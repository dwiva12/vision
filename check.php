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
$res = new AnnotateImageResponse();
$res->mergeFromJsonString($json);
$result = $res;


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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>Medical Vision</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        body, html {
            height: 100%;
            font-family: "Roboto", sans-serif; font-weight: 200; font-style: normal;
        }
        .bg {
          background: #CCCCCC;
            /* background-image: url("images/bg.jpg"); */
            height: 100%;
            background-position: center;
            background-repeat: repeat; background-attachment: fixed;
            background-size: cover;
        }
        .container-fluid  {
            margin-bottom: 50px;
        }
        .container  {
          /* margin-bottom: 50px;
          margin-top: 30px; */
          max-width: 1080px;
          background: #EEEEEE;
          padding: 40px 20px 20px 20px;
          /* box-shadow: 0px 10px 10px 5px #0004; */
          /* border-radius: 5px; */
        }
        .navbar {
          padding: 0px;
        }
        .navbar .container {
          padding: 20px;
          background-color: #0FAD60;
          box-shadow: 0 1px 15px 0 rgba(0, 0, 0, 0.45)
        }

        .navbar .container .navbar-btn {
          padding-top: 4px;
          padding-left: 4px;
          padding-right: 4px;
          line-height: 1;
          border-radius: 100px;
        }

        .navbar .container .navbar-btn:hover {
          background-color: #FFF3;
        }

        .navbar .container a .material-icons{
          font-size: 36px;
          color: white;
          text-align: center;
        }

        .nav-item a p {
          margin: 0px;
        }
        .nav-pills .nav-link.active {
          background-color: #0FAD60;
        }
        .nav-pills .nav-link i {
          color: #0FAD60;
        }
        .nav-pills .nav-link.active i {
          color: white;
        }
        .nav-pills .nav-link p {
          color: #0FAD60;
          font-weight: bold;
        }
        .nav-pills .nav-link.active p {
          color: white;
        }
        .w3-border{border:1px solid #888!important}
        .w3-green,.w3-hover-green:hover{color:#fff!important;background-color:#4CAF50!important}
    </style>
</head>
<body class="bg">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <h2><a style="color: white;" href="/vision">Medical Vision</a></h2>
        <h4 style="color: white; font-style:normal;">Image Analyze Result</h4>
      </div>

      <a class="navbar-btn" href="reannotate.php?token=<?php echo $imagetoken?>">
        <i class="material-icons">refresh</i>
      </a>
    </div>
  </nav>
  <div class="container" style="max-width: 1080px; min-height:100%;">
    <div class="row" style="padding: 20px;">
      <div class="col-md-4" style="text-align: center; margin-bottom:20px">
        <div style="background-color: white; border-radius:5px; box-shadow: 0px 5px 10px #0002;">
          <img style="width:100%; border-top-left-radius:5px;  border-top-right-radius: 5px;" src="<?php
          if (sizeof($faces) > 0) {
            echo "face_image.php?token=$imagetoken";
          } else if (sizeof($objects) > 0) {
            echo "object_image.php?token=$imagetoken";
          } else {
            echo "feed/" . $imagetoken . "." . $ext;
          }
          ?>" alt="Analysed Image" id="analysedImage" onclick="changeImage()"/>
          <p style="padding:20px;">Last Updated:
            <?php echo $annotatedImage['updated_at'] != null ? $annotatedImage['updated_at'] : $annotatedImage['created_at']; ?>
          </p>
        </div>
      </div>
      <div class="col-md-8" style="background-color:white; border-radius: 5px; padding:10px; box-shadow: 0px 5px 10px #0002;">
        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
            <?php if (sizeof($faces) > 0): ?>
            <li class="nav-item">
                <a href="#pills-face" role="tab" class="nav-link" id="pills-face-tab" data-toggle="pill" aria-controls="pills-face" aria-selected="true">
                  <i class="material-icons">face</i>
                  <p>Face</p>
                </a>
            </li>
            <?php endif ?>
            <?php if (sizeof($objects) > 0): ?>
            <li class="nav-item">
              <a href="#pills-object" role="tab" class="nav-link" id="pills-object-tab" data-toggle="pill" aria-controls="pills-object" aria-selected="true">
                <i class="material-icons">center_focus_strong</i>
                <p>Object</p>
              </a>
            </li>
            <?php endif ?>
            <?php if (sizeof($labels) > 0): ?>
            <li class="nav-item">
                <a href="#pills-labels" role="tab" class="nav-link" id="pills-labels-tab" data-toggle="pill" aria-controls="pills-labels" aria-selected="true">
                  <i class="material-icons">loyalty</i>
                  <p>Labels</p>
                </a>
            </li>
            <?php endif ?>
            <?php if ($web): ?>
            <li class="nav-item">
                <a href="#pills-web" role="tab" class="nav-link" id="pills-web-tab" data-toggle="pill" aria-controls="pills-web" aria-selected="true">
                  <i class="material-icons">public</i>
                  <p>Web</p>
                </a>
            </li>
            <?php endif ?>
            <?php if ($properties): ?>
            <li class="nav-item">
                <a href="#pills-properties" role="tab" class="nav-link" id="pills-properties-tab" data-toggle="pill" aria-controls="pills-properties" aria-selected="true">
                  <i class="material-icons">palette</i>
                  <p>Properties</p>
                </a>
            </li>
            <?php endif ?>
            <?php if ($safeSearch): ?>
            <li class="nav-item">
                <a href="#pills-safesearch" role="tab" class="nav-link" id="pills-safesearch-tab" data-toggle="pill" aria-controls="pills-safesearch" aria-selected="true">
                  <i class="material-icons">report</i>
                  <p>Safe Search</p>
                </a>
            </li>
            <?php endif ?>
            <?php if (sizeof($landmarks) > 0): ?>
            <li class="nav-item">
                <a href="#pills-landmarks" role="tab" class="nav-link" id="pills-landmarks-tab" data-toggle="pill" aria-controls="pills-landmarks" aria-selected="true">
                  <i class="material-icons">place</i>
                  <p>Landmarks</p>
                </a>
            </li>
            <?php endif ?>
            <?php if (sizeof($logos) > 0): ?>
            <li class="nav-item">
                <a href="#pills-logo" role="tab" class="nav-link" id="pills-logo-tab" data-toggle="pill" aria-controls="pills-logo" aria-selected="true">
                  <i class="material-icons">local_activity</i>
                  <p>Logos</p>
                </a>
            </li>
            <?php endif ?>
        </ul>
        <hr>
        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show" id="pills-face" role="tabpanel" aria-labelledby="pills-face-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/faces.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-object" role="tabpanel" aria-labelledby="pills-object-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/objects.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-labels" role="tabpanel" aria-labelledby="pills-labels-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/labels.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-web" role="tabpanel" aria-labelledby="pills-web-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/web.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-properties" role="tabpanel" aria-labelledby="pills-properties-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/properties.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-safesearch" role="tabpanel" aria-labelledby="pills-safesearch-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/safesearch.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-landmarks" role="tabpanel" aria-labelledby="pills-landmarks-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/landmarks.php" ;?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="pills-logo" role="tabpanel" aria-labelledby="pills-logo-tab">
                <div class="row">
                    <div class="col-12">
                        <?php include "check_tab/logos.php" ;?>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    </div>
    <footer>
        <script language="javascript">
            var tabPill = $('#pills-tab li:first-child a');
            tabPill.attr('class', 'nav-link active');
            $('#pills-tabContent').find(tabPill.attr('href')).attr('class', 'tab-pane fade show active');

            $('#pills-tab').children().each(function() {
                $(this).on('click', function() {
                    var target = $(this).find('.nav-link').attr('href');
                    switch (target) {
                        case '#pills-face':
                            $('#analysedImage').attr('src', '<?php echo "face_image.php?token=$imagetoken";?>');
                            break;
                        case '#pills-object':
                            $('#analysedImage').attr('src', '<?php echo "object_image.php?token=$imagetoken";?>');
                            break;
                        default:
                            $('#analysedImage').attr('src', '<?php echo "feed/" . $imagetoken . "." . $ext;?>');
                            break;
                    }
                });
            });
        </script>
    </footer>
</body>
</html>
