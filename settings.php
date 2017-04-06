<?php
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
    <!-- Reload page if a setting has been done -->
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
        var success=getParam("scheduling");
        if(success!=0){
          setTimeout(function() { //rredirect to lights.php
            window.location.replace("/settings.php").delay(5100);
          }, 3000);
        }
      });
    </script>
  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./lights.php">Home</a></li>
        <li><a href="./lights.php">Lights</a></li>
        <li class="active">Settings</li>
        <li><a href="login/logout.php">Log out</a></li>
      </ol>
      <div class="panel panel-default">
        <div class="panel-body">
          <p class="h4">Scheduled backup</p>
          <?php
            $scheduled = scheduling("check");
            if ($scheduled == "True"){
              echo '<div class="alert alert-success" role="alert">';
              echo "<strong>Success! </strong>Your sockets is already scheduled every 5 min";
              echo '</div>';
              echo '<a href="?scheduling=disable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Disable scheduling</button></a>';
            } elseif ($scheduled == "False") {
              echo '<div class="alert alert-info" role="alert">';
              echo "<strong>Information! </strong>Your sockets is not scheduled. Try to enable scheduling.";
              echo '</div>';
              echo '<a href="?scheduling=enable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Enable scheduling</button></a>';
            }
          ?>
        </div>
        <div class="panel-body">
          <p class="h4">Raspberry pi settings</p>
            <?php
              $configs = include('config.php');
              echo json_encode($configs->ip_info['ip']);
            ?>
        </div>
      </div>
      <?php
        if (isset($_GET['scheduling']) && $_GET['scheduling'] == "enable") {
          scheduling("enable");
        } elseif (isset($_GET['scheduling']) && $_GET['scheduling'] == "disable") {
          $line = scheduling("disable");
          echo $line;
        }
      ?>
    </div>
  </body>
</html>
