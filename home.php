<?php
session_start();
include_once 'include/user.php';
include_once 'include/fa.php';
include_once 'include/faculty.php';
include_once 'include/student.php';

$user = new User();
$user_id = $_SESSION['user_id'];
if (!$user->get_session())
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


?>
<?php
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
?>
<!--HTML Code-->
<div class="maincontent">

<h1> Hello <?php $user->get_fullname($user_id); ?></h1>



</div>