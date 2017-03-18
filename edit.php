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
    <title>Remote ligth control</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="./custom.css">
  </head>
  <body>
    <div class="container">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./index.php">Home</a></li>
        <li><a href="./lights.php">Lights</a></li>
        <li class="active">Edit</li>
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
                echo '<b>Off time:</b> ' . $signal['off_time']. ' <br>';
                echo '<a href="?id=' . $signal['name'] . '"><input class="btn btn-custom btn-lg btn-block btn-edit" style="border-color:#B2B2B2;" type="button" value="Edit"></a>';
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
        ?>
      </div>
    </div>
  </body>
</html>
