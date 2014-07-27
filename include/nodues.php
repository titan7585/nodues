<?php

include_once 'config.php';
include_once 'include/claim.php';
include_once 'include/student.php';
include_once 'include/fa.php';


$claim = new Claim();
$fa = new FA();
$student = new Student();


class NoDues
{
//Database connect 
public function __construct() 
{
	$db = new DB_Class();
}

public function is_requested($user_id) 
{
	$result = mysql_query("SELECT * from no_dues WHERE user_id = '$user_id' and requested_bit = 1 and approved_bit = 0") or die(mysql_error());
	if (mysql_num_rows($result) == 0)
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}

}

public function request_nodues($user_id, $batch)
{
	$result2 = mysql_query("SELECT * from no_dues where user_id = '$user_id'") or die(mysql_error());
	if (mysql_num_rows($result2) > 0)
	{
		$result3 = mysql_query("UPDATE no_dues set requested_bit = 1 where user_id = '$user_id'") or die(mysql_error());
		return $result3;
	}
	else
	{
		$result = mysql_query("INSERT INTO no_dues (user_id, requested_bit, approved_bit, batch) VALUES ('$user_id', 1, 0, '$batch')") or die(mysql_error());
		return $result;
	}
}



public function approve_nodues($user_id)
{
	$claim = new Claim();
	foreach ($user_id as $id => $value) {
		$iscleared = $claim->all_cleared($id);
		if($iscleared)
		{
			$result = mysql_query("update no_dues set requested_bit = 0, approved_bit = 1 where user_id = '$id'") or die(mysql_error());
			$result2 = mysql_query("SELECT email from user where user_id = '$id'");
			$email = mysql_fetch_assoc($result2);
			$to = $email['email'];
			echo $to;
			$subject = "No Dues Approved";
			$message = "Your No Dues has been approved.";
			$headers = "From: sourav.fb.90@gmail.com\r\nReply-To: sourav.fb.90@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$mail_sent = @mail($to, $subject, $message, $headers);
			return TRUE;
		}
		else
		{
			$result = mysql_query("update no_dues set requested_bit = 0, approved_bit = 0 where user_id = '$id'") or die(mysql_error());
			return FALSE;
		}
		/*$sql = "DELETE FROM claim WHERE claim_id = '".$id."'";
		$result = mysql_query($sql);
		return $result;*/

	}
	
}

public function check_status($user_id)
{
	$result = mysql_query("SELECT * from no_dues where user_id = '$user_id'") or die(mysql_error());
	if (mysql_num_rows($result) > 0)
	{
		while($nodues_data = mysql_fetch_assoc($result))
		{
			if($nodues_data['approved_bit'] == 1)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
}

public function generate_nodues($user_id)
{
	//ob_start();
	require ('include/fpdf.php');
	$user = new User();
	$name = $user->get_fullname($user_id);
	$fpdf = new FPDF();
	$fpdf->SetMargins(0, 0, 0);
	$fpdf->SetAutoPageBreak(true, 0);
	$fpdf->AliasNbPages();
	$fpdf->AddPage();
	$fpdf->SetFillColor(0);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->Cell(90,10,'NO Dues',0,'C',true);
	$fpdf->SetFillColor(255, 255, 255);
	$fpdf->SetTextColor(0);
	$fpdf->Cell(100, 6, "This is to certify that ", 1, 'L', FALSE);
	$fpdf->Cell(90,10, $user_id,0,'C',FALSE);
	$fpdf->Cell(90,10,'with Roll - ',0,'C',FALSE);
	$fpdf->Cell(90,10, $user_id,0,'C',FALSE);
	$fpdf->Cell(100, 6, "has been granted a No Dues certificate. ", 1, 'L', FALSE);
	$fpdf->Cell(100, 6, "This certificate is electronically signed.", 1, 'L', FALSE);
	
	$fpdf->Output('nodues.pdf', 'D');
	/*require ('include/fpdf.php');
	ob_start();
	$pdf = new FPDF( );
	$pdf->AddPage();
	
	$pdf->Cell(0,10,'PHP - The Good Parts!');
	
	$pdf->Output('nodues.pdf', 'D');*/
}
}
?>