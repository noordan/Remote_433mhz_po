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
    <!-- Reload page if socket has been turned on/off -->
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
          setTimeout(function() { //redirect to lights.php
            window.location.replace("/edit.php").delay(5100);
          }, 3000);
        }
      });
    </script>
  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./index.php">Home</a></li>
        <li><a href="./lights.php">Lights</a></li>
        <li class="active">Edit sockets</li>
        <li><a href="login/logout.php">Log out</a></li>
      </ol>
      <div class="text-center">
        <?php
          // fetch csv file
          $signals = fetch_csv();

          if (!isset($_GET['id'])){
            # Print out the csv file
            foreach ($signals as $key => $signal) {
              if ($signal['name'] != "name") {
                echo '<div class="panel panel-default">
                        <div class="panel-body">';
                echo '<b>Name:</b> ' . $signal['name']. ' ';
                echo '<b>Place:</b> ' . $signal['place']. ' ';
                echo '<b>On time:</b> ' . $signal['on_time']. ' ';
                echo '<b>Off time:</b> ' . $signal['off_time'] .' ';
                echo '<b>Status:</b> ' . $signal['status']. ' <br>';
                echo '<div align="center">
                  <a href="?code='. $signal['on_code'] . '&name=' .  $signal['name'] . '&status=on"><button  class="btn btn-md btn-custom" aria-haspopup="true" aria-expanded="false" style="border-color:#B2B2B2;width:49.5%;margin-top:10px;" type="submit">Turn on ' . $signal['place'] . '</button></a>
                  <a href="?code='. $signal['off_code'] . '&name=' . $signal['name'] . '&status=off"><button  class="btn btn-md btn-custom" aria-haspopup="true" aria-expanded="false" style="border-color:#B2B2B2;width:49.5%;margin-top:10px;" type="submit">Turn off ' . $signal['place'] . '</button></a>
                </div>';
                echo '<a href="?id=' . $signal['name'] . '"><input class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;margin-top:5px;" type="button" value="Edit"></a>';
                echo '</div>
                </div>';
              }
            }
            echo '<div class="panel panel-default">
                    <div class="panel-body">';
                echo '<a href="?id=add"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Add sockets </button></a>';
            echo '</div>
            </div>';
          } elseif ($_GET['id'] == "add") {
            echo '<div class="panel panel-default text-center">
                    <div class="panel-body">';
              echo '<form method="post" action="">
                <div class="form-inline ">';
                echo '<input type="hidden" value="True" name="add" />';
                echo '<b>Name: </b><input type="text" class="form-control" name="name" Placeholder="Group1"> ';
                echo '<b>On code: </b><input type="text" class="form-control" name="on_code" Placeholder="1381717"> ';
                echo '<b>Off code: </b><input type="text" class="form-control" name="off_code" Placeholder="1381716">
                    </div>
                  <div class="form-inline" style="margin-top:10px;">';
                echo '<b>Place: </b><input type="text" class="form-control" name="place" Placeholder="Kitchen"> ';
                echo '<b>On time: </b><input type="text" class="form-control" name="on_time" Placeholder="10:05"> ';
                echo '<b>Off time: </b><input type="text" class="form-control" name="off_time" Placeholder="20:35"> ';
              echo '</div>
                  <input class="btn btn-custom btn-lg btn-block" type="submit" formaction="functions.php" value="Submit" style="margin-top:10px;">
                </form>';
            echo '</div>
            </div>';

          } elseif (isset($_GET['id'])) {
            // Edit the csv file
            foreach ($signals as $key => $signal) {
              if ($signal['name'] != "name") {
                if ($signal['name'] == $_GET['id']) {
                  // Form for simple access to change the values
                  echo '<div class="panel panel-default text-center">
                          <div class="panel-body">';
                    echo '<form method="post" action="">
                      <div class="form-inline ">';
                      echo '<input type="hidden" value="True" name="edit" />';
                      echo '<b>Name: </b><input type="text" class="form-control" name="name" value="' . $signal['name']. '"> ';
                      echo '<b>On code: </b><input type="text" class="form-control" name="on_code" value="' . $signal['on_code']. '"> ';
                      echo '<b>Off code: </b><input type="text" class="form-control" name="off_code" value="' . $signal['off_code']. '">
                          </div>
                        <div class="form-inline" style="margin-top:10px;">';
                      echo '<b>Place: </b><input type="text" class="form-control" name="place" value="' . $signal['place']. '"> ';
                      echo '<b>On time: </b><input type="text" class="form-control" name="on_time" value="' . $signal['on_time']. '"> ';
                      echo '<b>Off time: </b><input type="text" class="form-control" name="off_time" value="' . $signal['off_time']. '"> ';
                    echo '</div>
                        <input class="btn btn-custom btn-lg btn-block" type="submit" formaction="functions.php" value="Submit" style="margin-top:10px;">
                      </form>';
                  echo '</div>
                  </div>';
                }
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
  </body>
</html>
