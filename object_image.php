<?php
require "database.php";
session_start();
$imageCreateFunc = [
    'png' => 'imagecreatefrompng',
    'gd' => 'imagecreatefromgd',
    'gif' => 'imagecreatefromgif',
    'jpg' => 'imagecreatefromjpeg',
    'jpeg' => 'imagecreatefromjpeg'
];

$imagetoken = $_GET['token'];
$database = new Database();
$annotatedImage = $database->getAnnotatedImage($imagetoken);

$ext = $annotatedImage['filetype'];
$path = "feed/". $imagetoken . "." . $ext;
$objects = json_decode(json_encode($_SESSION['objects'][$imagetoken]));
$image = call_user_func($imageCreateFunc[$ext], $path);
list($width, $height) = getimagesize($path);

foreach ($objects as $key => $object) {
    $objectColorR = $_SESSION['objects']['colors'][$key][0];
    $objectColorG = $_SESSION['objects']['colors'][$key][1];
    $objectColorB = $_SESSION['objects']['colors'][$key][2];

    $vertices = json_decode($object);
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
