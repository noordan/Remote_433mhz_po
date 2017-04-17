<?php
  //include '../functions.php';
  $configs = include('config.php');
  if ($_SERVER['REQUEST_URI'] != '/settings.php'){
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  echo '<div class="panel-body">
    <p class="h4">Login settings</p>';
    if ($configs['login_enabled'] == "True"){
      echo '<div class="alert alert-success" role="alert">';
        echo "<strong>Success! </strong>You have enabled the login feature when accessing the gui from a remote network";
      echo '</div>';
      echo '<a href="?login=False"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Disable login feature</button></a>';
    } elseif ($configs['login_enabled'] == "False") {
      echo '<div class="alert alert-danger" role="alert">';
        echo "<strong>Alert! </strong>It's strongly recommend for you to enable the login feature";
      echo '</div>';
      echo '<a href="?login=True"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Enable login feature</button></a>';
    }
  echo '</div>';

  if (isset($_GET['login']) && $_GET['login'] == "True") {
    $configs['login_enabled'] = "True";
    update_config($configs);
  } elseif (isset($_GET['login']) && $_GET['login'] == "False"){
    $configs['login_enabled'] = "False";
    update_config($configs);
  }
?>
