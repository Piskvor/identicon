<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Identicon' . DIRECTORY_SEPARATOR . 'Generator.php';

$generator = new Identicon\Generator($_GET['hash'], $_GET['size']);
if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && $generator->getHash() === $_SERVER['HTTP_IF_NONE_MATCH']) {
    header('HTTP/1.1 304 Not Modified');
} else {
    $generator->getImagePng();
}
