<?php

if (!empty($_FILES)) {
    $tempPath = $_FILES['file']['tmp_name'];
    $dirName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $_POST['id'];
    if (!file_exists($dirName)) {
        mkdir($dirName);
        mkdir($dirName . DIRECTORY_SEPARATOR . 'unzipped');
    }
    $uploadPath = $dirName . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
    move_uploaded_file($tempPath, $uploadPath);

    if (strpos($_FILES['file']['name'], '.obj') !== false) {
        $zip = new ZipArchive;
        $res = $zip->open($uploadPath);
        if ($res === TRUE) {
            $objFile = $dirName . DIRECTORY_SEPARATOR . 'unzipped' . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
            $zip->extractTo($objFile);
            $zip->close();

            $handle = fopen($objFile, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, 'mtllib') !== false) {
                        echo $line;
                        break;
                    }
                }

                fclose($handle);
            } else {
                // error opening the file.
            }
            echo 'woot!';
        } else {
            echo 'doh!';
        }
    }
    $answer = array('answer' => 'File transfer completed');
    $json = json_encode($answer);
    echo $json;
} else {
    echo 'No files';
}

return;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    foreach ($_FILES['files']['name'] as $i => $name) {
        echo $_FILES['files']['name'];
//        if (strlen($_FILES['files']['name'][$i]) > 1) {
//            if (strrpos($_FILES['files']['name'][$i], '.obj') > 0) {
//                $obj_filename = $_FILES['files']['name'][$i];
//            }
//            if (strrpos($_FILES['files']['name'][$i], '.mtl') > 0) {
//                $mtl_filename = $_FILES['files']['name'][$i];
//            }
//        }
    }
    return;
    include "config.php";
    include "admin.php";

    $admin = new admin();
    $admin->init(new config());


    echo $admin->addFilesToModel();
}