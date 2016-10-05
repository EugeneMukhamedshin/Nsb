<?php

include "admin.php";

$modelId = intval($_GET['modelId']);
$admin = new admin();
echo $admin->getModelFiles($modelId);