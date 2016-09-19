<?php
    header('Content-type: text/html; charset=utf-8');

    $keys = array_keys($_POST);

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

    foreach($keys as $key) {
        $id = str_replace('cb_', '', $key);
        $sql = "DELETE FROM models WHERE id = '". $id . "'";
        $conn->query($sql);
    }

    echo 'Выбранные модели удалены';
    echo "<br/><a href='admin_models.php'>Назад</a>";

    $conn->close();
?>