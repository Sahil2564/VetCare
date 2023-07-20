
<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users u JOIN doctor_details d ON u.`id`=d.`doc_id` WHERE u.id = '{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
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
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
				
				<div class="form-group col-6">
					<label for="name">Full Name</label>
					<input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo isset($meta['fullname']) ? $meta['fullname']: '' ?>" required>
				</div>
				<div class="form-group col-6">
					<label for="phone">Phone Number</label>
					<input type="tel" name="phone" id="phone" class="form-control" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>" pattern="^\d{10}$" required>
					<small class="text-info"><i>Phone number must be of 10 digits.</i></small>
				</div>
				<div class="form-group col-6">
					<label for="email">Gmail Address</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required  autocomplete="off">
				</div>
				<div class="form-group col-6">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" <?php echo isset($meta['id']) ? "": 'required' ?>>
                    <?php if(isset($_GET['id'])): ?>
					<small class="text-info"><i>Leave this blank if you dont want to change the password.</i></small>
                    <?php endif; ?>
				</div>
				<div class="form-group col-6">
					<label for="address">Address</label>
					<input type="text" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address']: '' ?>" required  autocomplete="off">
				</div>
				<div class="form-group col-6">
					<label for="type">User Type</label>
					<select name="type" id="type" class="custom-select" onchange="setForm(this.value)" required>
						<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Administrator</option>
						<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Doctor</option>
					</select>
				</div>
				<div class="col" id='doc_details'>
					<div class="form-group col-6">
						<label for="profession">Profession</label>
						<input type="text" name="profession" id="profession" class="form-control" value="<?php echo isset($meta['profession']) ? $meta['profession']: '' ?>">
					</div>
					<div class="form-group col-6">
						<label for="Speciality">Sub-Speciality</label>
						<input type="text" name="speciality" id="speciality" class="form-control" value="<?php echo isset($meta['speciality']) ? $meta['speciality']: '' ?>">
					</div>
				</div>
				
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
				<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$("#doc_details").hide();
			// if($(this).value == '2'){
			// 	$("#doc_details").show();
			// }
			// else{
			// 	$("#doc_details").hide();

			// }
			
		

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
						location.href = './?page=user/list';
					}else{
						$('#msg').html('<div class="alert alert-danger">Email already exist</div>')
						$("html, body").animate({ scrollTop: 0 }, "fast");
					}
					end_loader()
				}
			})
		})
	})

	function setForm(value) {
		if(value == '2'){
			$("#doc_details").show();
		}
		else {
			$("#doc_details").hide();
		}
	}

</script>