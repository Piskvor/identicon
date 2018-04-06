<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Identicon' . DIRECTORY_SEPARATOR . 'Generator.php';

$generator = new Identicon\Generator();

/* parse hash string */
$hash = md5($_GET['hash']); // do not assume we're actually getting a sane hash
$size = (int) $_GET['size'];
if ($size <= 0) { // set sane default size
    $size = 48;
} elseif ($size > 1024) { // set maximum size
    $size = 1024;
}

$csh=hexdec($hash[0]); // corner sprite shape
$ssh=hexdec($hash[1]); // side sprite shape
$xsh=hexdec($hash[2])&7; // center sprite shape

$cro=hexdec($hash[3])&3; // corner sprite rotation
$sro=hexdec($hash[4])&3; // side sprite rotation
$xbg=hexdec($hash[5])%2; // center sprite background

/* corner sprite foreground color */
$cfr=hexdec(substr($hash,6,2));
$cfg=hexdec(substr($hash,8,2));
$cfb=hexdec(substr($hash,10,2));

/* side sprite foreground color */
$sfr=hexdec(substr($hash,12,2));
$sfg=hexdec(substr($hash,14,2));
$sfb=hexdec(substr($hash,16,2));

/* final angle of rotation */
$angle=hexdec(substr($hash,18,2));

/* size of each sprite */
$spriteZ=128;

/* start with blank 3x3 identicon */
$identicon=imagecreatetruecolor($spriteZ*3,$spriteZ*3);
imageantialias($identicon,TRUE);

/* assign white as background */
$bg=imagecolorallocate($identicon,255,255,255);
imagefilledrectangle($identicon,0,0,$spriteZ,$spriteZ,$bg);

/* generate corner sprites */
$corner=$generator->getsprite($csh,$cfr,$cfg,$cfb,$cro);
imagecopy($identicon,$corner,0,0,0,0,$spriteZ,$spriteZ);
$corner=imagerotate($corner,90,$bg);
imagecopy($identicon,$corner,0,$spriteZ*2,0,0,$spriteZ,$spriteZ);
$corner=imagerotate($corner,90,$bg);
imagecopy($identicon,$corner,$spriteZ*2,$spriteZ*2,0,0,$spriteZ,$spriteZ);
$corner=imagerotate($corner,90,$bg);
imagecopy($identicon,$corner,$spriteZ*2,0,0,0,$spriteZ,$spriteZ);

/* generate side sprites */
$side=$generator->getsprite($ssh,$sfr,$sfg,$sfb,$sro);
imagecopy($identicon,$side,$spriteZ,0,0,0,$spriteZ,$spriteZ);
$side=imagerotate($side,90,$bg);
imagecopy($identicon,$side,0,$spriteZ,0,0,$spriteZ,$spriteZ);
$side=imagerotate($side,90,$bg);
imagecopy($identicon,$side,$spriteZ,$spriteZ*2,0,0,$spriteZ,$spriteZ);
$side=imagerotate($side,90,$bg);
imagecopy($identicon,$side,$spriteZ*2,$spriteZ,0,0,$spriteZ,$spriteZ);

/* generate center sprite */
$center=$generator->getcenter($xsh,$cfr,$cfg,$cfb,$sfr,$sfg,$sfb,$xbg);
imagecopy($identicon,$center,$spriteZ,$spriteZ,0,0,$spriteZ,$spriteZ);

// $identicon=imagerotate($identicon,$angle,$bg);

/* make white transparent */
imagecolortransparent($identicon,$bg);

/* create blank image according to specified dimensions */
$resized=imagecreatetruecolor($size, $size);
imageantialias($resized,TRUE);

/* assign white as background */
$bg=imagecolorallocate($resized,255,255,255);
imagefilledrectangle($resized,0,0, $size, $size,$bg);

/* resize identicon according to specification */
imagecopyresampled($resized,$identicon,0,0,(imagesx($identicon)-$spriteZ*3)/2,(imagesx($identicon)-$spriteZ*3)/2, $size,
    $size,$spriteZ*3,$spriteZ*3);

/* make white transparent */
imagecolortransparent($resized,$bg);

/* and finally, send to standard output */
header('Content-Type: image/png');
imagepng($resized);
