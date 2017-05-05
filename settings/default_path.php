<?php
  // no access to settings modules directly
  if (!preg_match('/\/settings.php.*/', $_SERVER['REQUEST_URI'])) {
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  $configs = include('config.php');
  echo '<form method="post" action="">
    <div class="panel-body">
      <p class="h4">Path to web root</p>';
      echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">Path</span>';
          echo '<input type="text" value="' . $configs['default_path'] . '" class="form-control" name="path" aria-describedby="basic-addon3">';
      echo '</div>';
      echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;margin-top:20px;" type="submit" name="update_path">Update your path</button>';
  echo '</div>
    </form>';

  if (isset($_POST['update_path'])) {
    // Auto refresh is not implemented
    $configs['default_path'] = $_POST['path'];
    update_config($configs);
  }
?>
