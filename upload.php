<?php

session_start();

require "vendor/autoload.php";
require "database.php";

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageResponse;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\TextAnnotation\DetectedBreak\BreakType;
use Google\Cloud\Vision\V1\Likelihood;

use claviska\SimpleImage;

putenv("GOOGLE_APPLICATION_CREDENTIALS=" . getcwd() . "/key1.json");
$imageAnnotator = new ImageAnnotatorClient();

$imageResource = fopen($_FILES['image']['tmp_name'], 'r');

$features = [
    TYPE::OBJECT_LOCALIZATION,
    TYPE::LABEL_DETECTION,
    TYPE::WEB_DETECTION,
    TYPE::FACE_DETECTION,
    TYPE::LANDMARK_DETECTION,
    TYPE::LOGO_DETECTION,
    TYPE::IMAGE_PROPERTIES,
    TYPE::SAFE_SEARCH_DETECTION
    // TYPE::TEXT_DETECTION
];

$result = $imageAnnotator->annotateImage($imageResource, $features);

if ($result) {
    $imagetoken = random_int(1111111, 999999999);
    $imageType = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif'
    ];
    $ext = $imageType[exif_imagetype($_FILES['image']['tmp_name'])];
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/feed/' . $imagetoken . "." . $ext);
    $_SESSION['image_path'] = 'feed/' . $imagetoken . "." . $ext;

    $fp = fopen('feed/' . $imagetoken . '.json', 'w');
    fwrite($fp, $result->serializeToJsonString());
    fclose($fp);

    $image = new SimpleImage('feed/' . $imagetoken . "." . $ext);
    $image->thumbnail(200, 200)->toFile('feed/' . $imagetoken . ".thumb." . $ext, null, 75);

    $database = new Database();

    header('Content-Type: application/json');
    if ($database->addAnnotatedImage($imagetoken)) {
      $response = [
        'success' => true,
        'imagetoken' => $imagetoken
      ];
      echo json_encode($response);
    } else {
      $response = [
        'success' => false,
        'imagetoken' => $imagetoken
      ];
      echo json_encode($response);
    }
} else {
    header("location: index.php");
    die();
}
?>
