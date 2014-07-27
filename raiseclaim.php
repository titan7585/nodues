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
$cat = $_SESSION['category'];
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
// Checking for user logged in or not
/*if ($user->get_session())
{
header("location:home.php");
}*/
function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}

$againstErr = $amountErr = $detailsErr = "";
$against = $amount = $details = "";

$flag = 0;


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
	if (empty($_POST['against']))
     {
		$againstErr = "Against field can't be blank";
		$flag = 1;
	}
	else
	{
		$against = test_input($_POST['against']);
    }
	if (empty($_POST['amount']))
    {
		$amountErr = "Amount field can't be blank";
		$flag = 1;
	}
	else
    {
		$amount = test_input($_POST['amount']);
		if(!ctype_digit($amount))
		{
			$amountErr = "Only digits allowed";
			$flag = 1;
		}
    }
	if($flag == 0)
	{
		$raiseclaim = $claim->raise_claim($user_id, $against, $amount, $details, $cat);
	
	if ($raiseclaim) 
	{
	// Registration Success
		$against = $_POST['against'];
		echo "Claim raised against $against";
	} else 
	{
		// Registration Failed
		echo 'Error. Failed to raise a claim';
	}
}

}


?>
<!--HTML Code-->
<form method="POST" action="" name='addclaim'  >
<h1>Raise a Claim</h1>
Against 
<input type="text" name="against" />
<span class="error">*<br> <?php echo $againstErr;?></span><br><br>
Amount
<input type="text" name="amount" />
<span class="error">*<br> <?php echo $amountErr;?></span><br><br>
Details
<textarea maxlength="200" name="details">
Enter claim details...
</textarea><br><br>

<input type="submit" value="Raise Claim"/><br><br>
</form>
<a href='home.php'>Go back to Home</a>
<?php echo "</div>"; ?>