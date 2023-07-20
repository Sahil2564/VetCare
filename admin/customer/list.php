<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
</script>
<?php endif;?>

<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
    }
</style>
<div class="card card-outline border border-success">
	<div class="card-header">
		<h3 class="card-title"><b>List of Customers</b></h3>
	</div>
	<div class="card-body">
		
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Email</th>
						<th>User Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT *,fullname as name from `users` where type = '3' order by fullname asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo ucwords($row['name']) ?></td>
							<td ><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
							<td ><p class="m-0">
								<?php if($row['type'] == 1){
									echo "Adminstrator";
								}elseif($row['type'] == 2){
									echo "Staff";
								}else{
									echo "Customer";
								} ?></p></td>
							<td align="center">
								<button type="button" class="btn btn-outline-success btn-sm view_customer" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</button>
				                   
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.view_customer').click(function(){
			uni_modal("Customer Details","customer/view_customer.php?id="+$(this).attr('data-id'),'mid-large')
		})
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
		
	})
	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>