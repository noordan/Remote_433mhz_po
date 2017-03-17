<?php session_start();
if(isset($_SESSION['foo'])) {
  session_destroy();
  header('Location: ./login.php');
}

 ?>
