<?php // TODO: Implement support for schedule
      //       Implement support for turn on/off all lights with one click
      //       Implement some logging solutions
      //       Implement a gui for edit codes

      include 'functions.php';
      $ip = get_client_ip_env();
      session_start();
      if (preg_match('/192\.168\.0\..{1,3}/', $ip) || isset($_SESSION['username'])){

      } else {
        require "login/login_header.php";
      }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Remote ligth control</title>
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
          setTimeout(function() { //rredirect to lights.php
            window.location.replace("/").delay(5100);
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
            $signals = array();
            $signals = fetch_csv();
            foreach ($signals as $signal) {
              if ($signal['name'] != "name"){
                echo '<p class="h4">' . $signal['name']. '</p>';
                echo '<a href="?id='. $signal['on_code']. '"><button class="btn btn-custom btn-lg btn-block btn-on" style="border-color:#B2B2B2; type="submit">Turn on ' . $signal['place'] . '</button></a>';
                echo '<a href="?id='. $signal['off_code']. '"><button class="btn btn-custom btn-lg btn-block btn-off" style="border-color:#B2B2B2; type="submit">Turn off ' . $signal['place'] . '</button></a>';
              }
            }
            echo '<a href="./edit.php"><button class="btn btn-custom btn-lg btn-block btn-off" style="border-color:#B2B2B2;" type="submit">Add or edit sockets </button></a>';
            if (isset($_GET['id'])) {
              exec('python3 backend/send_code.py ' . $_GET['id']);
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
