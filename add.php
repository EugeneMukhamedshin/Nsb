<?php

include "config.php";
include "admin.php";

$admin = new admin();
$admin->init(new config());
echo $admin->addModel();