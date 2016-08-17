<?php   //authenticate.php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/17
 * Time: 23:38
 */
include_once "../originls/login.php";
$db_server = mysqli_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("unable to conncet to MYSQL".mysqli_error($db_server));
mysqli_select_db($db_server ,$db_database) or die("unable to select to database".mysqli_error($db_server));

if (isset($_SERVER['PHP_AUTH_USER'])&&isset($_SERVER['PHP_AUTH_PW']))
{
    $un_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_USER']);
    $pw_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_PW']);

    $query = "SELECT * FROM users WHERE username = $un_temp";
    $result = mysqli_query($db_server, $query);
    if (!$result) die("database access failed".mysqli_error($db_server));
    elseif (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_row($result);
        $salt = "abc";
        $salt2 = "def";
        $token = md5("$salt$pw_temp$salt2");
        if ($token == $row[3])
        {
            session_start();
            $_SESSION['username'] = $un_temp;
            $_SESSION['password'] = $pw_temp;
            echo "$row[0]";
        }
        else die("invavid username/password combination");
    }
    else die("invavid username/password combination");

}
else
{
    header('www-Authenticate:Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthenticated');
    die("Please enter your username and password");
}

function mysql_entities_fix_string($string)
{
    return htmlentities(mysqli_fix_string($string));
}

function mysqli_fix_string($string)
{
    if (get_magic_quotes_gpc())
        $string = stripcslashes($string);
        return mysqli_real_escape_string($string);
}

