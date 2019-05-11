<?php

session_start();

require "vendor/autoload.php";


use Google\Cloud\Vision\VisionClient;
$vision = new VisionClient(['keyFile' => json_decode(file_get_contents("key.json"), true)]);

$familyPhotoResource = fopen($_FILES['image']['tmp_name'], 'r');

$image = $vision->image($familyPhotoResource,
    [/*'FACE_DETECTION',
     'WEB_DETECTION',*/
     'LABEL_DETECTION',
     'OBJECT_LOCALIZATION'/*,
     'IMAGE_PROPERTIES',
     'SAFE_SEARCH_DETECTION',
     'LANDMARK_DETECTION',
     'LOGO_DETECTION'*/
     /*'TEXT_DETECTION',
     'DOCUMENT_TEXT_DETECTION',
     'CROP_HINTS',
     'PRODUCT_SEARCH'*/
    ]);
$result = $vision->annotate($image);

if ($result) {
    $imagetoken = random_int(1111111, 999999999);
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/feed/' . $imagetoken . ".jpg");
} else {
    header("location: index.php");
    die();
}

$faces = $result->faces();
$object = $result->info();
$logos = $result->logos();
$labels = $result->labels();
$text = $result->text();
$fullText = $result->fullText();
$properties = $result->imageProperties();
$cropHints = $result->cropHints();
$web = $result->web();
$safeSearch = $result->safeSearch();
$landmarks = $result->landmarks();

$temp = [];
$index = 0;
foreach ($labels as $key => $label) {
    $temp[$index] = array(
        'label' => $label->info()['description'],
        'confidence' => number_format($label->info()['score'] * 100 , 2)
    );
    $index++;
}

// header('Content-Type: application/json');
print_r($result);
echo json_encode($temp);
