<?php

include "admin.php";

$post = file_get_contents("php://input");
$selectedFiles = json_decode($post);

$files = array();
foreach ($selectedFiles as $selectedFile) {
    $files[] = $selectedFile->Model;
}

$modelFile = new modelFile();
echo $modelFile->deleteModelFiles($files);
