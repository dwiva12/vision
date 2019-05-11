<?php

session_start();

$imagetoken = $_GET['token'];
$faces = $_SESSION['faces'][$imagetoken];
$image = imagecreatefromjpeg("feed/". $imagetoken . ".jpg");

foreach ($faces as $key => $face) {
    $faceColorR = $_SESSION['faces']['colors'][$key][0];
    $faceColorG = $_SESSION['faces']['colors'][$key][1];
    $faceColorB = $_SESSION['faces']['colors'][$key][2];

    foreach ($face->getBoundingPoly()->getVertices() as $vertex) {
        for ($i=0; $i < 5; $i++) {
            for ($j=0; $j < 5; $j++) {
                imagesetpixel($image, round($vertex->getX()), round($vertex->getY()), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
                imagesetpixel($image, round($vertex->getX() - random_int(1, 5)) , round($vertex->getY() - random_int(1, 5)), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
                imagesetpixel($image, round($vertex->getX() + random_int(1, 5)) , round($vertex->getY() + random_int(1, 5)), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
                imagesetpixel($image, round($vertex->getX() + random_int(1, 5)) , round($vertex->getY() - random_int(1, 5)), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
                imagesetpixel($image, round($vertex->getX() - random_int(1, 5)) , round($vertex->getY() + random_int(1, 5)), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
                imagesetpixel($image, round($vertex->getX() - random_int(1, 5)) , round($vertex->getY() - random_int(1, 5)), imagecolorallocate($image, $faceColorR, $faceColorG, $faceColorB));
            }
        }
    }
}

// header("Content-Type: image/png");
// imagejpeg($image);
