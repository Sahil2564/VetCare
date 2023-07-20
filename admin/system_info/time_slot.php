<?php
require_once 'dashboard/sess_auth.php';
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
</script>
<?php endif;?>

<div class="col-lg-12">
	<div class="card card-outline rounded-1 shadow border border-success">
		<div class="card-header">
			<h5 class="card-title"><b>Manage Time Slots</b></h5>
		</div>
		<div class="card-body">
			<form action="" id="time_frm">
			<div id="msg" class="form-group"></div>
			<fieldset>
				<legend>Time Slots</legend>
				<small><i>Clinic Schedule - <?php echo $_settings->info('clinic_schedule') ?></i></small>
				<div class="form-group">
					<label for="time_slots" class="control-label">Interval</label>
					<input type="number" class="form-control form-control-sm col-sm-3" name="interval" id="interval" value="<?php echo $_settings->info('interval') ?>">
				</div>
				<div class="form-group">
					<label for="time_slots" class="control-label">Start Time</label>
					<input type="time" class="form-control form-control-sm col-sm-3" name="start_time" id="start_time" value="<?php echo $_settings->info('start_time') ?>">
				</div>
				<div class="form-group">
					<label for="time_slots" class="control-label">End Time </label>
					<input type="time" class="form-control form-control-sm col-sm-3" name="end_time" id="end_time" value="<?php echo $_settings->info('end_time') ?>">
				</div>
			</fieldset>
			
			</form>
		</div>
		<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary" form="time_frm">Update</button>
				</div>
			</div>
		</div>

	</div>
</div>