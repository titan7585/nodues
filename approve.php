<?php
session_start();
include_once 'include/user.php';
include_once 'include/claim.php';
include_once 'include/nodues.php';
include_once 'include/fa.php';
include_once 'include/student.php';

$claim = new Claim();
$fa = new FA();
$user = new User();
$nodues = new NoDues();
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

echo "<div class='maincontent'>";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
$approvenodues = $nodues->approve_nodues($_POST['need_approval']);
if ($approvenodues) 
{
// Registration Success
	echo "No Dues approved<br>";
	
	
}
else 
{
// Registration Failed
	echo 'No Dues not approved';
}

}


$batch = $fa->get_batch($user_id);
$degree = $fa->get_degree($user_id);
$dept = $fa->get_dept($user_id);


echo "<form method='POST' action='' name='approve' >";
$flag = 0;
$result = mysql_query("SELECT roll from student where batch = '$batch' and dept = '$dept' and degree = '$degree'");
/*echo "<form method='POST' action='' name='remove' >";*/

	while ($student_data = mysql_fetch_assoc($result)){
		$roll = $student_data['roll'];
		$result2 = mysql_query("SELECT * from no_dues where user_id = '$roll' and requested_bit = 1");
		if(mysql_num_rows($result2) > 0)
		{
			$flag = 1;
			while($nodues_data = mysql_fetch_assoc($result2))
			{
				$nodues_id = $nodues_data['nodues_id'];
				$for = $nodues_data['user_id'];
			
				echo "<input name='need_approval[$for]' type='checkbox' id='checkbox[$for]' value='$for'>$nodues_id. $for<br>$batch<br>
				$degree<br>$dept<br>
				<hr>";
			}
		}
		
	}   
	if($flag == 1)
	{
		echo "<input type='submit' name='approvenodues' value='Approve'></form>";
	}
	else{
		echo "No pending approvals...";
	}

echo "<br><a href='home.php'>Go back to Home</a>";
echo "</div>";
?>