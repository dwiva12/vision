<?php

session_start();

require "vendor/autoload.php";


use Google\Cloud\Vision\V1\ImageAnnotatorClient;
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
} else {
    header("location: index.php");
    die();
}

$objects = $result->getLocalizedObjectAnnotations();
$labels = $result->getLabelAnnotations();
$web = $result->getWebDetection();
$textAnnotation = $result->getFullTextAnnotation();
$faces = $result->getFaceAnnotations();
$landmarks = $result->getLandmarkAnnotations();
$safeSearch = $result->getSafeSearchAnnotation();
$logos = $result->getLogoAnnotations();
$properties = $result->getImagePropertiesAnnotation();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medical Vision</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        body, html {
            height: 100%;
        }
        .bg {
            background-image: url("images/bg.jpg");
            height: 100%;
            /*background-position: center;*/
            background-repeat: repeat; background-attachment: fixed;
            /*background-size: cover;*/
        }
        .container-fluid  {
            margin-bottom: 50px;
        }
    </style>
</head>
<body class="bg">
    <div class="container-fluid" style="max-width: 1080px;">
        <br><br><br>
        <div class="row">
            <div class="col-md-12" style="margin: auto; background: #c9d8d3; padding: 20px; box-shadow: 10px 10px 5px #888; border-radius: 5px;">
                <div class="panel-heading">
                    <h2><a href="/">Medical Vision</a></h2>
                    <p style="font-style: italic;">Image Analyse Result</p>
                </div>
                <hr style="border: 1px solid grey;">
                <div class="row">
                    <div class="col-md-4" style="text-align: center;">
                        <img class="img-thumbnail" src="<?php
                            if (sizeof($faces) > 0) {
                                echo "http://localhost/vision/image.php?token=$imagetoken";
                            } else if (sizeof($objects) > 0) {
                                echo "http://localhost/vision/object_image.php?token=$imagetoken";
                            } else {
                                echo "http://localhost/vision/feed/" . $imagetoken . "." . $ext;
                            }
                        ?>" alt="Analysed Image" id="analysedImage" onclick="changeImage()">

                    </div>
                    <div class="col-md-8" style="padding: 10px; border: 2px solid grey;">
                        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                            <?php if (sizeof($faces) > 0): ?>
                            <li class="nav-item">
                                <a href="#pills-face" role="tab" class="nav-link" id="pills-face-tab" data-toggle="pill" aria-controls="pills-face" aria-selected="true">Face</a>
                            </li>
                            <?php endif ?>
                            <?php if (sizeof($objects) > 0): ?>
                            <li class="nav-item">
                                <a href="#pills-object" role="tab" class="nav-link" id="pills-object-tab" data-toggle="pill" aria-controls="pills-object" aria-selected="true">Object</a>
                            </li>
                            <?php endif ?>
                            <?php if (sizeof($labels) > 0): ?>
                            <li class="nav-item">
                                <a href="#pills-labels" role="tab" class="nav-link" id="pills-labels-tab" data-toggle="pill" aria-controls="pills-labels" aria-selected="true">Labels</a>
                            </li>
                            <?php endif ?>
                            <?php if ($web): ?>
                            <li class="nav-item">
                                <a href="#pills-web" role="tab" class="nav-link" id="pills-web-tab" data-toggle="pill" aria-controls="pills-web" aria-selected="true">Web</a>
                            </li>
                            <?php endif ?>
                            <?php if ($properties): ?>
                            <li class="nav-item">
                                <a href="#pills-properties" role="tab" class="nav-link" id="pills-properties-tab" data-toggle="pill" aria-controls="pills-properties" aria-selected="true">Properties</a>
                            </li>
                            <?php endif ?>
                            <?php if ($safeSearch): ?>
                            <li class="nav-item">
                                <a href="#pills-safesearch" role="tab" class="nav-link" id="pills-safesearch-tab" data-toggle="pill" aria-controls="pills-safesearch" aria-selected="true">Safe Search</a>
                            </li>
                            <?php endif ?>
                            <?php if (sizeof($landmarks) > 0): ?>
                            <li class="nav-item">
                                <a href="#pills-landmarks" role="tab" class="nav-link" id="pills-landmarks-tab" data-toggle="pill" aria-controls="pills-landmarks" aria-selected="true">Landmarks</a>
                            </li>
                            <?php endif ?>
                            <?php if (sizeof($logos) > 0): ?>
                            <li class="nav-item">
                                <a href="#pills-logo" role="tab" class="nav-link" id="pills-logo-tab" data-toggle="pill" aria-controls="pills-logo" aria-selected="true">Logos</a>
                            </li>
                            <?php endif ?>
                        </ul>
                        <hr style="border: 1px solid grey;">
                        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade show" id="pills-face" role="tabpanel" aria-labelledby="pills-face-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "faces.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-object" role="tabpanel" aria-labelledby="pills-object-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "objects.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-labels" role="tabpanel" aria-labelledby="pills-labels-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "labels.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-web" role="tabpanel" aria-labelledby="pills-web-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "web.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-properties" role="tabpanel" aria-labelledby="pills-properties-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "properties.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-safesearch" role="tabpanel" aria-labelledby="pills-safesearch-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "safesearch.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-landmarks" role="tabpanel" aria-labelledby="pills-landmarks-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "landmarks.php" ;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="pills-logo" role="tabpanel" aria-labelledby="pills-logo-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <?php include "logos.php" ;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <script language="javascript">
            // function changeImage() {
            //
            //     if (document.getElementById("analysedImage").src == "<?php //echo "http://localhost/vision/image.php?token=$imagetoken";?>") {
            //         document.getElementById("analysedImage").src = "<?php //echo "http://localhost/vision/object_image.php?token=$imagetoken";?>";
            //     } else {
            //         document.getElementById("analysedImage").src = "<?php //echo "http://localhost/vision/image.php?token=$imagetoken";?>";
            //     }
            // }

            var tabPill = $('#pills-tab li:first-child a');
            tabPill.attr('class', 'nav-link active');
            $('#pills-tabContent').find(tabPill.attr('href')).attr('class', 'tab-pane fade show active');

            $('#pills-tab').children().each(function() {
                $(this).on('click', function() {
                    // $('#analysedImage').attr('src', '<?php echo "http://localhost/vision/object_image.php?token=$imagetoken";?>');
                    var target = $(this).find('.nav-link').attr('href');
                    switch (target) {
                        case '#pills-face':
                            $('#analysedImage').attr('src', '<?php echo "http://localhost/vision/image.php?token=$imagetoken";?>');
                            break;
                        case '#pills-object':
                            $('#analysedImage').attr('src', '<?php echo "http://localhost/vision/object_image.php?token=$imagetoken";?>');
                            break;
                        default:
                            $('#analysedImage').attr('src', '<?php echo "http://localhost/vision/feed/" . $imagetoken . "." . $ext;?>');
                            break;
                    }
                });
            });
        </script>
    </footer>
</body>
</html>
