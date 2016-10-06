<?php
include "admin.php";
include "translate.php";

$config = new config();
$errorMsg = '';
if (!empty($_FILES)) {
    $tempPath = $_FILES['file']['tmp_name'];
    $tr = new translate();
    $fileName = $_FILES['file']['name'];
    $trFileName = $tr->transliterate($_FILES['file']['name']);
    $modelId = $_POST['id'];
    if (!$modelId) {
        $errorMsg = 'No Model Id';
    }
    else {
        $dirName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $config->uploadDir . DIRECTORY_SEPARATOR . $modelId;
        if (!file_exists($dirName)) {
            mkdir($dirName);
            mkdir($dirName . DIRECTORY_SEPARATOR . 'unzipped');
        }
        $uploadPath = $dirName . DIRECTORY_SEPARATOR . $trFileName;
        move_uploaded_file($tempPath, $uploadPath);

        $modelFile = new modelFile();
        echo $modelFile->addModelFile($modelId, $fileName, $trFileName);
        return;
    }
} else {
    $errorMsg = 'No files';
}

$answer = array('error' => $errorMsg);
$json = json_encode($answer);
echo $json;

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $id = $_POST['id'];
//    foreach ($_FILES['files']['name'] as $i => $name) {
//        echo $_FILES['files']['name'];
////        if (strlen($_FILES['files']['name'][$i]) > 1) {
////            if (strrpos($_FILES['files']['name'][$i], '.obj') > 0) {
////                $obj_filename = $_FILES['files']['name'][$i];
////            }
////            if (strrpos($_FILES['files']['name'][$i], '.mtl') > 0) {
////                $mtl_filename = $_FILES['files']['name'][$i];
////            }
////        }
//    }
//    return;
//    include "config.php";
//    include "admin.php";
//
//    $admin = new admin();
//    $admin->init(new config());
//
//
//    echo $admin->addFilesToModel();
//}