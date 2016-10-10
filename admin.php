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
        $sql = "
SELECT m.id, m.name, m.description, m.link, mf_obj.translit_file_name obj_filename, mf_mtl.translit_file_name mtl_filename, m.add_ground, m.enable_shadows
  FROM models m
  LEFT JOIN model_files mf_obj ON m.id = mf_obj.model_id AND mf_obj.file_type = 1
  LEFT JOIN model_files mf_mtl ON m.id = mf_mtl.model_id AND mf_mtl.file_type = 2
  WHERE m.id = " . $id;

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $model = array(
            "Id" => $row['id'],
            "Name" => $row['name'],
            "Description" => $row['description'],
            "Link" => $row['link'],
            "ObjFileName" => $row['obj_filename'],
            "MtlFileName" => $row['mtl_filename'],
            "AddGround" => $row['add_ground'] == 1,
            "EnableShadows" => $row['enable_shadows'] == 1
        );
        $conn->close();
        return json_encode($model);
    }

    public function getModels($page, $pageSize)
    {
        $conn = $this->createConnection();
        $startRec = ($page - 1) * $pageSize;
        $ps = $pageSize + 1;
        $sql = "SELECT id, name, description, link, add_ground, enable_shadows FROM models LIMIT " . $startRec . "," . $ps;

        $result = $conn->query($sql);

        $models = array("models" => array());
        while ($row = $result->fetch_assoc()) {
            $models["models"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name'],
                "Description" => $row['description'],
                "Link" => $row['link'],
                "AddGround" => $row['add_ground'] == 1,
                "EnableShadows" => $row['enable_shadows'] == 1
            );
        }
        $conn->close();
        return json_encode($models);
    }

    public function addModel()
    {
        $conn = $this->createConnection();
        $name = 'Новая модель';
        $sql = "INSERT INTO models (name, add_ground, enable_shadows) VALUES ('" . $name . "', 1, 1)";
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

    public function updateModel($updated)
    {
        $conn = $this->createConnection();

        $sql = "
UPDATE models 
SET
  name = '" . $updated->Name . "'
 ,description = '" . $updated->Description . "'
 ,link = '" . $updated->Link . "'
 ,add_ground = " . ($updated->AddGround ? "1" : "0") . "
 ,enable_shadows = " . ($updated->EnableShadows ? "1" : "0") . "
WHERE
  id = " . $updated->Id . "
;";
        $conn->query($sql);
        $conn->close();

        return $this->getModel($updated->Id);
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

    public function transliterateMtl($modelId, $fileName)
    {
        echo 'Start transliterate' . "\r\n";;
        $config = new config();
        $dirName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $config->uploadDir . DIRECTORY_SEPARATOR . $modelId;
        $info = null;
        $delimiter_pattern = "/[\s]+/";
        $translate = new translate();

        if (strpos($fileName, ".mtl") !== false) {
            $readHandle = fopen($dirName . DIRECTORY_SEPARATOR . $fileName, "r");
            $writeHandle = fopen($dirName . DIRECTORY_SEPARATOR . "_" . $fileName, "w");
            if ($readHandle && $writeHandle) {
                while (($fline = fgets($readHandle)) !== false) {
                    $decodedLine = iconv("Windows-1251", "UTF-8", $fline);
                    $line = trim($decodedLine);
                    if (strlen($line) !== 0 && $line[0] !== '#') {
                        $pos = strpos($line, ' ');
                        $key = ($pos >= 0) ? substr($line, 0, $pos) : $line;
                        $key = strtolower($key);
                        $value = ($pos >= 0) ? substr($line, $pos + 1) : '';
                        $value = trim($value);
                        switch (strtolower($key)) {
                            case 'map_ka':
                            case 'map_kd':
                            case 'map_ks':
                            case 'map_d':
                            case 'map_bump':
                            case 'bump':
                                $items = preg_split($delimiter_pattern, $value);
                                $pos = array_search('-bm', $items);
                                if ($pos) {
                                    array_splice($items, $pos, 2);
                                }
                                $pos = array_search('-s', $items);
                                if ($pos) {
                                    array_splice($items, $pos, 4);
                                }
                                $pos = array_search('-o', $items);
                                if ($pos) {
                                    array_splice($items, $pos, 4);
                                }
                                $url = implode(" ", $items);
                                $url_parts = explode("\\", $url);
                                $resourceFileName = end($url_parts);
                                $decodedLine = str_replace($url, $translate->transliterate($resourceFileName), $decodedLine);
                                echo $line;
                            default:
                                break;
                        }
                    }
                    fwrite($writeHandle, $decodedLine);
                }
            } else {
                echo "Error opening file";
            }

            fclose($writeHandle);
            fclose($readHandle);

            rename($dirName . DIRECTORY_SEPARATOR . "_" . $fileName, $dirName . DIRECTORY_SEPARATOR . $fileName);
            return;
        }
    }
}