<?php

include "admin.php";

$id = intval($_GET['id']);
$ground = new ground();
if ($id)
    echo $ground->getGround($id);
else
    echo $ground->getGrounds();