<?php

include "config.php";

class dataModel
{
    protected $config = null;

    function __construct()
    {
        $this->config = new config();
    }

    protected function createConnection()
    {
        // Create connection
        $conn = new mysqli($this->config->serverName, $this->config->username, $this->config->password, $this->config->dbName);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

}

class model extends dataModel
{
    public function getModel($id)
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, obj_filename, mtl_filename FROM models WHERE id = " . $id;

        $result = $conn->query($sql);

        $models = array("models" => array());
        while ($row = $result->fetch_assoc()) {
            $models["models"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name']
            );
        }
        $conn->close();
        return json_encode($models);
    }

    public function getModels($page, $pageSize)
    {
        $conn = $this->createConnection();
        $startRec = ($page - 1) * $pageSize;
        $ps = $pageSize + 1;
        $sql = "SELECT id, name, obj_filename, mtl_filename FROM models LIMIT " . $startRec . "," . $ps;

        $result = $conn->query($sql);

        $models = array("models" => array());
        while ($row = $result->fetch_assoc()) {
            $models["models"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name']
            );
        }
        $conn->close();
        return json_encode($models);
    }

    public function addModel()
    {
        $conn = $this->createConnection();
        $name = 'Новая модель';
        $sql = "INSERT INTO models (name) VALUES ('" . $name . "')";
        $conn->query($sql);
        $id = $conn->insert_id;
        $conn->close();
        return $this->getModel($id);
    }
//
//    public function validateModel($id)
//    {
//        $conn = $this->createConnection();
//        $sql = "SELECT id, name, obj_filename, mtl_filename FROM models WHERE id = '" . $id . "'";
//        $result = $conn->query($sql);
//
//        if ($result->num_rows > 0) {
//            // output data of each row
//            $row = $result->fetch_assoc();
//
//            $modelResult = '{"Id":"' . $row['id'] . '", "Name":"' . $row['name'] . '", "ObjFileName":"' . $row['obj_filename'] . '", "MtlFileName":"' . $row['mtl_filename'] . '"}';
//            $hasError = false;
//            $errors = '';
//            $errorCounter = 0;
//            if (!$row['obj_filename'] || $row['obj_filename'] == '') {
//                $hasError = true;
//                $errors[$errorCounter++] = 'Не задан OBJ файл';
//            }
//            if (!$row['mtl_filename'] || $row['mtl_filename'] == '') {
//                $hasError = true;
//                $errors[$errorCounter++] = 'Не задан MTL файл';
//            }
//
//            function reduce($result, $item) {
//                if ($result != '')
//                    $result = $result . ', ';
//                $result = $result . '"' . $item . '"';
//                return $result;
//            }
//
//            $validationResult = '{"HasErrors":"' . $hasError .'", "Errors":[' . array_reduce($errors, "reduce"). ']}';
//
//            $retVal = '{"model": ' . $modelResult .', "validation" : ' . $validationResult . '}';
//
//        } else {
//            $retVal = "{}";
//        }
//        $conn->close();
//        return $retVal;
//    }

    public function deleteModels($ids)
    {
        $conn = $this->createConnection();
        $idStr = '(';

        foreach ($ids as $id) {
            if ($idStr != '(')
                $idStr = $idStr . ', ';
            $idStr = $idStr . strval($id);
        }

        $idStr = $idStr . ')';

        $sql = "DELETE FROM models WHERE id in " . $idStr;
        $conn->query($sql);
        $conn->close();
    }
}

class modelFile extends dataModel
{

    public function getModelFile($id)
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, model_id, file_name, translit_file_name, file_type, is_valid FROM model_files WHERE id = " . $id;

        $result = $conn->query($sql);

        $arr = array("modelFiles" => array());
        while ($row = $result->fetch_assoc()) {
            $arr["models"][] = array(
                "Id" => $row['id'],
                "ModelId" => $row['modelId'],
                "FileName" => $row['file_name'],
                "TranslitFileName" => $row['translit_file_name'],
                "FileType" => $row['file_type'],
                "IsValid" => $row['is_valid']
            );
        }
        $conn->close();
        return json_encode($arr);
    }

    public function getModelFiles($modelId, $page, $pageSize)
    {
        $conn = $this->createConnection();
        $startRec = ($page - 1) * $pageSize;
        $ps = $pageSize + 1;
        $sql = "SELECT id, model_id, file_name, translit_file_name, file_type, is_valid FROM model_files WHERE model_id = " . $modelId . " LIMIT " . $startRec . "," . $ps;

        $result = $conn->query($sql);

        $arr = array("modelFiles" => array());
        while ($row = $result->fetch_assoc()) {
            $arr["modelFiles"][] = array(
                "Id" => $row['id'],
                "ModelId" => $row['modelId'],
                "FileName" => $row['file_name'],
                "TranslitFileName" => $row['translit_file_name'],
                "FileType" => $row['file_type'],
                "IsValid" => $row['is_valid']
            );
        }
        $conn->close();
        return json_encode($arr);
    }

    public function addModelFile($modelId, $fileName, $trFileName)
    {
        $conn = $this->createConnection();

        $sql = "DELETE FROM model_files WHERE translit_file_name = '" . $trFileName . "' AND model_id = " . $modelId;
        $conn->query($sql);

        $fileType = $this->getFileType($fileName);
        $sql = "
INSERT INTO model_files
(
  model_id
 ,file_name
 ,translit_file_name
 ,file_type
)
VALUES
(
  " . $modelId . "
 ,'" . $fileName . "'
 ,'" . $trFileName . "'
 ," . $fileType . "
);";
        $conn->query($sql);

        $id = $conn->insert_id;
        $conn->close();

        switch ($fileType) {
            case 1 :
                $this->transliterateObj();
            case 2 :
                $this->transliterateMtl($modelId, $trFileName);
        }

        return $this->getModelFile($id);
//
//
//        $errorMsg = '';
//        $dirName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $modelId;
//        if (strpos($fileName, '.obj') !== false) {
//            $zip = new ZipArchive();
//            $res = $zip->open($dirName . DIRECTORY_SEPARATOR . $fileName);
//            if ($res === TRUE) {
//                $objFile = $dirName . DIRECTORY_SEPARATOR . 'unzipped';
//                $zip->extractTo($objFile);
//                $zip->close();
//
//                $mtlLibFileName = '';
//                $handle = fopen($objFile . DIRECTORY_SEPARATOR . $fileName, "r");
//                if ($handle) {
//                    while (($line = fgets($handle)) !== false) {
//                        if (strpos($line, 'mtllib') !== false) {
//                            $mtlLibFileName = str_replace('mtllib ', '', trim($line));
//                            break;
//                        }
//                    }
//                    fclose($handle);
//                } else {
//                    $errorMsg = 'Can not open file';
//                }
//            } else {
//                $errorMsg = 'Not zip';
//            }
//        }
//        $answer = array('mtlLib' => $mtlLibFileName, 'error' => $errorMsg);
//        $json = json_encode($answer);
//        echo $json;
    }

    public function deleteModelFiles($files)
    {
        $conn = $this->createConnection();
        $idStr = '(';

        foreach ($files as $file) {
            if ($idStr != '(')
                $idStr = $idStr . ', ';
            $idStr = $idStr . strval($file->Id);
        }

        $idStr = $idStr . ')';

        $sql = "DELETE FROM model_files WHERE id in " . $idStr;
        $conn->query($sql);
        $conn->close();
    }

    /**
     * @param $fileName
     * @return int
     */
    public function getFileType($fileName)
    {
        $fileType = 3;      // resource file
        if (substr($fileName, -3) == 'obj')
            $fileType = 1;  // obj file
        if (substr($fileName, -3) == 'mtl')
            $fileType = 2;  // mtl file
        return $fileType;
    }

    function transliterateObj()
    {

    }

    function transliterateMtl($modelId, $fileName)
    {
        $config = new config();
        $dirName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $config->uploadDir . DIRECTORY_SEPARATOR . $modelId;
        $info = null;
        $delimiter_pattern = "\\s+";
        $materialsInfo = array();

        if (strpos($fileName, ".mtl") !== false) {
            $handle = fopen($dirName . DIRECTORY_SEPARATOR . $fileName, "r");
            if ($handle) {
                while (($fline = fgets($handle)) !== false) {
                    $line = trim($fline);

                    if (strlen($line) === 0 || $line[0] === '#') {

                        // Blank line or comment ignore
                        continue;

                    }

                    $pos = strpos($line, ' ');

                    $key = ($pos >= 0) ? substr($line, 0, $pos) : $line;
                    $key = strtolower($key);

                    $value = ($pos >= 0) ? substr($line, $pos + 1) : '';
                    $value = trim($value);

                    if ($key === 'newmtl') {

                        // New material

                        $info = array("name" => $value);
                        $materialsInfo[$value] = $info;

                    } else if ($info) {

                        if ($key === 'ka' || $key === 'kd' || $key === 'ks') {
                            continue;
                        } else {
                            $info[$key] = $value;
                        }
                    }
                }
                fclose($handle);
            } else {
                $errorMsg = 'Can not open file';
            }
        } else {
            $errorMsg = 'Not zip';
        }
        echo json_encode($materialsInfo);
    }
}

