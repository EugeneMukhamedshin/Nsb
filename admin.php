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
SELECT
  m.id,
  m.name,
  m.description,
  m.link,
  mf_obj.translit_file_name obj_filename,
  mf_mtl.translit_file_name mtl_filename,
  m.add_ground,
  m.add_background,
  m.enable_shadows,
  g.id AS ground_id,
  g.name AS ground_name,
  g.filename AS ground_filename,
  b.id AS bg_id,
  b.name AS bg_name,
  b.px AS bg_px,
  b.nx AS bg_nx,
  b.py AS bg_py,
  b.ny AS bg_ny,
  b.pz AS bg_pz,
  b.nz AS bg_nz
FROM models m
  LEFT JOIN model_files mf_obj
    ON m.id = mf_obj.model_id
    AND mf_obj.file_type = 1
  LEFT JOIN model_files mf_mtl
    ON m.id = mf_mtl.model_id
    AND mf_mtl.file_type = 2
  LEFT JOIN grounds g
    ON m.ground_id = g.id
  LEFT JOIN backgrounds b
    ON m.background_id = b.id
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
            "AddBackground" => $row['add_background'] == 1,
            "EnableShadows" => $row['enable_shadows'] == 1,
            "Ground" => array(
                "Id" => $row['ground_id'],
                "Name" => $row['ground_name'],
                "FileName" => $row['ground_filename']
            ),
            "Background" => array(
                "Id" => $row['bg_id'],
                "Name" => $row['bg_name'],
                "Px" => $row['bg_px'],
                "Py" => $row['bg_py'],
                "Pz" => $row['bg_pz'],
                "Nx" => $row['bg_nx'],
                "Ny" => $row['bg_ny'],
                "Nz" => $row['bg_nz']
            )
        );
        $conn->close();
        return json_encode($model);
    }

    public function getModels($page, $pageSize)
    {
        $conn = $this->createConnection();
        $startRec = ($page - 1) * $pageSize;
        $ps = $pageSize + 1;
        $sql = "
SELECT
  m.id,
  m.name,
  m.description,
  m.link,
  mf_obj.translit_file_name obj_filename,
  mf_mtl.translit_file_name mtl_filename,
  m.add_ground,
  m.add_background,
  m.enable_shadows,
  g.id AS ground_id,
  g.name AS ground_name,
  g.filename AS ground_filename,
  b.id AS bg_id,
  b.name AS bg_name,
  b.px AS bg_px,
  b.nx AS bg_nx,
  b.py AS bg_py,
  b.ny AS bg_ny,
  b.pz AS bg_pz,
  b.nz AS bg_nz
FROM models m
  LEFT JOIN model_files mf_obj
    ON m.id = mf_obj.model_id
    AND mf_obj.file_type = 1
  LEFT JOIN model_files mf_mtl
    ON m.id = mf_mtl.model_id
    AND mf_mtl.file_type = 2
  LEFT JOIN grounds g
    ON m.ground_id = g.id
  LEFT JOIN backgrounds b
    ON m.background_id = b.id
LIMIT " . $startRec . "," . $ps;

        $result = $conn->query($sql);

        $models = array("models" => array());
        while ($row = $result->fetch_assoc()) {
            $models["models"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name'],
                "Description" => $row['description'],
                "Link" => $row['link'],
                "ObjFileName" => $row['obj_filename'],
                "MtlFileName" => $row['mtl_filename'],
                "AddGround" => $row['add_ground'] == 1,
                "AddBackground" => $row['add_background'] == 1,
                "EnableShadows" => $row['enable_shadows'] == 1,
                "Ground" => array(
                    "Id" => $row['ground_id'],
                    "Name" => $row['ground_name'],
                    "FileName" => $row['ground_filename']
                ),
                "Background" => array(
                    "Id" => $row['bg_id'],
                    "Name" => $row['bg_name'],
                    "Px" => $row['bg_px'],
                    "Py" => $row['bg_py'],
                    "Pz" => $row['bg_pz'],
                    "Nx" => $row['bg_nx'],
                    "Ny" => $row['bg_ny'],
                    "Nz" => $row['bg_nz']
                )
            );
        }
        $conn->close();
        return json_encode($models);
    }

    public function addModel()
    {
        $conn = $this->createConnection();
        $name = 'Новая модель';
        $sql = "INSERT INTO models (name, add_ground, add_background, enable_shadows) VALUES ('" . $name . "', 1, 1, 1)";
        $conn->query($sql);
        $id = $conn->insert_id;
        $conn->close();
        return $this->getModel($id);
    }

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

        $ground = new ground();
        $ground->updateGround($updated->Ground);

        $background = new background();
        $background->updateBackround($updated->Background);

        $sql = "
UPDATE models 
SET
  name = '" . $updated->Name . "'
 ,description = '" . $updated->Description . "'
 ,link = '" . $updated->Link . "'
 ,ground_id = '" . $updated->Ground->Id . "'
 ,background_id = '" . $updated->Background->Id . "'
 ,add_ground = " . ($updated->AddGround ? "1" : "0") . "
 ,add_background = " . ($updated->AddBackground ? "1" : "0") . "
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

    public function getFileType($fileName)
    {
        $fileType = 3;      // resource file
        if (substr($fileName, -3) == 'obj')
            $fileType = 1;  // obj file
        if (substr($fileName, -3) == 'mtl')
            $fileType = 2;  // mtl file
        return $fileType;
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

class ground extends dataModel
{
    function getGround($id)
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, filename FROM grounds WHERE id = " . $id;

        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $ground = array(
            "Id" => $row['id'],
            "Name" => $row['name'],
            "FileName" => $row['filename']
        );
        $conn->close();
        return json_encode($ground);
    }

    function getGrounds()
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, filename FROM grounds";

        $result = $conn->query($sql);

        $arr = array("grounds" => array());
        while ($row = $result->fetch_assoc()) {
            $arr["grounds"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name'],
                "FileName" => $row['filename']
            );
        }
        $conn->close();
        return json_encode($arr);
    }

    public function addGround()
    {
        $conn = $this->createConnection();
        $name = 'Новое основание';
        $sql = "INSERT INTO grounds (name) VALUES ('" . $name . "')";
        $conn->query($sql);
        $id = $conn->insert_id;
        $conn->close();
        return $this->getGround($id);
    }

    public function updateGround($updated) {
        $conn = $this->createConnection();

        $sql = "
UPDATE grounds 
SET
  name = '" . $updated->Name . "'
 ,filename = '" . $updated->FileName . "'
WHERE
  id = " . $updated->Id . "
;";
        $conn->query($sql);
        $conn->close();

        return $this->getGround($updated->Id);
    }

    public function updateFileName($groundId, $fileName)
    {
        $conn = $this->createConnection();

        $sql = "
UPDATE grounds 
SET
  filename = '" . $fileName . "'
WHERE
  id = " . $groundId . "
;";

        $conn->query($sql);
        $conn->close();

        return $this->getGround($groundId);
    }
}

class background extends dataModel
{
    function getBackground($id)
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, px, py, pz, nx, ny, nz FROM backgrounds WHERE id = " . $id;

        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $background = array(
            "Id" => $row['id'],
            "Name" => $row['name'],
            "Px" => $row['px'],
            "Py" => $row['py'],
            "Pz" => $row['pz'],
            "Nx" => $row['nx'],
            "Ny" => $row['ny'],
            "Nz" => $row['nz']
        );
        $conn->close();
        return json_encode($background);
    }

    function getBackgrounds()
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, px, py, pz, nx, ny, nz FROM backgrounds";

        $result = $conn->query($sql);

        $arr = array("backgrounds" => array());
        while ($row = $result->fetch_assoc()) {
            $arr["backgrounds"][] = array(
                "Id" => $row['id'],
                "Name" => $row['name'],
                "Px" => $row['px'],
                "Py" => $row['py'],
                "Pz" => $row['pz'],
                "Nx" => $row['nx'],
                "Ny" => $row['ny'],
                "Nz" => $row['nz']
            );
        }
        $conn->close();
        return json_encode($arr);
    }

    public function addBackground()
    {
        $conn = $this->createConnection();
        $name = 'Новый фон';
        $sql = "INSERT INTO backgrounds (name) VALUES ('" . $name . "')";
        $conn->query($sql);
        $id = $conn->insert_id;
        $conn->close();
        return $this->getBackground($id);
    }

    public function updateBackround($updated) {
        $conn = $this->createConnection();

        $sql = "
UPDATE backgrounds 
SET
  name = '" . $updated->Name . "'
 ,px = '" . $updated->Px . "'
 ,py = '" . $updated->Py . "'
 ,pz = '" . $updated->Pz . "'
 ,nx = '" . $updated->Nx . "'
 ,ny = '" . $updated->Ny . "'
 ,nz = '" . $updated->Nz . "'
WHERE
  id = " . $updated->Id . "
;";
        $conn->query($sql);
        $conn->close();

        return $this->getBackground($updated->Id);
    }
}