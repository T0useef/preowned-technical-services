<?php

$src = __DIR__ . '/../public/assets/images/pts-logo.png';
$img = imagecreatefrompng($src);

if ($img === false) {
    fwrite(STDERR, "Failed to load source logo.\n");
    exit(1);
}

imagealphablending($img, true);
imagesavealpha($img, true);
imagefilter($img, IMG_FILTER_NEGATE);

$publicDir = __DIR__ . '/../public';

foreach ([16, 32, 180] as $size) {
    $resized = imagescale($img, $size, $size, IMG_BICUBIC);

    if ($resized === false) {
        fwrite(STDERR, "Failed to resize to {$size}x{$size}.\n");
        continue;
    }

    $filename = $size === 32 ? 'favicon.png' : "favicon-{$size}x{$size}.png";
    $out = $publicDir . '/' . $filename;

    imagepng($resized, $out);
    imagedestroy($resized);

    echo "Created {$filename}\n";
}

imagedestroy($img);
