<?php
ini_set( 'display_errors', 1);
ini_set( 'track_errors',   1);
ini_set( 'html_errors',    1);
error_reporting( E_ALL );
require_once( './examples/functions_utils.php' );
require_once('./bootstrap.php');
$host = 'ipa.lnord.se';
$certificate = __DIR__ . "/certs/ipa.lnord.se_ca.crt";

try {
    $ipa = new \FreeIPA\APIAccess\Main($host, $certificate);
} catch (Exception $e) {
    _print("[instance] Exception. Message: {$e->getMessage()} Code: {$e->getCode()}");
    die();
}




$user = 'admin';
$password = 'dsadasd';
$auth = $ipa->connection()->authenticate($user, $password);
if ($auth) {
	    print 'Logged in';
} else {
	    $auth_info = $ipa->connection()->getAuthenticationInfo();
	    var_dump($auth_info);
}
//Showing the user information
//$r = $ipa->user()->get($user);
//var_dump($r);


?>
