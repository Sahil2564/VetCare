<?php
require_once('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}

	//send or save message from contact form
	function save_message(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `message_list` set {$data} ";
		}else{
			$sql = "UPDATE `message_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$toEmail =$this->conn->query("SELECT email FROM users WHERE TYPE != '3'");
			$toEmail -> fetch_all(MYSQLI_ASSOC);

			foreach ($toEmail as $to_user) {
				$to[] = $to_user['email'];
			}
			$to_recipients = $to;

			$mail = new PHPMailer(true);

			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'sahilfyp2022@gmail.com';
			$mail->Password = 'ltuboawmayvwsopy';

			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
				
			$mail->setFrom('sahilfyp2022@gmail.com',"VetCare");
				
			foreach ($to_recipients as $to_recipient) {
				$to_comp = $to_recipient;
				$mail->addAddress($to_comp);
			}
				
			$mail->isHTML(true);
				
			$mail->Subject = "User Inquiry";
			$mail->Body = "Dear Sir/Ma'am,<br> 
				<p>You have new message from user inquiry. Please check Manage Inquiries after login to the system.</p><br>
				<p>Regards,<br>
				VetCare Administration<br>
				Phone: 9840167003</p>";

			$mail->send();
				
			if($mail)
			{
				$resp['status'] = 'success';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['msg'] = "Failed to send mail.";
			}
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Your message has successfully sent.";
			else
				$resp['msg'] = "Message details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success' && !empty($id))
		$this->settings->set_flashdata('success',$resp['msg']);
		if($resp['status'] =='success' && empty($id))
		$this->settings->set_flashdata('pop_msg',$resp['msg']);
		return json_encode($resp);
	}

	//delete message or inquiry message
	function delete_message(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `message_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Message has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	//save category list
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}'" : ""));
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Category has successfully added.";
				else
					$resp['msg'] = "Category details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}

	//delete category list
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set delete_flag=1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	//save service list
	function save_service(){
		$_POST['category_ids'] = implode(',',$_POST['category_ids']);
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `service_list` set {$data} ";
		}else{
			$sql = "UPDATE `service_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `service_list` where `name` ='{$name}' and category_ids = '{$category_ids}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Service already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Service has successfully added.";
				else
					$resp['msg'] = "Service has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}

	//delete service list
	function delete_service(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `service_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Service has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	//book appointment
	function save_appointment(){

		if(empty($_POST['id'])){
			$prefix="VETCARE-".date("Ym");
			$code = sprintf("%'.04d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `appointment_list` where code = '{$prefix}{$code}' ")->num_rows;
				if($check <= 0){
					$_POST['code'] = $prefix.$code;
					break;
				}else{
					$code = sprintf("%'.04d",ceil($code)+1);
				}
			}
		}
		if(!empty($_POST['service_id'])){
			$_POST['service_ids'] = implode(",", $_POST['service_id']);
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}

		$slot_taken = $this->conn->query("SELECT * FROM `appointment_list` where date(schedule) = '{$schedule}' and `status` in (0,1)")->num_rows;
		if($slot_taken >= $this->settings->info('max_appointment')){
			$resp['status'] = 'failed';
			$resp['msg'] = "Sorry, The Appointment Schedule is already full.";
		}else{
			$time_slots = $this->conn->query("SELECT * FROM `appointment_list` where date(schedule) = '{$schedule}' and time(timeslot) = '{$timeslot}' and `status` in (0,1)")->num_rows;
			$doctor_count = $this->conn->query("SELECT * FROM `appointment_list` where date(schedule) = '{$schedule}' and doctor_id = '{$doctor_id}' and `status` in (0,1)")->num_rows;

			if($time_slots >= $this->settings->info('max_patient'))
			{
				$resp['status'] = 'failed';
				$resp['msg'] = "Sorry, The selected Time Slot is already full.";
			}
			elseif($doctor_count >= '5'){
				$resp['status'] = 'failed';
				$resp['msg'] = "Sorry, The selected doctor is already full.";
			}
			else
			{
				if(empty($id)){
					$sql = "INSERT INTO `appointment_list` set {$data} ";
					if($sql)
					{
						$select_user_email = $this->conn->query("SELECT email FROM users WHERE id = '{$cus_id}'");
						while($row = $select_user_email->fetch_assoc()){
							$email = $row['email'];
						}

						$bcc_users = $this->conn->query("SELECT email FROM users WHERE TYPE = '1' OR id = '{$doctor_id}'");
						$bcc_users -> fetch_all(MYSQLI_ASSOC);
						foreach ($bcc_users as $bcc_user) {
							$bcc[] = $bcc_user['email'];
						}
						$bcc_recipients = $bcc;
						
						$mail = new PHPMailer(true);
						$mail->isSMTP();
						$mail->Host = 'smtp.gmail.com';
						$mail->SMTPAuth = true;
						$mail->Username = 'sahilfyp2022@gmail.com';
						$mail->Password = 'ltuboawmayvwsopy';
						$mail->SMTPSecure = 'ssl';
						$mail->Port = 465;
						$mail->setFrom('sahilfyp2022@gmail.com',"VetCare");
						$toEmail = $email;
						$mail->addAddress($toEmail);
						foreach ($bcc_recipients as $bcc_recipient) {
							$bcc_comp = $bcc_recipient;
							$mail->addBCC($bcc_comp);
						}
						$mail->isHTML(true);
						$mail->Subject = "Appointment Request";
						$mail->Body = "Dear Sir/Ma'am,<br> 
						<p>The request for doctor appointment has been received by our Appointment Section. The management will reach you as soon as they sees your request.</p><br><h4> Your appointment code is '{$_POST['code']}'.<h4><br>
						<p>Regards,<br>
						Appointment Section</p>
						<p>VetCare<br>
						Phone: 9840167003</p>";
						$mail->send();
						if($mail)
						{
							$resp['status'] = 'success';
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
				}else{
					$sql = "UPDATE `appointment_list` set {$data} where id = '{$id}' ";
				}
				$save = $this->conn->query($sql);
				if($save){
					$rid = !empty($id) ? $id : $this->conn->insert_id;
					$resp['id'] = $rid;
					$resp['code'] = $code;
					$resp['status'] = 'success';
					if(empty($id))
						$resp['msg'] = "<p>New Appointment Details has successfully added.</p>.";
					else
						$resp['msg'] = "Appointment Details has been updated successfully.";
				}else{
					$resp['status'] = 'failed';
					$resp['msg'] = "An error occured.";
					$resp['err'] = $this->conn->error."[{$sql}]";
				}
			}	
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}

	//delete appointment
	function delete_appointment(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `appointment_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Appointment Details has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	//appointment status update
	function update_appointment_status(){
		extract($_POST);

		$del = $this->conn->query("UPDATE `appointment_list` set `status` = '{$status}' where id = '{$id}'");

		if($del){
			if($status == 1)
			{
				$a_status = "Confirmed";
			}
			else
			{
				$a_status = "Cancelled";
			}
			
			$select_email = $this->conn->query("SELECT  a.*,u.`email` from `appointment_list` a JOIN users u ON a.`cus_id` = u.`id` WHERE a.id = '{$id}'");

			while($row = $select_email->fetch_assoc())
			{
				$cus_email = $row['email'];
				$code = $row['code'];
				$schedule = $row['schedule'];
				$timeslot = $row['timeslot'];
				$doctor_id = $row['doctor_id'];
			}

			$bcc_users =  $this->conn->query("SELECT email FROM users WHERE TYPE = '1' OR id = '{$doctor_id}'");

			$bcc_users -> fetch_all(MYSQLI_ASSOC);
			foreach ($bcc_users as $bcc_user) {
				$bcc[] = $bcc_user['email'];
			}
			$bcc_recipients = $bcc;

			$mail = new PHPMailer(true);

			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'sahilfyp2022@gmail.com';
			$mail->Password = 'ltuboawmayvwsopy';

			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			
			$mail->setFrom('sahilfyp2022@gmail.com',"VetCare");
			
			$toEmail = $cus_email;
			$mail->addAddress($toEmail);
			foreach ($bcc_recipients as $bcc_recipient) {
				$bcc_comp = $bcc_recipient;
				$mail->addBCC($bcc_comp);
			}
			
			$mail->isHTML(true);
			
			$mail->Subject = "RE: Book Appointment Detail";
			$mailcontent = '';
			if($a_status == "Confirmed")
			{
				$mailcontent = "<p>Your appointment has been '{$a_status}' for '{$schedule}' between '{$timeslot}'. <br>Your appointment code is '{$code}'.<br><br>For more queries please contact on VetCare, 9840167003.</p>";
			}
			elseif($a_status == "Cancelled")
			{
				$mailcontent = "<p>Your appointment has been '{$a_status}'.<br><br>If you have any queries please contact on VetCare, 9840167003.</p>";
			}
		
			$mail->Body = "Dear Sir/Ma'am,<br> 
			{$mailcontent}
			<br>
			<p>Regards,<br>
			VetCare Appointment Section</p>";

			$mail->send();
			
			if($mail)
			{
				$resp['status'] = 'success';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['msg'] = "Failed to send mail.";
			}

			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Appointment Request status has successfully updated.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_appointment':
		echo $Master->save_appointment();
	break;
	case 'delete_appointment':
		echo $Master->delete_appointment();
	break;
	case 'update_appointment_status':
		echo $Master->update_appointment_status();
	break;
	case 'save_message':
		echo $Master->save_message();
	break;
	case 'delete_message':
		echo $Master->delete_message();
	break;
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_service':
		echo $Master->save_service();
	break;
	case 'delete_service':
		echo $Master->delete_service();
	break;
	default:
		// echo $sysset->index();
		break;
}