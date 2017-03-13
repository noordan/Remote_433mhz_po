<?php require 'login_header.php';?>
<?php
// TODO: check if user exists in mail db, if not add user to it, fix page to add alias
//INSERT INTO Domains_tbl (DomainName) VALUES ('linuxnewz.com');
//INSERT INTO Users_tbl (DomainId, password, Email) VALUES (1, ENCRYPT('PasswordForFirstEmailAccount', CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), 'tecmint@linuxnewz.com');
//INSERT INTO Users_tbl (DomainId, password, Email) VALUES (1, ENCRYPT('PasswordForSecondEmailAccount', CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), 'linuxsay@linuxnewz.com');
//INSERT INTO Alias_tbl (DomainId, Source, Destination) VALUES (1, 'info@linuxnewz.com', 'tecmint@linuxnewz.com');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('DOMAIN_FQDN', 'lukasnord.se');
define('LDAP_SERVER', '192.168.0.125');

if (isset($_POST['submit']))
{
    $user = strip_tags($_POST['username']) .'@'. DOMAIN_FQDN;
    $pass = stripslashes($_POST['password']);

    $conn = ldap_connect("ldap://". LDAP_SERVER ."/");

    if (!$conn)
        $err = 'Could not connect to LDAP server';

    else
    {
        //define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($conn, $user, $pass);

        ldap_get_option($conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

        if (!empty($extended_error))
        {
            $errno = explode(',', $extended_error);
            $errno = $errno[2];
            $errno = explode(' ', $errno);
            $errno = $errno[2];
            $errno = intval($errno);

            if ($errno == 532)
                $err = 'Unable to login: Password expired';
        }

        elseif ($bind)
        {
          //original base_dn
          //$base_dn = array("CN=Users,DC=". join(',DC=', explode('.', DOMAIN_FQDN)),
          //    "OU=Users,OU=Employees,DC=". join(',DC=', explode('.', DOMAIN_FQDN)));

            $base_dn = array("CN=Users,DC=". join(',DC=', explode('.', DOMAIN_FQDN)),
                "OU=Employees,DC=". join(',DC=', explode('.', DOMAIN_FQDN)));
                //change OU to "OU=EMployees" to log in with EMployees
                //create a function for loop thru all OU

            $result = ldap_search(array($conn,$conn), $base_dn, "(cn=*)");

            if (!count($result))
                $err = 'Unable to login: '. ldap_error($conn);

            else
            {
                foreach ($result as $res)
                {
                    $info = ldap_get_entries($conn, $res);

                    for ($i = 0; $i < $info['count']; $i++)
                    {
                        if (isset($info[$i]['userprincipalname']) AND strtolower($info[$i]['userprincipalname'][0]) == strtolower($user))
                        {
                            session_start();


                            $username = explode('@', $user);
                            $_SESSION['foo'] = 'bar';
                            $_SESSION['username'] = $username[0];
                            $_SESSION['dn_name'] = $info[$i]["distinguishedname"][0];

                            preg_match("/OU=(Administrators)/", $_SESSION['dn_name'], $admin);
                            if ($admin){
                              $_SESSION['admin'] = True;
                            }

                            break;
                        }
                    }
                }
            }
        }
    }

    // session OK, redirect to home page
    if (isset($_SESSION['foo']))
    {
        header('Location: https://lights.tastorp.nu');
        exit();
    }

    elseif (!isset($err)) {
      session_start();
      $err = 'Unable to login: '. ldap_error($conn);
      $_SESSION['err'] = $err;
      header('Location: ./login.php');
    }

    ldap_close($conn);
}
?>
