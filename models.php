<?php

include 'config.php';

// Create connection
$conn = new mysqli($serverName, $username, $password, $dbName);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pageSize = 5;
$page = intval($_GET['page']);

$startRec = ($page - 1) * $pageSize;
$sql = "SELECT id, name, obj_filename, mtl_filename FROM models LIMIT " . $startRec . "," . $pageSize;

$result = $conn->query($sql);

echo '{"models": [';
$first = true;
// output data of each row
while ($row = $result->fetch_assoc()) {
    if (!$first)
        echo ",";
    $first = false;
    echo '{"Id":"' . $row['id'] . '", "Name":"' . $row['name'] . '", "ObjFileName":"' . $row['obj_filename'] . '", "MtlFileName":"' . $row['mtl_filename'] . '"}';
}
echo "]}";
$conn->close();
