<?php
chdir('../');
  require "database.php";

  if (array_key_exists("page", $_GET)) {
    $page = $_GET['page'];
    if (!$page) {
      $page = 1;
    }
  } else {
    $page = 1;
  }

  $database = new Database();
  list($pageCount, $annotatedImages) = $database->getAnnotatedImagesList($page);

  $response = [
    "pageCount" => $pageCount,
    "annotatedImages" => $annotatedImages
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
