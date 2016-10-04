<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $blob = $_POST['blob'];

    include "config.php";
    include "admin.php";

    $admin = new admin();
    $admin->init(new config());

    echo $admin->addFilesToModel();
}