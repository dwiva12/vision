<?php

session_start();

require "vendor/autoload.php";


use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageResponse;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\TextAnnotation\DetectedBreak\BreakType;
use Google\Cloud\Vision\V1\Likelihood;

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

    // $json = file_get_contents('feed/' . $imagetoken . '.json');
    // // echo $json;
    // $res = new AnnotateImageResponse();
    // $res->mergeFromJsonString($json);
    // foreach ($res->getFaceAnnotations() as $key => $value) {
    //     echo "posible \n";
    // };
    //
    // $result = $res;

    // var_dump($res);
    $response = [
        'imagetoken' => $imagetoken
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header("location: index.php");
    die();
}
?>
