<?php

namespace Identicon;

class Generator
{
    /** @var string */
    private $hash;

    /** @var int */
    private $size;

    /**
     * size of each sprite
     * @var int
     */
    private $spriteZ=128;


    public function __construct($textInput, $size)
    {
        $this->hash = md5($textInput); // note that we now have an actual hash - don't rely on being passed one!

        if ($size <= 0) { // set sane default size
            $size = 48;
        } elseif ($size > 1024) { // set maximum size
            $size = 1024;
        }
        $this->size = $size;
    }
    
    public function getImagePng() {

        $csh=hexdec($this->hash[0]); // corner sprite shape
        $ssh=hexdec($this->hash[1]); // side sprite shape
        $xsh=hexdec($this->hash[2])&7; // center sprite shape

        $cro=hexdec($this->hash[3])&3; // corner sprite rotation
        $sro=hexdec($this->hash[4])&3; // side sprite rotation
        $xbg=hexdec($this->hash[5])%2; // center sprite background

        /* corner sprite foreground color */
        $cfr=hexdec(substr($this->hash,6,2));
        $cfg=hexdec(substr($this->hash,8,2));
        $cfb=hexdec(substr($this->hash,10,2));

        /* side sprite foreground color */
        $sfr=hexdec(substr($this->hash,12,2));
        $sfg=hexdec(substr($this->hash,14,2));
        $sfb=hexdec(substr($this->hash,16,2));

        /* final angle of rotation */
        $angle=hexdec(substr($this->hash,18,2));

        /* start with blank 3x3 identicon */
        $identicon=imagecreatetruecolor($this->spriteZ*3,$this->spriteZ*3);
        imageantialias($identicon,TRUE);

        /* assign white as background */
        $bg=imagecolorallocate($identicon,255,255,255);
        imagefilledrectangle($identicon,0,0,$this->spriteZ,$this->spriteZ,$bg);

        /* generate corner sprites */
        $corner=$this->getSprite($csh,$cfr,$cfg,$cfb,$cro);
        imagecopy($identicon,$corner,0,0,0,0,$this->spriteZ,$this->spriteZ);
        $corner=imagerotate($corner,90,$bg);
        imagecopy($identicon,$corner,0,$this->spriteZ*2,0,0,$this->spriteZ,$this->spriteZ);
        $corner=imagerotate($corner,90,$bg);
        imagecopy($identicon,$corner,$this->spriteZ*2,$this->spriteZ*2,0,0,$this->spriteZ,$this->spriteZ);
        $corner=imagerotate($corner,90,$bg);
        imagecopy($identicon,$corner,$this->spriteZ*2,0,0,0,$this->spriteZ,$this->spriteZ);

        /* generate side sprites */
        $side=$this->getSprite($ssh,$sfr,$sfg,$sfb,$sro);
        imagecopy($identicon,$side,$this->spriteZ,0,0,0,$this->spriteZ,$this->spriteZ);
        $side=imagerotate($side,90,$bg);
        imagecopy($identicon,$side,0,$this->spriteZ,0,0,$this->spriteZ,$this->spriteZ);
        $side=imagerotate($side,90,$bg);
        imagecopy($identicon,$side,$this->spriteZ,$this->spriteZ*2,0,0,$this->spriteZ,$this->spriteZ);
        $side=imagerotate($side,90,$bg);
        imagecopy($identicon,$side,$this->spriteZ*2,$this->spriteZ,0,0,$this->spriteZ,$this->spriteZ);

        /* generate center sprite */
        $center=$this->getCenter($xsh,$cfr,$cfg,$cfb,$sfr,$sfg,$sfb,$xbg);
        imagecopy($identicon,$center,$this->spriteZ,$this->spriteZ,0,0,$this->spriteZ,$this->spriteZ);

// $identicon=imagerotate($identicon,$angle,$bg);

        /* make white transparent */
        imagecolortransparent($identicon,$bg);

        /* create blank image according to specified dimensions */
        $resized=imagecreatetruecolor($this->size, $this->size);
        imageantialias($resized,TRUE);

        /* assign white as background */
        $bg=imagecolorallocate($resized,255,255,255);
        imagefilledrectangle($resized,0,0, $this->size, $this->size,$bg);

        /* resize identicon according to specification */
        imagecopyresampled($resized,$identicon,0,0,(imagesx($identicon)-$this->spriteZ*3)/2,(imagesx($identicon)-$this->spriteZ*3)/2, $this->size,
            $this->size,$this->spriteZ*3,$this->spriteZ*3);

        /* make white transparent */
        imagecolortransparent($resized,$bg);

        /* and finally, send to standard output */
        header('Content-Type: image/png');
        imagepng($resized);
    }


    /* generate sprite for corners and sides */
    private function getSprite($shape,$R,$G,$B,$rotation) {
        $sprite=imagecreatetruecolor($this->spriteZ,$this->spriteZ);
        imageantialias($sprite,TRUE);
        $fg=imagecolorallocate($sprite,$R,$G,$B);
        $bg=imagecolorallocate($sprite,255,255,255);
        imagefilledrectangle($sprite,0,0,$this->spriteZ,$this->spriteZ,$bg);
        switch($shape) {
            case 0: // triangle
                $shape=array(
                    0.5,1,
                    1,0,
                    1,1
                );
                break;
            case 1: // parallelogram
                $shape=array(
                    0.5,0,
                    1,0,
                    0.5,1,
                    0,1
                );
                break;
            case 2: // mouse ears
                $shape=array(
                    0.5,0,
                    1,0,
                    1,1,
                    0.5,1,
                    1,0.5
                );
                break;
            case 3: // ribbon
                $shape=array(
                    0,0.5,
                    0.5,0,
                    1,0.5,
                    0.5,1,
                    0.5,0.5
                );
                break;
            case 4: // sails
                $shape=array(
                    0,0.5,
                    1,0,
                    1,1,
                    0,1,
                    1,0.5
                );
                break;
            case 5: // fins
                $shape=array(
                    1,0,
                    1,1,
                    0.5,1,
                    1,0.5,
                    0.5,0.5
                );
                break;
            case 6: // beak
                $shape=array(
                    0,0,
                    1,0,
                    1,0.5,
                    0,0,
                    0.5,1,
                    0,1
                );
                break;
            case 7: // chevron
                $shape=array(
                    0,0,
                    0.5,0,
                    1,0.5,
                    0.5,1,
                    0,1,
                    0.5,0.5
                );
                break;
            case 8: // fish
                $shape=array(
                    0.5,0,
                    0.5,0.5,
                    1,0.5,
                    1,1,
                    0.5,1,
                    0.5,0.5,
                    0,0.5
                );
                break;
            case 9: // kite
                $shape=array(
                    0,0,
                    1,0,
                    0.5,0.5,
                    1,0.5,
                    0.5,1,
                    0.5,0.5,
                    0,1
                );
                break;
            case 10: // trough
                $shape=array(
                    0,0.5,
                    0.5,1,
                    1,0.5,
                    0.5,0,
                    1,0,
                    1,1,
                    0,1
                );
                break;
            case 11: // rays
                $shape=array(
                    0.5,0,
                    1,0,
                    1,1,
                    0.5,1,
                    1,0.75,
                    0.5,0.5,
                    1,0.25
                );
                break;
            case 12: // double rhombus
                $shape=array(
                    0,0.5,
                    0.5,0,
                    0.5,0.5,
                    1,0,
                    1,0.5,
                    0.5,1,
                    0.5,0.5,
                    0,1
                );
                break;
            case 13: // crown
                $shape=array(
                    0,0,
                    1,0,
                    1,1,
                    0,1,
                    1,0.5,
                    0.5,0.25,
                    0.5,0.75,
                    0,0.5,
                    0.5,0.25
                );
                break;
            case 14: // radioactive
                $shape=array(
                    0,0.5,
                    0.5,0.5,
                    0.5,0,
                    1,0,
                    0.5,0.5,
                    1,0.5,
                    0.5,1,
                    0.5,0.5,
                    0,1
                );
                break;
            default: // tiles
                $shape=array(
                    0,0,
                    1,0,
                    0.5,0.5,
                    0.5,0,
                    0,0.5,
                    1,0.5,
                    0.5,1,
                    0.5,0.5,
                    0,1
                );
                break;
        }
        /* apply ratios */
        for ($i=0;$i<count($shape);$i++)
            $shape[$i]=$shape[$i]*$this->spriteZ;
        imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
        /* rotate the sprite */
        for ($i=0;$i<$rotation;$i++)
            $sprite=imagerotate($sprite,90,$bg);
        return $sprite;
    }

    /* generate sprite for center block */
    private function getCenter($shape,$fR,$fG,$fB,$bR,$bG,$bB,$useBg) {
        $sprite=imagecreatetruecolor($this->spriteZ,$this->spriteZ);
        imageantialias($sprite,TRUE);
        $fg=imagecolorallocate($sprite,$fR,$fG,$fB);
        /* make sure there's enough contrast before we use background color of side sprite */
        if ($useBg>0 && (abs($fR-$bR)>127 || abs($fG-$bG)>127 || abs($fB-$bB)>127))
            $bg=imagecolorallocate($sprite,$bR,$bG,$bB);
        else
            $bg=imagecolorallocate($sprite,255,255,255);
        imagefilledrectangle($sprite,0,0,$this->spriteZ,$this->spriteZ,$bg);
        switch($shape) {
            case 0: // empty
                $shape=array();
                break;
            case 1: // fill
                $shape=array(
                    0,0,
                    1,0,
                    1,1,
                    0,1
                );
                break;
            case 2: // diamond
                $shape=array(
                    0.5,0,
                    1,0.5,
                    0.5,1,
                    0,0.5
                );
                break;
            case 3: // reverse diamond
                $shape=array(
                    0,0,
                    1,0,
                    1,1,
                    0,1,
                    0,0.5,
                    0.5,1,
                    1,0.5,
                    0.5,0,
                    0,0.5
                );
                break;
            case 4: // cross
                $shape=array(
                    0.25,0,
                    0.75,0,
                    0.5,0.5,
                    1,0.25,
                    1,0.75,
                    0.5,0.5,
                    0.75,1,
                    0.25,1,
                    0.5,0.5,
                    0,0.75,
                    0,0.25,
                    0.5,0.5
                );
                break;
            case 5: // morning star
                $shape=array(
                    0,0,
                    0.5,0.25,
                    1,0,
                    0.75,0.5,
                    1,1,
                    0.5,0.75,
                    0,1,
                    0.25,0.5
                );
                break;
            case 6: // small square
                $shape=array(
                    0.33,0.33,
                    0.67,0.33,
                    0.67,0.67,
                    0.33,0.67
                );
                break;
            case 7: // checkerboard
                $shape=array(
                    0,0,
                    0.33,0,
                    0.33,0.33,
                    0.66,0.33,
                    0.67,0,
                    1,0,
                    1,0.33,
                    0.67,0.33,
                    0.67,0.67,
                    1,0.67,
                    1,1,
                    0.67,1,
                    0.67,0.67,
                    0.33,0.67,
                    0.33,1,
                    0,1,
                    0,0.67,
                    0.33,0.67,
                    0.33,0.33,
                    0,0.33
                );
                break;
        }
        /* apply ratios */
        for ($i=0;$i<count($shape);$i++)
            $shape[$i]=$shape[$i]*$this->spriteZ;
        if (count($shape)>0)
            imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
        return $sprite;
    }

}
