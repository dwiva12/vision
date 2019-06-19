<?php
require "database.php";
session_start();
$imagetoken = $_GET['token'];
include "reannotate_core.php" ;

// header('Content-Type: application/json');
if ($success) {
    // $response = [
    //   'success' => true,
    //   'imagetoken' => $imagetoken
    // ];
    // echo json_encode($response);
    header("location: check.php?token=" . $imagetoken);
} else {
  // $response = [
  //   'success' => false,
  //   'imagetoken' => $imagetoken
  // ];
  // echo json_encode($response);
}
?>
