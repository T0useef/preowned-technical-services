<?php

$s = 64;
$im = imagecreatetruecolor($s, $s);
imagesavealpha($im, true);
$clear = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $clear);

$black = imagecolorallocate($im, 17, 17, 17);
$white = imagecolorallocate($im, 255, 255, 255);

imagefilledellipse($im, 32, 32, 64, 64, $black);
imagefilledellipse($im, 32, 22, 12, 12, $white);
imagefilledellipse($im, 20, 26, 9, 9, $white);
imagefilledellipse($im, 44, 26, 9, 9, $white);
imagefilledellipse($im, 32, 40, 22, 16, $white);
imagefilledellipse($im, 18, 42, 14, 12, $white);
imagefilledellipse($im, 46, 42, 14, 12, $white);
imagefilledrectangle($im, 0, 48, 64, 64, $black);

$path = __DIR__ . '/../public/template/party-users-icon.png';
imagepng($im, $path);
imagedestroy($im);

echo "Created {$path}\n";
