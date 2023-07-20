<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT a.*,c.name AS pet_type, u.`fullname`,u.`address`,u.`email`,u.`phone` FROM `appointment_list` a 
    INNER JOIN category_list c ON a.category_id = c.id 
    JOIN users u ON a.`cus_id`=u.`id`
    where a.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
        
    }else{
    echo "<script>alert('Unknown Appointment Request ID'); location.replace('./?page=appointments');</script>";
    }
}
else{
    echo "<script>alert('Appointment Request ID is required'); location.replace('./?page=appointments');</script>";
}
$service = "";
if(isset($service_ids)){
    $services = $conn->query("SELECT * FROM `service_list` where id in ({$service_ids}) order by `name` asc");

    while($row = $services->fetch_assoc()){
        if(!empty($service)) $service .=", ";
        $service .=$row['name'];
    }
    $service = (empty($service)) ? "N/A" : $service;
}
?>
<style>
    @media screen {
        .show-print{
            display:none;
        }
    }
    img#appointment-banner{
		height: 45vh;
		width: 20vw;
		object-fit: scale-down;
		object-position: center center;
	}
    .table.border-info tr, .table.border-info th, .table.border-info td{
        border-color:var(--dark);
    }
</style>
<div class="content py-3">
    <div class="card card-outline rounded-1 border border-success ">
        <div class="card-header rounded-0">
            <h5 class="card-title">Appointment Request Details</h5>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div id="outprint">
                    <fieldset>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered border-info">
                                    <colgroup>
                                        <col width="30%">
                                        <col width="70%">
                                    </colgroup>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-olive px-2 py-1">Appointment Request Code</th>
                                        <td><?= ($code) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend class="text-muted border-bottom">Owner Information</legend>
                                        <table class="table table-stripped table-bordered" data-placeholder='true' id="">
                                            <colgroup>
                                                <col width="70%">
                                                <col width="30%">
                                            </colgroup>
                                            <tbody>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Name</th>
                                                    <td class="py-1 px-2 text-right"><?= ucwords($fullname) ?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Email</th>
                                                    <td class="py-1 px-2 text-right"><?= ($email) ?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Address</th>
                                                    <td class="py-1 px-2 text-right"><?= ($address) ?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Phone</th>
                                                    <td class="py-1 px-2 text-right"><?= ($phone) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend class="text-muted border-bottom">Pet Information</legend>
                                        <table class="table table-stripped table-bordered" data-placeholder='true'>
                                            <colgroup>
                                                <col width="70%">
                                                <col width="30%">
                                            </colgroup>
                                            <tbody>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Pet Type</th>
                                                    <td class="py-1 px-2 text-right"><?= ($pet_type) ?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Breed</th>
                                                    <td class="py-1 px-2 text-right"><?= ($breed) ? ($breed) : 'N/A'?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Age</th>
                                                    <td class="py-1 px-2 text-right"><?= ($age) ?></td>
                                                </tr>
                                                <tr class="border-info">
                                                    <th class="py-1 px-2 text-light bg-gradient-info">Service(s) Needed</th>
                                                    <td class="py-1 px-2 text-right"><?= ($service) ? ($service) : 'N/A' ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <small class="text-muted px-2">Status</small><br>
                                    <?php 
									switch ($status){
										case 0:
											echo '<span class="ml-4 rounded-pill badge badge-primary">Pending</span>';
											break;
										case 1:
											echo '<span class="ml-4 rounded-pill badge badge-success">Confirmed</span>';
											break;
										case 2:
											echo '<span class="ml-4 rounded-pill badge badge-danger">Cancelled</span>';
											break;
									}
								?>
                                </div>
                            </div>
                    </fieldset>
                </div>
                
                <hr>
                <div class="rounded-0 text-center mt-3">
                        <a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" id="update_status"><i class="fa fa-edit"></i> Update Status</a>
                        <button class="btn btn-sm btn-danger btn-flat" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
                        <a class="btn btn-light border btn-flat btn-sm" href="./?page=appointments" ><i class="fa fa-angle-left"></i> Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#delete_data').click(function(){
			_conf("Are you sure to delete <b><?= $code ?>\'s</b> from appointment permanently?","delete_appointment",['<?= $id ?>'])
		})
        $('#update_status').click(function(){
            uni_modal("Update Status","appointments/update_status.php?id=<?= $id ?>&status=<?= $status ?>")
        })
    })
    function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
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
					location.replace('./?page=appointments');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>