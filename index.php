<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medical Vision</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css">
    <style>
        body, html {
            height: 100%;
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
                    <input type="file" name="image" accept="image/*" class="form-control" onchange="loadFile(event)">
                    <br>
                    <div>
                        <img id="output" width="100%" class="center"/>
                    </div>
                    <br>
                    <script>

                    </script>
                    <br>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <button id="btnSubmit" type="submit" style="border-radius: 0px;" class="btn btn-lg btn-block btn-outline-success">Analyse Image</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
    <script>
        var cropper;
        var canvas;
        var imageFile;
        var $progress = $('.progress');
        var $progressBar = $('.progress-bar');
        var $alert = $('.alert');

        var loadFile = function(event) {
            var output = document.getElementById('output');
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

              // avatar.src = canvas.toDataURL();
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
                  cache: false,

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
                    // alert('Upload success' + data.imagetoken);
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
