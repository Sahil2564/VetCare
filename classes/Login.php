<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}

	//login
	public function login(){
		extract($_POST);
		$email = $_POST['email'];
		$password = $_POST['password'];
		$qry = $this->conn->query("SELECT * from users where email = '{$email}'");
		while($row = $qry->fetch_assoc()) {
			if(($qry->num_rows > 0) && password_verify($_POST['password'],$row['password']) )
			{
				$status = $row['user_status'];
				$_SESSION['Auth']['User']['type'] = $row['type'];
				if($_SESSION['Auth']['User']['type']==3 && $status=='verified')
				{
					$_SESSION['Auth']['User']['fullname'] = $row['fullname'];
					$_SESSION['Auth']['User']['type']==3;
					$_SESSION['Auth']['User']['id'] = $row['id'];
					return 1;
					exit;
				}
				elseif($_SESSION['Auth']['User']['type']==1 && $status=='verified')
				{
					$_SESSION['Auth']['User']['fullname'] = $row['fullname'];
					$_SESSION['Auth']['User']['type']==1;
					$_SESSION['Auth']['User']['id'] = $row['id'];
					return 2;
					exit;
				}
				elseif($_SESSION['Auth']['User']['type']==2 && $status=='verified')
				{
					$_SESSION['Auth']['User']['fullname'] = $row['fullname'];
					$_SESSION['Auth']['User']['type']==2;
					$_SESSION['Auth']['User']['id'] = $row['id'];
					return 3;
					exit;
				}

			}
			else
			{
				$resp['status'] = 'failed';
				$resp['msg'] = "Invalid email or password.";
			}
		}

	}

	//admin or doctor logout
	public function logout(){
		session_destroy();
    	unset($_SESSION['Auth']['User']['fullname']);
		unset($_SESSION['Auth']['User']['type']);
		redirect('admin/login.php');
	}

}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

