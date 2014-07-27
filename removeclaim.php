<?php
session_start();
include_once 'include/user.php';
include_once 'include/claim.php';
include_once 'include/faculty.php';
include_once 'include/fa.php';
include_once 'include/student.php';

$claim = new Claim();
$user = new User();
$user_id = $_SESSION['user_id'];
if ($user_id == NULL)
{
header("location:login.php");
}
if (isset($_GET['q'])){
if ($_GET['q'] == 'logout') 
{
$user->user_logout();
header("location:login.php");
}
}
$checkErr = "";
// Checking for user logged in or not
/*if ($user->get_session())
{
header("location:home.php");
}*/

if ($_SESSION['category'] == "student")
{
	$student = new Student();
	include 'html/header_stud.php';
	$batch = $student->get_batch($user_id);
	
}
else if ($_SESSION['category'] == "faculty")
{
	$faculty = new Faculty();
	$fa = new FA();
	if($isfa = $fa->is_fa($user_id))
	{
		include 'html/header_fa.php';
	}
	else
	{
		include 'html/header_others.php';
	}
}
else if($_SESSION['category'] == "mess")
{
	include 'html/header_others.php';
}
echo "<div class = 'maincontent'>";
echo "<h1>Remove Claims</h1>";
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if(isset($_POST['need_delete']))
	{
		$removeclaim = $claim->remove_claim($_POST['need_delete']);
		if ($removeclaim) 
		{


			echo "Claim removed";
		} else 
		{

			echo 'Error. Failed to remove the claim';
		}
		
	}
	else
	{
		$checkErr = "Atleast 1 data needs to be selected";
		
	}
}


$result = mysql_query("SELECT * from claim WHERE by_user = '$user_id'");

if (mysql_num_rows($result) > 0)
{
	
	echo "<form method='POST' action='' name='remove' >";
	while ($claim_data = mysql_fetch_assoc($result)){
		$claim_id = $claim_data['claim_id'];
		$against = $claim_data['against_user'];
		$amount = $claim_data['amount'];
		$details = $claim_data['claim_details'];
		echo "<input name='need_delete[$claim_id]' type='checkbox' id='checkbox[$claim_id]' value='$claim_id'>$claim_id. $against<br>$amount<br>
		$details<br>
		<hr>";
    }

	echo "<input type='submit' name='removeclaim' value='Remove'></form>";
	echo $checkErr;
}
else
{
	echo "<br>Nothing to delete ... <br>";
}
echo "<br><a href='home.php'>Go back to Home</a>";
echo "</div>";
?>