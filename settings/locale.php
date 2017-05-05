<?php
  // no access to settings modules directly
  if (!preg_match('/\/settings.php.*/', $_SERVER['REQUEST_URI'])) {
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  $configs = include('config.php');
  echo '<form method="post" action="">
    <div class="panel-body">
      <p class="h4">Locale settings</p>';
      // UTC
      echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">UTC</span>';
          echo '<input type="text" value="' . $configs['utc'] . '" class="form-control" name="utc" aria-describedby="basic-addon3">';
      echo '</div><br>';
      // latitude
      echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">Latitude</span>';
          echo '<input type="text" value="' . $configs['latitude'] . '" class="form-control" name="lat" aria-describedby="basic-addon3">';
      echo '</div><br>';
      // Longitude
      echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">Longitude</span>';
          echo '<input type="text" value="' . $configs['longitude'] . '" class="form-control" name="long" aria-describedby="basic-addon3">';
      echo '</div>';
      echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;margin-top:20px;" type="submit" name="update_locale">Update your locale settings</button>';
  echo '</div>
    </form>';

  if (isset($_POST['update_locale'])) {
    // Auto refresh is not implemented
    $configs['utc'] = $_POST['utc'];
    $configs['latitude'] = $_POST['lat'];
    $configs['longitude'] = $_POST['long'];
    update_config($configs);
  }
?>
