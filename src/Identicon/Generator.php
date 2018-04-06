<?php

namespace Identicon;

class Generator
{

    /* generate sprite for corners and sides */
    public function getsprite($shape,$R,$G,$B,$rotation) {
        global $spriteZ;
        $sprite=imagecreatetruecolor($spriteZ,$spriteZ);
        imageantialias($sprite,TRUE);
        $fg=imagecolorallocate($sprite,$R,$G,$B);
        $bg=imagecolorallocate($sprite,255,255,255);
        imagefilledrectangle($sprite,0,0,$spriteZ,$spriteZ,$bg);
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
            $shape[$i]=$shape[$i]*$spriteZ;
        imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
        /* rotate the sprite */
        for ($i=0;$i<$rotation;$i++)
            $sprite=imagerotate($sprite,90,$bg);
        return $sprite;
    }

    /* generate sprite for center block */
    public function getcenter($shape,$fR,$fG,$fB,$bR,$bG,$bB,$usebg) {
        global $spriteZ;
        $sprite=imagecreatetruecolor($spriteZ,$spriteZ);
        imageantialias($sprite,TRUE);
        $fg=imagecolorallocate($sprite,$fR,$fG,$fB);
        /* make sure there's enough contrast before we use background color of side sprite */
        if ($usebg>0 && (abs($fR-$bR)>127 || abs($fG-$bG)>127 || abs($fB-$bB)>127))
            $bg=imagecolorallocate($sprite,$bR,$bG,$bB);
        else
            $bg=imagecolorallocate($sprite,255,255,255);
        imagefilledrectangle($sprite,0,0,$spriteZ,$spriteZ,$bg);
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
            $shape[$i]=$shape[$i]*$spriteZ;
        if (count($shape)>0)
            imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
        return $sprite;
    }

}
