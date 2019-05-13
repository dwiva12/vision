<?php

session_start();
$imageCreateFunc = [
    'png' => 'imagecreatefrompng',
    'gd' => 'imagecreatefromgd',
    'gif' => 'imagecreatefromgif',
    'jpg' => 'imagecreatefromjpeg',
    'jpeg' => 'imagecreatefromjpeg'
];

$imagetoken = $_GET['token'];
$path = $_SESSION['image_path'];
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$objects = json_decode(json_encode($_SESSION['objects'][$imagetoken]));
$image = call_user_func($imageCreateFunc[$ext], $path);
list($width, $height) = getimagesize($path);

foreach ($objects as $key => $object) {
    $objectColorR = $_SESSION['objects']['colors'][$key][0];
    $objectColorG = $_SESSION['objects']['colors'][$key][1];
    $objectColorB = $_SESSION['objects']['colors'][$key][2];

    $vertices = json_decode($object);
        // for ($i=0; $i < 5; $i++) {
        //     for ($j=0; $j < 5; $j++) {
        //         imagesetpixel($image, round($vertices->x), round($vertices->y), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertices->x - random_int(1, 5)) , round($vertices->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertices->x + random_int(1, 5)) , round($vertices->y + random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertices->x + random_int(1, 5)) , round($vertices->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertices->x - random_int(1, 5)) , round($vertices->y + random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //         imagesetpixel($image, round($vertices->x - random_int(1, 5)) , round($vertices->y - random_int(1, 5)), imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
        //     }
        // }
    imagesetthickness($image, round(0.004 * $width));
    imagerectangle($image,
        round($vertices->left * $width),
        round($vertices->top * $height),
        round($vertices->right * $width),
        round($vertices->bottom * $height),
        imagecolorallocate($image, $objectColorR, $objectColorG, $objectColorB));
}

header("Content-Type: image/png");
imagejpeg($image);
