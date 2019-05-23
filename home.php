<?php
  require('database.php');
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

  // if (mysqli_num_rows($result) > 0) {
  //     while ($row = mysqli_fetch_assoc($result)) {
  //         echo "Token: " . $row["token"]. "<br>";
  //     }
  // } else {
  //     echo "0 results";
  // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <title>Medical Vision</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <style>
    body, html {
      height: 100%;
      font-family: "Roboto", sans-serif; font-weight: 200; font-style: normal;"
    }
    .bg {
      background-color: #CCCCCC;
      /* background-image: url("images/bg.jpg"); */
      height: 100%;
      background-position: center;
      background-repeat: repeat; background-attachment: fixed;
      background-size: cover;
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
    a {
      text-decoration: none !important;
    }

    .navbar {
      padding: 0px;
    }

    .navbar .container {
      padding: 20px;
      background-color: #0FAD60;
      box-shadow: 0 1px 15px 0 rgba(0, 0, 0, 0.45)
    }

    .pagination li {
      color: white;
      float: left;
      margin: 4px;
      transition: background-color .3s;
      border-radius: 20px;
    }

    .pagination li.active {
      background-color: dodgerblue;
      color: white;
    }

    .pagination li.active a {
      color: white;
    }

    .pagination li.disabled a {
      color: #aaa;
    }

    .pagination li:hover:not(.active) {
      background-color: #ddd;
    }
  </style>
</head>
<body class="bg">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <h2><a style="color: white;" href="/vision">Medical Vision</a></h2>
        <h4 style="color: white; font-style:normal;">Image Catalog</h4>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="panel-heading center">

    </div>
        <div class="row">
        <?php foreach ($annotatedImages as $key => $image): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <a href="/vision/check.php?token=<?php echo $image['token'];?>">
          <div style="background-color:white; margin-bottom: 40px; border-radius: 5px; padding-bottom:10px; box-shadow: 0px 5px 10px #0002;">
            <img alt="picture" src="<?php echo "feed/" . $image['token'] . ".thumb.jpg";?>" style="width:100%; border-top-left-radius:5px;  border-top-right-radius: 5px;" />
            <h4 class="text-center" style="margin-top:20px; margin-bottom:10px; color:black;"><?php echo $image['token'];?></h4>
          </div>
          </a>
        </div>
        <?php endforeach ?>
      </div>
        <nav aria-label="page navigation">
          <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
              <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1?>">&laquo</a></li>
            <?php else: ?>
              <li class="page-item disabled"><a class="page-link">&laquo</a></li>
            <?php endif ?>
            <?php for ($i = 1; $i <= $pageCount; $i++) {
              if ($page == $i) {
                echo "<li class='page-item active'><a class='page-link' href='?page=". $i ."'>" . $i . "</a></li>";
              } else {
                echo "<li class='page-item'><a class='page-link' href=\"?page=". $i ."\">" . $i . "</a></li>";
              }
            }?>
            <?php if ($page < $pageCount): ?>
              <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1?>">&raquo</a></li>
            <?php else: ?>
              <li class="page-item disabled"><a class="page-link">&raquo</a></li>
            <?php endif ?>
          </ul>
        </nav>
        <!-- </div> -->
    </div>
<footer>
</footer>
</body>
</html>
