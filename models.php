<?php

include "admin.php";

$page = intval($_GET['page']);
$pageSize = intval($_GET['pageSize']);
$admin = new admin();
echo $admin->getModels($page, $pageSize);