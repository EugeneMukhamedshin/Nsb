<?php
/**
 * Created by PhpStorm.
 * User: Evgeniy
 * Date: 08.10.2016
 * Time: 13:24
 */

include "admin.php";

$post = file_get_contents("php://input");
$updated = json_decode($post);

echo json_encode($updated);

$background = new background();
$background->updateBackround($updated);
