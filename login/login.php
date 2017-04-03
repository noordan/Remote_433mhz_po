<?php
session_start();
include '../functions.php';
$ip = get_client_ip_env()
if (preg_match('/192\.168\.0\..{1,3}/', $ip) || isset($_SESSION['username'])){
    header("location: ../lights.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../custom.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="container">

      <form class="form-signin" action="login_function.php" id="login" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="username" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="password" type="password" class="form-control" placeholder="Password">

        <button name="submit" class="btn btn-custom btn-lg btn-primary btn-block" type="submit" style="margin-top:10px;">Sign in</button>

        <?php
          if(isset($_SESSION['err'])){
            echo '<div class="alert alert-danger">' . $_SESSION['err'] . '</div>';
          }
        ?>
      </form>
    </div> <!-- /container -->
  </body>
</html>
