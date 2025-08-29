<?php
header("Content-type: image/png");
$im = imagecreatetruecolor(100, 100);
$text_color = imagecolorallocate($im, 255, 255, 255);
imagestring($im, 5, 10, 10, "GD OK", $text_color);
imagepng($im);
imagedestroy($im);
?>