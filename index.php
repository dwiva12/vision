<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Vision</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
  <style>
    body, html {
      height: 100%;
      font-family: "Roboto", sans-serif; font-weight: 200; font-style: normal;
    }
    .bg {
      background-image: url("images/bg.svg");
      background-color: #CCCCCC;
      height: 100%;
      background-position: center;
      background-repeat: repeat; background-attachment: fixed;
      background-size: cover;
    }
    .center {
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .container {
      height: 100%;
      position: relative;
    }
    input[type=file] {
      width: 100%;
      margin: 8px 0;
      box-sizing: border-box;
      border: none;
      background-color: #21a04c;
      color: white;
    }
    .progress {
      display: none;
      margin-bottom: 1rem;
    }

    .alert {
      display: none;
    }
    .centern {
      margin: 0;
      position: absolute;
      top: 45%;
      left: 50%;
      -ms-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
    }

    /* .edit-photo-container {
      height: 400px;
    } */

    img {
      max-width: 100%; /* This rule is very important, please do not ignore this! */
    }

    input[type="file"] {
      display: none;
    }

    .navbar-btn {
      padding-top: 4px;
      padding-left: 4px;
      padding-right: 4px;
      line-height: 1;
      border-radius: 100px;
      float: right;
    }

    .navbar-btn:hover {
      background-color: #0FAD6040;
    }

    a .material-icons{
      font-size: 30px;
      color: #0FAD60;
      text-align: center;
    }

    .check-container {
      display: block;
      position: relative;
      padding-left:  35px;
      margin-bottom: 12px;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .check-container input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      border-radius: 4px;
      background-color: #ccc;
    }

    .check-container:hover input ~ .checkmark {
      background-color: #aaa;
    }

    .check-container input:checked ~ .checkmark {
      background-color: #0FAD60;
    }

    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    .check-container input:checked ~ .checkmark:after {
      display: block;
    }

    .check-container .checkmark:after {
      left: 9px;
      top: 5px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 3px 3px 0;
      -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
      transform: rotate(45deg);
      box-sizing: content-box;
    }

    .success {
      background:#0FAD6033;
      color: #0FAD60;
    }

    .failed {
      background:#dc354533;
      color: #dc3545;
    }

  </style>
</head>
<body class="bg">
  <div class="container">
    <div class="row">
      <div class="col-sm-10 col-md-4 centern" style="background: #EEEEEE; padding: 20px; box-shadow: 0px 5px 10px #0004; border-radius: 8px">
        <div class="panel-heading">
          <a class="navbar-btn" href="home.php">
            <i class="material-icons">home</i>
          </a>
          <h2><a style="text-decoration: none; color:#0FAD60;" href="/">Medical Vision</a></h2>
          <p style="font-style: bold;">For Medical Purpose</p>
        </div>
        <hr>
        <form id="fileUploadForm" method="post" enctype="multipart/form-data">
          <label class="form-control container" style="margin-bottom: 20px;">
            <span id="file-selected">Choose File</span>
            <input type="file" name="image" accept="image/*" onchange="loadFile(event)"/>
          </label>
          <div class="edit-photo-container" style="margin-bottom: 20px;">
            <img id="output" width="100%" max-height="200px" class="center"/>
          </div>
          <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
          </div>
            <span id="upload-report" class="success" style="display: none; text-align:center; border-radius: 4px; margin-bottom:8px;">up-report</span>
          <label class="check-container">don't analyze
            <input id="checkbox" type="checkbox" name="upload_only">
            <span class="checkmark"></span>
          </label>
          <button id="btnSubmit" width="50%" type="submit" style="border-radius: 5px;" class="btn btn-lg btn-outline-success center">Analyse Image</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    var cropper;
    var canvas;
    var imageFile;
    var $progress = $('.progress');
    var $progressBar = $('.progress-bar');
    var $alert = $('.alert');

    function loadFile(event) {
      var output = document.getElementById('output');
      $('#file-selected').html(event.target.files[0].name);
      var url = URL.createObjectURL(event.target.files[0]);
      imageFile = event.target.files[0];
      if (cropper == null) {
        output.src = url;
        initCropper();
      } else {
        cropper.replace(url);
      }
      $('#upload-report').css('display', 'none');
    };

    function initCropper() {
      var image = document.querySelector('#output');
      cropper = new Cropper(image, {
        strict: false,
        rotatable: false,
        scalable: false,
        ready: function () {
          var cropper = this.cropper;
          var containerData = cropper.getContainerData();
          var cropBoxData = cropper.getCropBoxData();
          var aspectRatio = cropBoxData.width / cropBoxData.height;
          var newCropBoxWidth;
        },

        cropmove: function () {
          var cropper = this.cropper;
          var cropBoxData = cropper.getCropBoxData();
          var aspectRatio = cropBoxData.width / cropBoxData.height;
        },
      });
    }

    function cropImage() {
      if (cropper) {
        canvas = cropper.getCroppedCanvas();
      }
    }

    $('#btnSubmit').click(function (event) {
      var uploadOnly = $('#checkbox').prop('checked');
      event.preventDefault();
      var form = $('#fileUploadForm')[0];
      cropImage();
      canvas.toBlob(function (blob) {
        var formData = new FormData(form);
        formData.append('image', blob);
        formData.append('upload_only', uploadOnly);
        $('#btnSubmit').prop('disabled', true);
        $progress.show();

        $.ajax({
          method: 'POST',
          enctype: 'multipart/form-data',
          url: 'upload.php',
          data: formData,
          processData: false,
          contentType: false,

          xhr: function () {
            var xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function (e) {
              var percent = '0';
              var percentage = '0%';

              if (e.lengthComputable) {
                percent = Math.round((e.loaded / e.total) * 100);
                percentage = percent + '%';
                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
              }
            };

            return xhr;
          },

          success: function (data) {
            var uploadReport = $('#upload-report');
            uploadReport.css('display', 'block');
            if (data.success) {
              uploadReport.attr('class', 'success');
              uploadReport.html('Upload Success');
            } else {
              uploadReport.attr('class', 'failed');
              uploadReport.html('Upload Failed');
            }

            $('#btnSubmit').prop('disabled', false);

            if (!uploadOnly) {
              window.location.href = 'check.php?token=' + data.imagetoken;
            }
          },

          error: function () {
            var uploadReport = $('#upload-report');
            uploadReport.css('display', 'block');
            uploadReport.attr('class', 'failed');
            uploadReport.html('Upload Failed');
            $('#btnSubmit').prop('disabled', false);
          },
          complete: function () {
            $progress.hide();
          }
        });
      }, 'image/jpeg', 0.75);
    });
  </script>
</body>
</html>
