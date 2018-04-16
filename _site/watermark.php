<?php
// watermark_wrapper.php

// Path the the requested file
$path = $_GET['image'];

// Load the requested image
$image = imagecreatefromstring(file_get_contents($path));

$w = imagesx($image);
$h = imagesy($image);

// Load the watermark image 
$watermarkImg = ($w>360)?'images/watermark.png':'images/watermark_small.png';
// echo $watermarkImg;
$watermark = imagecreatefrompng($watermarkImg);
$ww = imagesx($watermark);
$wh = imagesy($watermark);

// Merge watermark upon the original image
imagecopy($image, $watermark, ((10)), 0, 0, 0, $ww, $wh);

// Send the image
header('Content-type: image/jpeg');
imagejpeg($image,null,95);
exit();

// End file