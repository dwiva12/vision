<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Medical Vision</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
    </style>
</head>
<body class="bg">
    <div class="container">
        <br><br><br>
        <div class="row">
            <div class="col-md-6 offset-md-3" style="margin: 5px 100px 25px 10px; background: #dce5df; padding: 20px; box-shadow: 10px 10px 5px #0004; border-radius: 8px">
                <div class="panel-heading">
                    <h2><a href="/">Medical Vision</a></h2>
                    <p style="font-style: bold;">For Medical Purpose</p>
                </div>
                <hr>
                <form action="check.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*" class="form-control" onchange="loadFile(event)">
                    <br>
                    <img id="output" width="300" height="300" class="center"/>
                    <br>
                    <script>
                        var loadFile = function(event) {
                        var output = document.getElementById('output');
                        output.src = URL.createObjectURL(event.target.files[0]);
                        };
                    </script>
                    <br>
                    <button type="submit" style="border-radius: 0px;" class="btn btn-lg btn-block btn-outline-success">Analyse Image</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
