<?php

include "config.php";
include "admin.php";

$page = intval($_GET['page']);
$pageSize = intval($_GET['pageSize']);
$admin = new admin();
$admin->init(new config());
echo $admin->getModels($page, $pageSize);