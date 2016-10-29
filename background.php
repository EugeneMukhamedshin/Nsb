<?php

include "admin.php";

$id = intval($_GET['id']);
$background = new background();
if ($id)
    echo $background->getBackground($id);
else
    echo $background->getBackgrounds();