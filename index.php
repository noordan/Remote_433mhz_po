<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Ligths control</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="./custom.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
      function getParam(name, url) {
        if (!url) {
          url = window.location.href;
        }
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
        if (!results) {
          return 0;
        }
        return results[1] || 0;
      }

      $(document).ready(function(){
        var success=getParam("id");
        if(success!=0){
          setTimeout(function() { //rredirect to index.php, remove index.php?logged_in=true
            window.location.replace("./index.php").delay(5100);
          }, 3000);
        }
      });
    </script>

  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./index.php">Home</a></li>
        <li class="active">Lights</li>
      </ol>
      <div class="panel panel-default">
        <div class="panel-body">
          <?php
            include './codes.php';
            foreach ($signals as $signal) {
              echo '<p class="h4">' . $signal['name']. '</p>';
              echo '<a href="?id='. $signal['on']. '"><button class="btn btn-custom btn-lg btn-block btn-on" type="submit">Slå på ' . $signal['place'] . '</button></a>';
              echo '<a href="?id='. $signal['off']. '"><button class="btn btn-custom btn-lg btn-block btn-off" type="submit">Slå av ' . $signal['place'] . '</button></a>';
            }
            if (isset($_GET['id'])) {
              exec('python3 send_code.py ' . $_GET['id']);
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
