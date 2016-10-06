<?php

include "admin.php";

$page = intval($_GET['page']);
$pageSize = intval($_GET['pageSize']);
$model = new model();
echo $model->getModels($page, $pageSize);