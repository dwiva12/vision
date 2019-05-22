<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Vision</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
  <style>
    body, html {
      height: 100%;
      font-family: "Roboto", sans-serif; font-weight: 200; font-style: normal;
    }
    .bg {
      background-image: url("images/bg.jpg");
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

    /* .edit-photo-container {
      height: 400px;
    } */

    img {
      max-width: 100%; /* This rule is very important, please do not ignore this! */
    }

    input[type="file"] {
      display: none;
    }
  </style>
</head>
<body class="bg">
  <div class="container">
    <br><br><br>
    <div class="row">
      <div class="col-md-4 offset-md-3" style="margin: 5px 100px 25px 10px; background: #dce5df; padding: 20px; box-shadow: 10px 10px 5px #0004; border-radius: 8px">
        <div class="panel-heading">
          <h2><a href="/">Medical Vision</a></h2>
          <p style="font-style: bold;">For Medical Purpose</p>
        </div>
        <hr>
        <form id="fileUploadForm" method="post" enctype="multipart/form-data">
          <label class="form-control" style="margin-bottom: 20px;">
            <span id="file-selected">Choose File</span>
            <input type="file" name="image" accept="image/*" onchange="loadFile(event)"/>
          </label>
          <div class="edit-photo-container" style="margin-bottom: 20px;">
            <img id="output" width="100%" max-height="200px" class="center"/>
          </div>
          <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
          </div>
          <button id="btnSubmit" width="50%" type="submit" style="border-radius: 0px;" class="btn btn-lg btn-outline-success center">Analyse Image</button>
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
      event.preventDefault();
      var form = $('#fileUploadForm')[0];
      cropImage();
      canvas.toBlob(function (blob) {
        var formData = new FormData(form);
        formData.append('image', blob);
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
            window.location.href = 'check.php?token=' + data.imagetoken;
          },

          error: function () {
            alert('Upload error');
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
