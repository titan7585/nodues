<?php

include_once 'config.php';
include_once 'faculty.php';
class FA extends Faculty
{
//Database connect 
public function __construct() 
{
	$db = new DB_Class();
}


public function get_batch($user_id) 
{
	
	$result = mysql_query("SELECT batch_year from fa WHERE fa_id = '$user_id'");
	if($result === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}

	while($batch = mysql_fetch_array($result))
	{
		return $batch['batch_year'];
	}

}

public function is_fa($user_id)
{
	$result = mysql_query("SELECT * from fa where fa_id = '$user_id'");
	if($result === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}

	if(mysql_num_rows($result) > 0)
	{
		return TRUE;
	}
	return FALSE;
}

public function get_degree($user_id) 
{
	
	$result = mysql_query("SELECT batch_degree from fa WHERE fa_id = '$user_id'");
	if($result === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}

	while($batch = mysql_fetch_array($result))
	{
		return $batch['batch_degree'];
	}

}

public function get_dept($user_id) 
{
	
	$result = mysql_query("SELECT batch_dept from fa WHERE fa_id = '$user_id'");
	if($result === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}

	while($batch = mysql_fetch_array($result))
	{
		return $batch['batch_dept'];
	}

}
	



}
?>