<?php
    header('Content-type: text/html; charset=utf-8');

    include 'config.php';

    $keys = array_keys($_POST);

    // Create connection
    $conn = new mysqli($serverName, $username, $password, $dbName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    foreach($keys as $key) {
        $id = str_replace('cb_', '', $key);
        $sql = "DELETE FROM models WHERE id = '". $id . "'";
        $conn->query($sql);
    }

    echo 'Выбранные модели удалены';
    echo "<br/><a href='admin_models.php'>Назад</a>";

    $conn->close();
?>