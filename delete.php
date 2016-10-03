<?php

include "config.php";
include "admin.php";

$admin = new admin();
$admin->init(new config());

$post = file_get_contents("php://input");
$selectedModels = json_decode($post);

$ids = '';
foreach ($selectedModels as $selectedModel) {
    $ids[$counter++] = $selectedModel->Model->Id;
}

echo $admin->deleteModels($ids);
