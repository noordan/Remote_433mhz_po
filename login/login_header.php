<?php
  //PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
  session_start();
  $uri = $_SERVER['REQUEST_URI']; // $uri == example.com/sub
  $exploded_uri = explode('/', $uri); //$exploded_uri == array('example.com','sub')
  $domain_name = $exploded_uri[0]; //$domain_name = 'example.com'

  include '../functions.php';
  $ip = get_client_ip_env();
  if (preg_match('/192\.168\.0\..{1,3}/', $ip)){
    header('location: https://' . $_SERVER['HTTP_HOST']);
  } elseif (!isset($_SESSION['username'])) {
      header('location: https://' . $_SERVER['HTTP_HOST'] . '/login/login.php');
  }
