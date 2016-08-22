<?php
    $count = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $model_name = $_POST['name'];
        foreach ($_FILES['files']['name'] as $i => $name) {
            if (strlen($_FILES['files']['name'][$i]) > 1) {
                if (strrpos($_FILES['files']['name'][$i], '.obj') > 0) {
                    $obj_filename = $_FILES['files']['name'][$i]; 
                }
                if (strrpos($_FILES['files']['name'][$i], '.mtl') > 0) {
                    $mtl_filename = $_FILES['files']['name'][$i];
                }
            }
        }
        echo $model_name;
        echo $obj_filename;
        echo $mtl_filename;

        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "nsb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "INSERT INTO models (name, obj_filename, mtl_filename) VALUES ('" . $model_name . "', '" . $obj_filename ."', '" . $mtl_filename . "')";
        $result = $conn->query($sql);

        $id = $conn->insert_id;

        mkdir('content/' . $id);

        foreach ($_FILES['files']['name'] as $i => $name) {
            if (strlen($_FILES['files']['name'][$i]) > 1) {
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], 'content/' . $id . '/' . $name)) {
                    $count++;
                }
            }
        }
    }
?>