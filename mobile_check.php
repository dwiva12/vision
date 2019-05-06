<?php

session_start();

require "vendor/autoload.php";

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;

putenv("GOOGLE_APPLICATION_CREDENTIALS=/opt/lampp/htdocs/vision/key1.json");
$imageAnnotator = new ImageAnnotatorClient();

$imageResource = fopen($_FILES['image']['tmp_name'], 'r');

$features = [
    TYPE::OBJECT_LOCALIZATION,
    TYPE::LABEL_DETECTION,
    TYPE::WEB_DETECTION
];

$result = $imageAnnotator->annotateImage($imageResource, $features);

if ($result) {
    $imagetoken = random_int(1111111, 999999999);
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/feed/' . $imagetoken . ".jpg");
} else {
    header("location: index.php");
    die();
}

$objects = $result->getLocalizedObjectAnnotations();
$labels = $result->getLabelAnnotations();
$web = $result->getWebDetection();

$objectsData = [];
foreach ($objects as $key => $object) {
    $normalizedVertices = $object->getBoundingPoly()->getNormalizedVertices();
    $vertices = [
        'left' => number_format($normalizedVertices[0]->getX(), 8),
        'top' => number_format($normalizedVertices[0]->getY(), 8),
        'right' => number_format($normalizedVertices[2]->getX(), 8),
        'bottom' => number_format($normalizedVertices[2]->getY(), 8)
    ];

    $objectsData[$key] = [
        'name' => $object->getName(),
        'score' => number_format($object->getScore() * 100 , 2),
        'vertices' => $vertices
    ];
}

$labelsData = [];
foreach ($labels as $key => $label) {
    $labelsData[$key] = [
        'description' => $label->getDescription(),
        'score' => number_format($label->getScore() * 100 , 2)
    ];
}

$webEntities = [];
foreach ($web->getWebEntities() as $key => $entity) {
    $webEntities[$key] = [
        'description' => $entity->getDescription(),
        'score' => number_format($entity->getScore() * 100 , 2)
    ];
}

$webFullMatchingImages = [];
foreach ($web->getFullMatchingImages() as $key => $image) {
    $webFullMatchingImages[$key] = [
        'url' => $image->getUrl()
    ];
}

$webPartialMatchingImages = [];
foreach ($web->getPartialMatchingImages() as $key => $image) {
    $webPartialMatchingImages[$key] = [
        'url' => $image->getUrl()
    ];
}

$webVisuallySimilarImages = [];
foreach ($web->getVisuallySimilarImages() as $key => $image) {
    $webVisuallySimilarImages[$key] = [
        'url' => $image->getUrl()
    ];
}

$webPagesWithMatchingImages = [];
foreach ($web->getPagesWithMatchingImages() as $key => $page) {
    $webPagesWithMatchingImages[$key] = [
        'title' => $page->getPageTitle(),
        'url' => $page->getUrl()
    ];
}

$webBestGuessLabels = [];
foreach ($web->getBestGuessLabels() as $key => $label) {
    $webBestGuessLabels[$key] = [
        'label' => $label->getLabel(),
        'languageCode' => $label->getLanguageCode()
    ];
}

$webData = [
    'entities' => $webEntities,
    'fullMachingImages' => $webFullMatchingImages,
    'partialMatchingImages' => $webPartialMatchingImages,
    'visuallySimilarImages' => $webVisuallySimilarImages,
    'pages' => $webPagesWithMatchingImages,
    'bestGuessLabels' => $webBestGuessLabels
];

$visionData = [
    'objects' => $objectsData,
    'labels' => $labelsData,
    'web' => $webData
];

header('Content-Type: application/json');
echo json_encode($visionData);
// echo '\n \n';
// echo $result->serializeToJsonString();
