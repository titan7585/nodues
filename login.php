<?php
session_start();
include_once 'include/user.php';
$user = new User();
if ($user->get_session())
{
header("location:home.php");
}
$msg = "";

function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}

$user_idErr = $passwordErr = "";
$user_id = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{ 
	if (empty($_POST['user_id']))
     {$user_idErr = "User ID is required";}
   else
     {
     $user_id = test_input($_POST['user_id']);
     }
	 if (empty($_POST['password']))
     {$passwordErr = "Password is required";}
   else
     {
     $password = test_input($_POST['password']);
     }
	$login = $user->check_login($user_id, $password, $_POST['category']);
if ($login) 
{
// Login Success
header("location:login.php");
} 
else 
{
// Login Failed
$msg = 'Wrong userid / password / category';

}
}
?>
<?php include 'html/header.php'; ?>
<!--HTML Code -->
<form method="POST" action="" name="login" class="maincontent" >
<h1>LOGIN</h1>
Username
<input type="text" name="user_id" value="<?php echo $user_id;?>"/>
<span class="error">*<br> <?php echo $user_idErr;?></span><br><br>
Password
<input type="password" name="password"/>
<span class="error">*<br> <?php echo $passwordErr;?></span><br><br>
<select name="category">
<option value="student">Student</option>
<option value="faculty">Faculty</option>
<option value="mess">Mess In Charge</option>
</select><br><br>
<input type="submit" value="Login"/><br><br>
<?php echo $msg; ?>
</form>

<?php include 'footer.php'; ?>