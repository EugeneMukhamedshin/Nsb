<?php

include "admin.php";

$post = file_get_contents("php://input");
$selectedModels = json_decode($post);

$ids = '';
foreach ($selectedModels as $selectedModel) {
    $ids[$counter++] = $selectedModel->Model->Id;
}

$admin = new admin();
echo $admin->deleteModels($ids);
