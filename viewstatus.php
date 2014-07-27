<?php
session_start();
include_once 'include/user.php';
include_once 'include/claim.php';
include_once 'include/nodues.php';
include_once 'include/faculty.php';
include_once 'include/fa.php';
include_once 'include/student.php';



$claim = new Claim();
$user = new User();
$user_id = $_SESSION['user_id'];
$nodues = new NoDues();
$student = new Student();
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

echo "<div class = 'maincontent'>";
echo "<h1>Claims Against You</h1>";

if(isset($_POST['request_nodues']))
{
	$batch = $student->get_batch($user_id);
	$request = $nodues->request_nodues($user_id, $batch);
	if($request)
	{
		echo "No Dues requested successfully.";
	}
	else
	{
		echo "Could not request No Dues due to some error.";
	}
}

/*if(isset($_POST['generate_nodues']))
{
	$generate = $nodues->generate_nodues($user_id);
}

*/

$viewclaim = $claim->view_claim($user_id);
if($viewclaim)
{
	echo "<div class='tableclass'>";
	echo "<table border='1' ><tr><th>Claim ID</th><th>Claimed By</th><th>Amount</th><th>Details</th></tr>";
	while ($claim_data = mysql_fetch_assoc($viewclaim)){
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

$isrequested = $nodues->is_requested($user_id);
$isapproved = $nodues->check_status($user_id);
$allcleared = $claim->all_cleared($user_id);
if($isrequested and !$isapproved )
{
	echo "Your No Dues has been requested, and pending approval ... ";
}
else if((!$isrequested and !$isapproved) or !$allcleared)
{
	echo "<br><br><form method='POST' action='' name='request'>
	<input type='submit' name='request_nodues' value='Request No Dues'>";
}
else if(!$isrequested and $isapproved and $allcleared)
{
	echo "<br><br><form method='POST' action='generate.php' name='generate'>
	<input type='submit' name='generate_nodues' value='Generate No Dues'>";
}



echo "<br><a href='home.php'>Go back to Home</a>";
echo "</div>";

?>