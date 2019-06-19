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

$faces = json_decode(json_encode($_SESSION['faces'][$imagetoken]));
// $image = imagecreatefromjpeg("feed/". $imagetoken . ".jpg");
$image = call_user_func($imageCreateFunc[$ext], $path);
list($width, $height) = getimagesize($path);

foreach ($faces as $key => $face) {
    $faceColorR = $_SESSION['faces']['colors'][$key][0];
    $faceColorG = $_SESSION['faces']['colors'][$key][1];
    $faceColorB = $_SESSION['faces']['colors'][$key][2];

    $faceMark = json_decode($face);
    $vertices = $faceMark->vertices;

    imagesetthickness($image, round(0.004 * $width));
    imagerectangle($image,
        round($vertices->left),
        round($vertices->top),
        round($vertices->right),
        round($vertices->bottom),
        imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
}

header("Content-Type: image/png");
imagejpeg($image);
