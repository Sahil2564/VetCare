<?php 
require_once 'dashboard/sess_auth.php';

$id = $_SESSION['Auth']['User']['id'];
$user = $conn->query("SELECT * FROM users where id ='".$id."'");

foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
</script>
<?php endif;?>
<div class="card card-outline border border-success">
	<div class="card-body">
		<div class="container-fluid">
		<div class="card-header text-center"><b>Your Profile</b></div>
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo $id ?>">
				<div class="form-group">
					<label for="name">Full Name</label>
					<input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo isset($meta['fullname']) ? $meta['fullname']: '' ?>" required>
				</div>
				<div class="form-group">
					<label for="phone">Phone Number</label>
					<input type="tel" name="phone" id="phone" class="form-control" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>" pattern="^\d{10}$" required>
					<small class="text-info"><i>Phone number must be of 10 digits.</i></small>
				</div>
				<div class="form-group">
					<label for="email">Gmail Address</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required  autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
					<small><i>Leave this blank if you dont want to change the password.</i></small>
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<input type="address" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address']: '' ?>" required  autocomplete="off">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Update</button>
					<a class="btn btn-sm btn-secondary" href="./">Cancel</a>
				</div>
			</div>
		</div>
</div>

<script>

	$('#manage-user').submit(function(e){
		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					location.reload()
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader()
				}
			}
		})
	})

</script>