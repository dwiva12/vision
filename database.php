<?php

class Database {
  private $dbhost = '';
  private $dbuser = '';
  private $dbpass = '';
  private $dbname = '';
  private $conn = null;

  public function __construct() {
    $config = include('config.php');
    $this->dbhost = $config->db['host'] . ':' . $config->db['port'];
    $this->dbuser = $config->db['username'];
    $this->dbpass = $config->db['password'];
    $this->dbname = $config->db['database'];
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

  public function getAnnotatedImage($token) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "SELECT * FROM annotated_images WHERE token = " . $token;
    $result = mysqli_query($this->conn, $sql);
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public function close() {
    mysqli_close($conn);
  }

  public function addImage($token, $ext) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "INSERT INTO `annotated_images`(`id`, `token`, `filetype`, `is_checked`, `created_at`) VALUES (null, '" . $token ."', '" . $ext ."', false, NOW())";
    return mysqli_query($this->conn, $sql);
    // $result = mysqli_query($this->conn, $sql);
  }

  public function addAnnotatedImage($token, $ext) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "INSERT INTO `annotated_images`(`id`, `token`, `filetype`, `is_checked`, `created_at`) VALUES (null, '" . $token ."', '" . $ext ."', true, NOW())";
    return mysqli_query($this->conn, $sql);
    // $result = mysqli_query($this->conn, $sql);
  }

  public function addUnannotatedImage($token, $ext) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "INSERT INTO `annotated_images`(`id`, `token`, `filetype`, `is_checked`, `created_at`) VALUES (null, '" . $token ."', '" . $ext ."', false, NOW())";
    return mysqli_query($this->conn, $sql);
    // $result = mysqli_query($this->conn, $sql);
  }

  public function updateAnnotatedImage($token) {
    if (!$this->conn) {
        return 'null';
    }

    $sql = "UPDATE annotated_images SET is_checked = 1, updated_at = now() WHERE token = '" . $token . "';";
    return mysqli_query($this->conn, $sql);
    // $result = mysqli_query($this->conn, $sql);
  }
}
