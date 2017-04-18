<?php // TODO: Implement support for turn on/off all lights with one click
      //       Implement some logging solutions
      //       Add sun up/down feature
      ini_set("session.cookie_secure", 1);
      ini_set( 'session.cookie_httponly', 1 );
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
    <title>Remote light control</title>
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
        var success=getParam("code");
        if(success!=0){
          setTimeout(function() { //rredirect to lights.php
            window.location.replace("/").delay(5100);
          }, 1000);
        }
      });
    </script>
  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./lights.php">Home</a></li>
        <li class="active">Lights</li>
        <li><a href="login/logout.php">Log out</a></li>
      </ol>
      <div class="panel panel-default">
        <div class="panel-body">
          <?php
            $signals = array();
            $signals = fetch_csv();
            foreach ($signals as $signal) {
              if ($signal['name'] != "name"){
                echo '<p class="h4">' . $signal['name']. '</p>';
                // dimmable
                if ($signal['dimmable'] == "true") {
                  //echo 'Current status ' . $signal['dimlevel'];
                  $effect_percent = ((int)$signal['dimlevel']/15)*100;
                  // left arrow
                  echo '<div class="col-md-1">
                          <span class="glyphicon glyphicon-chevron-left" style="font-size: 2em;"></span>
                        </div>';
                        // bar in the middle
                        echo '<div class="col-md-10">
                          <div class="progress" style="height:2em;">
                            <div class="progress-bar" role="progressbar" aria-valuenow="' . $effect_percent . '" aria-valuemin="0" aria-valuemax="100" style="font-size: 2em;width: ' . $effect_percent . '%;">
                              '. $effect_percent .'%
                              </div>
                            </div>
                        </div>';
                        // right arrow
                        echo '<div class="col-md-1">
                          <span class="glyphicon glyphicon-chevron-right" style="font-size: 2em;float:right;"></span>
                        </div>';
                        // tur on/off button
                        echo '<div class="col-md-6"><a href="?code='. $signal['on_code'] . '&name=' .  $signal['name'] . '&status=on"><button class="btn btn-custom btn-lg btn-block btn-on" style="border-color:#B2B2B2; type="submit">Turn on ' . $signal['place'] . '</button></a></div>
                        <div class="col-md-6"><a href="?code='. $signal['off_code'] . '&name=' . $signal['name'] . '&status=off"><button class="btn btn-custom btn-lg btn-block btn-off" style="border-color:#B2B2B2; type="submit">Turn off ' . $signal['place'] . '</button></a></div>';
                // not dimmable
                } elseif ($signal['status'] == "off"){
                  echo '<a href="?code='. $signal['on_code'] . '&name=' .  $signal['name'] . '&status=on"><button class="btn btn-custom btn-lg btn-block btn-on" style="border-color:#B2B2B2; type="submit">Turn on ' . $signal['place'] . '</button></a>';
                } elseif ($signal['status'] == "on"){
                  echo '<a href="?code='. $signal['off_code'] . '&name=' . $signal['name'] . '&status=off"><button class="btn btn-custom btn-lg btn-block btn-off" style="border-color:#B2B2B2; type="submit">Turn off ' . $signal['place'] . '</button></a>';
                }
              }
            }
            // Run codes for remote sockets
            if (isset($_GET['code'])) {
              exec('python3 backend/send_code.py ' . $_GET['code']);

              // Call function for update status
              status($_GET['name'], $_GET['status']);
            }
          ?>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <a href="./edit.php"><button class="btn btn-custom btn-lg btn-block btn-off" style="border-color:#B2B2B2;" type="submit"> Add or edit sockets </button></a>
          <a href="./settings.php"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit"> Settings </button></a>
        </div>
      </div>
    </div>
  </body>
</html>
