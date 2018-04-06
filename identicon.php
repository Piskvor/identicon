<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Identicon' . DIRECTORY_SEPARATOR . 'Generator.php';

$generator = new Identicon\Generator($_GET['hash'], $_GET['size']);
$generator->getImagePng();
