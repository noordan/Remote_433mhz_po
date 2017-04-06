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
  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./lights.php">Home</a></li>
        <li class="active">Settings</li>
        <li><a href="login/logout.php">Log out</a></li>
      </ol>
      <div class="panel panel-default">
        <div class="panel-body">
          <p class="h4">Scheduled backup</p>
          <?php
            $scheduled = scheduling("check");
            if ($scheduled == "True"){
              echo '<div class="alert alert-success" role="success">';
              echo "<b><Success! </b>Your sockets is already scheduled every 5 min";
              echo '</div>';
              echo '<a href="?scheduling=disable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Disable scheduling</button></a>';
            } elseif ($scheduled == "False") {
              echo '<div class="alert alert-info" role="alert">';
              echo "<b>Information! </b>Your sockets is not scheduled. Try to enable scheduling.";
              echo '</div>';
              echo '<a href="?scheduling=enable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Enable scheduling</button></a>';
            }


            if (isset($_GET['scheduling']) && $_GET['scheduling'] == "enable") {
              scheduling("enable");
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
