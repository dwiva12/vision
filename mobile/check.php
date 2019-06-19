<?php
chdir('../');
session_start();

require "vendor/autoload.php";
require "database.php";

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageResponse;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\TextAnnotation\DetectedBreak\BreakType;

$imagetoken = $_GET['token'];
$database = new Database();
$annotatedImage = $database->getAnnotatedImage($imagetoken);

$ext = $annotatedImage['filetype'];
if (!file_exists('feed/' . $imagetoken . '.' . $ext)) {
  header("location: index.php");
  die();
}

if (!file_exists('feed/' . $imagetoken . '.json')) {
  include "reannotate_core.php" ;
}

$json = file_get_contents('feed/' . $imagetoken . '.json');
$result = new AnnotateImageResponse();
$result->mergeFromJsonString($json);

$objects = $result->getLocalizedObjectAnnotations();
$labels = $result->getLabelAnnotations();
$web = $result->getWebDetection();
$textAnnotation = $result->getFullTextAnnotation();

$objectsData = [];
if ($objects != null) {
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
}

$labelsData = [];
if ($labels != null) {
    foreach ($labels as $key => $label) {
        $labelsData[$key] = [
            'description' => $label->getDescription(),
            'score' => number_format($label->getScore() * 100 , 2)
        ];
    }
}

$webData = null;
if ($web != null) {
    $webData = [];
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
        'webEntities' => $webEntities,
        'fullMatchingImages' => $webFullMatchingImages,
        // 'fullMatchingImages' => [],
        'partialMatchingImages' => $webPartialMatchingImages,
        // 'partialMatchingImages' => [],
        'visuallySimilarImages' => $webVisuallySimilarImages,
        // 'visuallySimilarImages' => [],
        // 'pages' => $webPagesWithMatchingImages,
        'pages' => [],
        'bestGuessLabels' => $webBestGuessLabels
    ];
}

$textData = null;
if ($textAnnotation != null) {
    $textData = [];
    $textBlocks = [];
    foreach ($textAnnotation->getPages() as $key => $page) {
        $width = $page->getWidth();
        $height = $page->getheight();

        foreach ($page->getBlocks() as $key => $block) {
            $blockVertices = $block->getBoundingBox()->getVertices();
            $vertices = [
                'left' => number_format($blockVertices[0]->getX() / $width, 8),
                'top' => number_format($blockVertices[0]->getY() / $height, 8),
                'right' => number_format($blockVertices[2]->getX() / $width, 8),
                'bottom' => number_format($blockVertices[2]->getY() / $height, 8)
            ];

            $textBlock = '';
            foreach ($block->getParagraphs() as $paragraf) {
                for ($i = 0; $i < sizeof($paragraf->getWords()); $i++) {
                    $word = $paragraf->getWords()[$i];
                    foreach ($word->getSymbols() as $symbol) {
                        $textBlock .= $symbol->getText();
                        if ($symbol->getProperty()->getDetectedBreak() != null) {
                            switch ($symbol->getProperty()->getDetectedBreak()->getType()) {
                                case BREAKTYPE::EOL_SURE_SPACE:
                                    $textBlock .= "\n";
                                    break;
                                case BREAKTYPE::SPACE:
                                case BREAKTYPE::SURE_SPACE:
                                    $textBlock .= ' ';
                                    break;
                            }
                        }
                    }
                }
            }
            array_push(
                $textBlocks,
                [
                    'text' => $textBlock,
                    'vertices' => $vertices
                ]
            );
        }
    }

    $textData = [
        'text' => $textAnnotation->getText(),
        'width' => $textAnnotation->getPages()[0]->getWidth(),
        'height' => $textAnnotation->getPages()[0]->getheight(),
        'blocks' => $textBlocks
    ];
}

$visionData = [
    'objects' => $objectsData,
    'labels' => $labelsData,
    'web' => $webData,
    'textAnnotation' => $textData
];

header('Content-Type: application/json');
echo json_encode($visionData);
// echo '\n \n';
// echo $result->serializeToJsonString();
