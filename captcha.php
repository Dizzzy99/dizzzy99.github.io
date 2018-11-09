<?php
session_start();

define("CAP_H", 40);
define("CAP_W", 100);
$num1=rand(1,20);
$num2=rand(1,20);
$rand=rand(1,3);
switch ($rand){
    case 1: {
        $znak="+";
        $sum=$num1+$num2;
    } break;
    case 2: {
        $znak="-";
        if($num1>$num2){
            $sum=$num1-$num2;
        } else {
            $num1+=$num2;
            $sum=$num1-$num2;
        }
    } break;
    case 3: {
        $znak="*";
        $sum=$num1*$num2;
    } break;
    default:{
        $sum=$num1+$num2;
    }; break;
}


$fraza=$num1.$znak.$num2."=";
$_SESSION['sum']=$sum;
$img=imagecreatetruecolor(CAP_W, CAP_H);
$text_color=imagecolorallocate($img, rand(0,80),rand(0,80),rand(0,80));
$bg_color=imagecolorallocate($img, rand(130,255),rand(130,255),rand(130,255));
$gr_color=imagecolorallocate($img, rand(90,120),rand(90,120),rand(90,120));
imagefilledrectangle($img, 0,0,CAP_W,CAP_H, $bg_color);
for($i=0;$i<5;$i++) {
    imageline($img, 0, rand() % CAP_H, CAP_W, rand() % CAP_H, $gr_color);
}
for($i=0;$i<50;$i++) {
    imagesetpixel($img, rand() % CAP_W, rand() % CAP_H, $gr_color);
}
imagettftext($img, 25,1,5, CAP_H-5,$text_color,'12434.ttf', $fraza);
header('content-type: image/png');
imagepng($img);
imagedestroy($img);

?>