<?php
  // no access to settings modules directly
  if (!preg_match('/\/settings.php.*/', $_SERVER['REQUEST_URI'])) {
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  // get config file
  $configs = include('config.php');
  echo '<form method="post">
    <div class="panel-body">
    <p class="h4">Login settings</p>';
    if ($configs['login_enabled'] == "True"){
      echo '<div class="alert alert-success" role="alert">';
        echo "<strong>Success! </strong>You have enabled the login feature when accessing the gui from a remote network";
      echo '</div>';
      echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit" name="login" value="False">Disable login feature</button>';
    } elseif ($configs['login_enabled'] == "False") {
      echo '<div class="alert alert-danger" role="alert">';
        echo "<strong>Alert! </strong>It's strongly recommend for you to enable the login feature";
      echo '</div>';
      echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit" name="login" value="True">Enable login feature</button>';
    }
  echo '</div>
      </form>';
  # ADD A FORM ABOVE
  if (isset($_POST['login'])) {
    $configs['login_enabled'] = $_POST['login'];
    update_config($configs);
  }
?>
