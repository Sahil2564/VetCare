<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	//save users or create users from admin panel
	public function save_users(){
		extract($_POST);
		$oid = $id;
		$data = '';
		$doc_data = '';
		$chk = $this->conn->query("SELECT * FROM `users` where email ='{$email}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(in_array($k,array('fullname','phone','email','address','type'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}

		foreach($_POST as $k => $v){
			if(in_array($k,array('profession','speciality'))){
				if(!empty($doc_data)) $doc_data .=" , ";
				$doc_data .= " {$k} = '{$v}' ";
			}
		}

		if(!empty($password)){
			$password = password_hash($password, PASSWORD_DEFAULT);;
			if(!empty($data)) $data .=" , ";
			$data .= "password = '{$password}' ";
		}
		if(!empty($data)) $data .=" , ";
		$data .= "user_status = 'verified'";

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				if($type == '2')
				{
					if(!empty($doc_data)) $doc_data .=" , ";
					$doc_data .= "doc_id = $id";
					$insert_doctor_info = $this->conn->query("INSERT INTO doctor_details set {$doc_data}");
				}
				
				$this->settings->set_flashdata('success','User Details successfully saved.');
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				if($id == $this->settings->userdata('id')){
					foreach($_POST as $k => $v){
						if($k != 'id'){
							if(!empty($data)) $data .=" , ";
							$this->settings->set_userdata($k,$v);
						}
					}
					
				}
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			
		}
		if(isset($resp['msg']))
		$this->settings->set_flashdata('success',$resp['msg']);
		return  $resp['status'];
	}

	//delete users
	public function delete_users(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){

			$this->settings->set_flashdata('success','User Details successfully deleted.');

			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	default:
	break;
}