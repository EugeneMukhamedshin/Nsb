<?php
    include 'config.php';

    $post = file_get_contents("php://input");
    $selectedModels = json_decode($post);

    // Create connection
    $conn = new mysqli($serverName, $username, $password, $dbName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $idStr = '(';

    foreach($selectedModels as $selectedModel) {
        if ($idStr != '(')
            $idStr = $idStr . ', ';
        $idStr = $idStr . strval($selectedModel -> Model -> Id);
    }

    $idStr = $idStr . ')';

    $sql = "DELETE FROM models WHERE id in ". $idStr;
    $conn->query($sql);
    echo $sql;

    $conn->close();
