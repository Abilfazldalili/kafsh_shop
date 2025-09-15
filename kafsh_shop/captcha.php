<?php
if (!session_id()) session_start();
$code = rand(10000, 99999);
$_SESSION['captcha_code'] = $code;
$im = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($im, 255, 255, 255);
$tc = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 120, 40, $bg);
imagestring($im, 5, 30, 12, $code, $tc);
header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);
?>