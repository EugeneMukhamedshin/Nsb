<?php

/**
 * Created by PhpStorm.
 * User: Evgeniy
 * Date: 03.10.2016
 * Time: 23:02
 */
class admin
{
    private $config = null;

    public function init($config)
    {
        $this->config = $config;
    }

    private function createConnection()
    {
        // Create connection
        $conn = new mysqli($this->config->serverName, $this->config->username, $this->config->password, $this->config->dbName);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public function getModels($page, $pageSize)
    {
        $conn = $this->createConnection();
        $startRec = ($page - 1) * $pageSize;
        $ps = $pageSize + 1;
        $sql = "SELECT id, name, obj_filename, mtl_filename FROM models LIMIT " . $startRec . "," . $ps;

        $result = $conn->query($sql);

        $retVal = '{"models": [';
        $first = true;
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            if (!$first)
                $retVal = $retVal . ",";
            $first = false;
            $retVal = $retVal . '{"Id":"' . $row['id'] . '", "Name":"' . $row['name'] . '", "ObjFileName":"' . $row['obj_filename'] . '", "MtlFileName":"' . $row['mtl_filename'] . '"}';
        }
        $retVal = $retVal . "]}";
        $conn->close();
        return $retVal;
    }

    public function addModel()
    {
        $conn = $this->createConnection();
        $name = 'Новая модель';
        $sql = "INSERT INTO models (name) VALUES ('" . $name . "')";
        $conn->query($sql);
        $id = $conn->insert_id;
        $conn->close();
        return $this->validateModel($id);
    }

    public function addFilesToModel($files, $id)
    {
//        $conn = $this->createConnection();
//        $sql = "INSERT INTO models (name, obj_filename, mtl_filename) VALUES ('" . $model_name . "', '" . $obj_filename ."', '" . $mtl_filename . "')";
//        $result = $conn->query($sql);

//        $id = $conn->insert_id;

        $error = '';

        $dirName = 'content/' . $id;
        if (!file_exists($dirName))
            mkdir($dirName);

        foreach ($files['name'] as $i => $name) {
            if (strlen($files['name'][$i]) > 1) {
                if (!move_uploaded_file($files['tmp_name'][$i], 'content/' . $id . '/' . $name)) {
                    $error = 'unable to move file into content folder';
                }
            }
        }
    }

    public function validateModel($id)
    {
        $conn = $this->createConnection();
        $sql = "SELECT id, name, obj_filename, mtl_filename FROM models WHERE id = '" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();

            $modelResult = '{"Id":"' . $row['id'] . '", "Name":"' . $row['name'] . '", "ObjFileName":"' . $row['obj_filename'] . '", "MtlFileName":"' . $row['mtl_filename'] . '"}';
            $hasError = false;
            $errors = '';
            $errorCounter = 0;
            if (!$row['obj_filename'] || $row['obj_filename'] == '') {
                $hasError = true;
                $errors[$errorCounter++] = 'Не задан OBJ файл';
            }
            if (!$row['mtl_filename'] || $row['mtl_filename'] == '') {
                $hasError = true;
                $errors[$errorCounter++] = 'Не задан MTL файл';
            }

            function reduce($result, $item) {
                if ($result != '')
                    $result = $result . ', ';
                $result = $result . '"' . $item . '"';
                return $result;
            }

            $validationResult = '{"HasErrors":"' . $hasError .'", "Errors":[' . array_reduce($errors, "reduce"). ']}';

            $retVal = '{"model": ' . $modelResult .', "validation" : ' . $validationResult . '}';

        } else {
            $retVal = "{}";
        }
        $conn->close();
        return $retVal;
    }

    public function deleteModels($ids)
    {
        $conn = $this->createConnection();
        $idStr = '(';

        foreach($ids as $id) {
            if ($idStr != '(')
                $idStr = $idStr . ', ';
            $idStr = $idStr . strval($id);
        }

        $idStr = $idStr . ')';

        $sql = "DELETE FROM models WHERE id in ". $idStr;
        $conn->query($sql);
        $conn->close();
    }
}