<?php

include "admin.php";

$id = intval($_GET['id']);
$page = intval($_GET['page']);
$pageSize = intval($_GET['pageSize']);
$model = new model();
if ($id)
    echo $model->getModel($id);
else
    echo $model->getModels($page, $pageSize);