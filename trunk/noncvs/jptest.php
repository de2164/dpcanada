<?php
// Change this defines to where Your fonts are stored 
DEFINE("TTF_DIR","/usr/share/fonts/truetype/ttf-dejavu/");
// Change this define to a font file that You know that You have
DEFINE("TTF_FONTFILE","DejaVuSansMono.ttf");
// Text to display
DEFINE("TTF_TEXT","Hello World!");

$im = imagecreatetruecolor (400, 100);
$white = imagecolorallocate ($im, 255, 255, 255);
$textcolor = imagecolorallocate ($im, 0, 0, 0);

imagefilledrectangle($im,0,0,399,99,$white);
imagettftext ($im, 12, 0, 10, 40, $textcolor, TTF_DIR.TTF_FONTFILE,TTF_TEXT);

header ("Content-type: image/png");
imagepng ($im);
?>