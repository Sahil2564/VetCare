<?php
require_once('../config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';



Class Customer extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	//register user
	function create_user()
	{
		extract($_POST);
		$oid = $id;
		$postdata = array();
		$postdata['fullname'] = $_POST['fullname'];
		$postdata['address'] = $_POST['address'];
		$postdata['phone'] = $_POST['phone'];
		$postdata['email'] = $_POST['email'];
		$password = $_POST['password'];
		$postdata['password'] = password_hash($password, PASSWORD_DEFAULT);
		$postdata['type'] = '3';
		$postdata['user_status'] = 'unverified';
		//generate otp
		$otp = rand(999999, 111111);
		$count_email =  $this->conn->query("SELECT * from users where email = '{$_POST['email']}'".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if ($count_email > 0) {
			return 3;
			exit;
		}
		if(empty($id))
		{
			$qry = $this->conn->query("INSERT INTO users (fullname,address,phone,email,password,type,user_status,otp) values ('{$postdata['fullname']}','{$postdata['address']}','{$postdata['phone']}','{$postdata['email']}','{$postdata['password']}','{$postdata['type']}','{$postdata['user_status']}','$otp')");
			if($qry)
			{
				$mail = new PHPMailer(true);
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'sahilfyp2022@gmail.com';
				$mail->Password = 'ltuboawmayvwsopy';
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				$mail->setFrom('sahilfyp2022@gmail.com');
				$toEmail = $_POST['email'];
				$mail->addAddress($toEmail);
				$mail->isHTML(true);
				$mail->Subject = "One Time Password";
				$mail->Body = "Your OTP verification code is $otp";
				$mail->send();
				if($mail)
				{
					return 1;
					exit;
				}
				else
				{
					$resp['status'] = 'failed';
					$resp['msg'] = "Failed to send mail.";
				}
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['msg'] = "Error Occured";
			}
		}
    }

	//send otp
	function otp_verify()
	{
		extract($_POST);
		$otp = $_POST['otp'];
		$sql =  "SELECT otp FROM users where otp=$otp";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			$update = $this->conn->query("UPDATE `users` set `user_status` = 'verified' where otp = '{$otp}'");
			return 1;
			exit;
		} 
		else {
			$resp['status'] = 'failed';
			$resp['msg'] = "Invalid OTP.";
		}

	}

	//user logout
	public function logout(){
		session_destroy();
    	unset($_SESSION['Auth']['User']['fullname']);
		unset($_SESSION['Auth']['User']['type']);
		redirect('./admin');
	}

}

$Customer = new Customer();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'create_user':
		echo $Customer->create_user();
	break;
	case 'otp_verify':
		echo $Customer->otp_verify();
	break;
	case 'logout':
		echo $Customer->logout();
	break;
	default:
		// echo $sysset->index();
	break;
}

