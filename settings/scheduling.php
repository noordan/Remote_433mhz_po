<?php
  //include '../functions.php';
  if ($_SERVER['REQUEST_URI'] != '/settings.php'){
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  echo '<div class="panel-body">
    <p class="h4">Scheduled backup</p>';

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
echo '</div>';

if (isset($_GET['scheduling']) && $_GET['scheduling'] == "enable") {
  scheduling("enable");
} elseif (isset($_GET['scheduling']) && $_GET['scheduling'] == "disable") {
  scheduling("disable");
}
?>
