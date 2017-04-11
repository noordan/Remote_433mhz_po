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
          }, 0300);
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
          <?php
            // include settings
            include 'settings/scheduling.php';
            include 'settings/raspberrypi.php';
          ?>
      </div>
      <?php
        if (isset($_GET['scheduling']) && $_GET['scheduling'] == "enable") {
          scheduling("enable");
        } elseif (isset($_GET['scheduling']) && $_GET['scheduling'] == "disable") {
          $line = scheduling("disable");
          echo $line;
        } elseif (isset($_POST['update_ip'])) {
          // Change raspberry i settings in configuration file and save it again
          // Auto refresh is not implemented
          $configs['socket_info']['ip'] = $_POST['ip'];
          $configs['socket_info']['port'] = $_POST['port'];
          file_put_contents('config.php', '<?php return ' . var_export($configs, true) . ';');
        }
      ?>
    </div>
  </body>
</html>
