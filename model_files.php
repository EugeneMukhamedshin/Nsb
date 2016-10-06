<?php

include "admin.php";

$modelId = intval($_GET['modelId']);
$page = intval($_GET['page']);
$pageSize = intval($_GET['pageSize']);
$modelFile = new modelFile();
echo $modelFile->getModelFiles($modelId, $page, $pageSize);