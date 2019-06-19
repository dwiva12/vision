<?php
require "vendor/autoload.php";
include('config.php');

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageResponse;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\TextAnnotation\DetectedBreak\BreakType;
use Google\Cloud\Vision\V1\Likelihood;

use claviska\SimpleImage;

$imageAnnotator = new ImageAnnotatorClient();
$database = new Database();
$annotatedImage = $database->getAnnotatedImage($imagetoken);

$ext = $annotatedImage['filetype'];

$imageResource = fopen('feed/' . $imagetoken . "." .  $ext, 'r');

$features = [
    TYPE::OBJECT_LOCALIZATION,
    TYPE::LABEL_DETECTION,
    TYPE::WEB_DETECTION,
    TYPE::FACE_DETECTION,
    TYPE::LANDMARK_DETECTION,
    TYPE::LOGO_DETECTION,
    TYPE::IMAGE_PROPERTIES,
    TYPE::SAFE_SEARCH_DETECTION,
    TYPE::TEXT_DETECTION
];

$result = $imageAnnotator->annotateImage($imageResource, $features);

if ($result) {
    $fp = fopen('feed/' . $imagetoken . '.json', 'w');
    fwrite($fp, $result->serializeToJsonString());
    fclose($fp);

    $success = $database->updateAnnotatedImage($imagetoken);
} else {
  $success = false;
}
?>
