<?php
  // no access to settings modules directly
  if (!preg_match('/\/settings.php.*/', $_SERVER['REQUEST_URI'])) {
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  echo '<div class="panel-body">
    <p class="h4">Scheduled backup</p>';
    // function for scheduling check
    $scheduled = scheduling("check");

    // scheduling is enabled
    if ($scheduled == "True"){
      echo '<div class="alert alert-success" role="alert">';
      echo "<strong>Success! </strong>Your sockets is already scheduled every 5 min";
      echo '</div>';
      echo '<a href="?scheduling=disable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Disable scheduling</button></a>';
      // scheduling is disabled
    } elseif ($scheduled == "False") {
      echo '<div class="alert alert-info" role="alert">';
      echo "<strong>Information! </strong>Your sockets is not scheduled. Try to enable scheduling.";
      echo '</div>';
      echo '<a href="?scheduling=enable"><button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;" type="submit">Enable scheduling</button></a>';
    }
  echo '</div>';

  // enable or disable scheduling
  if (isset($_GET['scheduling']) && $_GET['scheduling'] == "enable") {
    scheduling("enable");
  } elseif (isset($_GET['scheduling']) && $_GET['scheduling'] == "disable") {
    scheduling("disable");
  }
?>
