<?php

session_start();

$imagetoken = $_GET['token'];
$objects = json_decode(json_encode($_SESSION['objects'][$imagetoken]));
$image = imagecreatefromjpeg("feed/". $imagetoken . ".jpg");
list($width, $height) = getimagesize("feed/" . $imagetoken . ".jpg");

foreach ($objects as $key => $object) {
    $objectColorR = $_SESSION['objects']['colors'][$key][0];
    $objectColorG = $_SESSION['objects']['colors'][$key][1];
    $objectColorB = $_SESSION['objects']['colors'][$key][2];

    $vertex = json_decode($object);
        // for ($i=0; $i < 5; $i++) {
        //     for ($j=0; $j < 5; $j++) {
        //         imagesetpixel($image, round($vertex->x), round($vertex->y), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertex->x - random_int(1, 5)) , round($vertex->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertex->x + random_int(1, 5)) , round($vertex->y + random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertex->x + random_int(1, 5)) , round($vertex->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertex->x - random_int(1, 5)) , round($vertex->y + random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertex->x - random_int(1, 5)) , round($vertex->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //     }
        // }
    imagesetthickness($image, round(0.004 * $width));
    imagerectangle($image,
        round($vertex->left * $width),
        round($vertex->top * $height),
        round($vertex->right * $width),
        round($vertex->bottom * $height),
        imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
}

header("Content-Type: image/png");
imagejpeg($image);
