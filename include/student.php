<?php

include_once 'config.php';
include_once 'include/user.php';
class Student extends User
{
//Database connect 
public function __construct() 
{
	$db = new DB_Class();
}


public function get_batch($user_id) 
{
	
	$result = mysql_query("SELECT batch from student WHERE roll = '$user_id'");
	if($result === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}

	while($batch = mysql_fetch_array($result))
	{
		return $batch['batch'];
	}

}





}
?>