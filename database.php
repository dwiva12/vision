<?php

class Database {
  private $dbhost = '';
  private $dbuser = '';
  private $dbpass = '';
  private $dbname = '';
  private $conn = null;

  public function __construct() {
    $this->dbhost = 'localhost:3306';
    $this->dbuser = 'root';
    $this->dbpass = '';
    $this->dbname = 'vision';
    $this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
  }

  public function getAnnotatedImagesList($page) {
    if (!$this->conn) {
        return 'null';
    }
    $imagesPerPage = 12;

    $sql = "SELECT COUNT(*) AS count FROM annotated_images";
    // print_r(mysqli_query($this->conn, $sql)->fetch_all(MYSQLI_ASSOC));
    $count = mysqli_query($this->conn, $sql)->fetch_all(MYSQLI_ASSOC)[0]["count"];

    $pageCount = ceil($count / $imagesPerPage);
    $offset = ($page - 1) * $imagesPerPage;

    $sql = "SELECT * FROM annotated_images ORDER BY `id` DESC LIMIT " . $imagesPerPage . " OFFSET " . $offset;
    $result = mysqli_query($this->conn, $sql);
    return [$pageCount, $result->fetch_all(MYSQLI_ASSOC)];
  }

  public function close() {
    mysqli_close($conn);
  }

  public function addAnnotatedImage($token) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "INSERT INTO `annotated_images`(`id`, `token`, `created_at`) VALUES (null, '" . $token ."', NOW())";
    return mysqli_query($this->conn, $sql);
    // $result = mysqli_query($this->conn, $sql);
  }
}
