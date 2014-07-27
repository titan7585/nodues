<?php


include_once 'config.php';
class User
{
//Database connect 
public function __construct() 
{
$db = new DB_Class();
}

// Login process
public function check_login($user_id, $password, $category) 
{
$_SESSION['login'] = FALSE;
$password = ($password);
$result = mysql_query("SELECT user_id, first_name, last_name from user WHERE user_id = '$user_id' and password = '$password' and category = '$category'");
$user_data = mysql_fetch_array($result);
$no_rows = mysql_num_rows($result);
if ($no_rows > 0) 
{
$_SESSION['login'] = true;
$_SESSION['user_id'] = $user_data['user_id'];
$_SESSION['category'] = $category;
$_SESSION['first_name'] = $user_data['first_name'];
$_SESSION['last_name'] = $user_data['last_name'];

return TRUE;
}
else
{
return FALSE;
}
}
// Getting name
public function get_fullname($user_id) 
{
$result = mysql_query("SELECT first_name, last_name FROM user WHERE user_id = '$user_id'");
if($result === FALSE) {
    die(mysql_error()); // TODO: better error handling
}

while($user_data = mysql_fetch_array($result))
{
    echo $user_data['first_name'] ." " . $user_data['last_name'];
}
}
// Getting session 
public function get_session() 
{
if (isset($_SESSION['login'])){
	
return $_SESSION['login'];
}
}



// Logout 
public function user_logout() 
{
$_SESSION['login'] = FALSE;
session_destroy();
}

}
?>