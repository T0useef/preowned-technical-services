<?php

$s = 128;
$im = imagecreatetruecolor($s, $s);
imagesavealpha($im, true);
$clear = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $clear);

$black = imagecolorallocate($im, 0, 0, 0);
imagesetthickness($im, 7);

// Outer document shape (rounded-ish)
$x1 = 28; $y1 = 20; $x2 = 100; $y2 = 108; $fold = 28;

// Left, bottom, top-left edges
imageline($im, $x1, $y1 + 10, $x1, $y2 - 10, $black);
imageline($im, $x1 + 10, $y2, $x2 - 10, $y2, $black);
imageline($im, $x1 + 10, $y1, $x2 - $fold, $y1, $black);

// Right edge below fold
imageline($im, $x2, $y1 + $fold, $x2, $y2 - 10, $black);

// Corner arcs
imagearc($im, $x1 + 10, $y1 + 10, 20, 20, 180, 270, $black);
imagearc($im, $x1 + 10, $y2 - 10, 20, 20, 90, 180, $black);
imagearc($im, $x2 - 10, $y2 - 10, 20, 20, 0, 90, $black);

// Folded corner
imageline($im, $x2 - $fold, $y1, $x2, $y1 + $fold, $black);
imageline($im, $x2 - $fold, $y1, $x2 - $fold, $y1 + $fold, $black);
imageline($im, $x2 - $fold, $y1 + $fold, $x2, $y1 + $fold, $black);

// Text lines
imagesetthickness($im, 6);
imageline($im, 44, 48, 86, 48, $black);
imageline($im, 44, 66, 68, 66, $black);

$path = __DIR__ . '/../public/template/note-icon.png';
imagepng($im, $path);
imagedestroy($im);

echo "Created {$path}\n";
