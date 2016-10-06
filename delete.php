<?php

include "admin.php";

$post = file_get_contents("php://input");
$selectedModels = json_decode($post);

$ids = array();
foreach ($selectedModels as $selectedModel) {
    $ids[] = $selectedModel->Model->Id;
}

$model = new model();
echo $model->deleteModels($ids);
