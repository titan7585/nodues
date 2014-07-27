<?php

include_once 'config.php';
class Claim
{
//Database connect 
public function __construct() 
{
	$db = new DB_Class();
}

public function raise_claim($by, $against, $amount, $details, $cat) 
{
	$result = mysql_query("INSERT INTO claim (by_user, against_user, amount, claim_details, by_cat) VALUES ('$by', '$against', $amount,'$details', '$cat')") or die(mysql_error());
	$result2 = mysql_query("SELECT * from no_dues where user_id = '$against'");
	if(mysql_num_rows($result2) > 0)
	{
		$result3 = mysql_query("UPDATE no_dues set approved_bit = 0 where user_id = '$against'");
	}
	return $result;

}


public function remove_claim($claim_id) 
{

	foreach ($claim_id as $id => $value) {
		$sql = "DELETE FROM claim WHERE claim_id = '".$id."'";
		$result = mysql_query($sql);
		

	}
	return $result;
}

public function view_claim($user_id)
{
	$result = mysql_query("SELECT * from claim WHERE against_user = '$user_id'");
	return $result;

}

public function all_cleared($user_id)
{
	
	$result = mysql_query("SELECT * from claim WHERE against_user = '$user_id' and (by_cat = 'faculty' or by_cat = 'mess')");
	if (mysql_num_rows($result) > 0)
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}
}
?>