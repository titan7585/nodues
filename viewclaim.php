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


$viewclaim = $claim->view_claim($user_id);
echo "<div class='maincontent'>";
echo "<h1>Claims Against You</h1>";
echo "<div class='tableclass'>";
echo "<table border='1'><tr><th>Claim ID</th><th>Claimed By</th><th>Amount</th><th>Details</th></tr>";
if($viewclaim)
{
	while ($claim_data = mysql_fetch_assoc($viewclaim))
	{
		$claim_id = $claim_data['claim_id'];
		$by_user = $claim_data['by_user'];
		$amount = $claim_data['amount'];
		$details = $claim_data['claim_details'];
		
		echo "<tr><td>$claim_id</td><td>$by_user</td><td>$amount</td><td>$details</td></tr>";
    
	}
	echo "</table></div>";
}
else
{
	echo "Congrats. There are no claims against you. ";
}

echo "<br><a href='home.php'>Go back to Home</a>";
echo "</div>";
?>