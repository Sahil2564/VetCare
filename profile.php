<?php
// require_once('../../config.php');

require_once 'inc/sess_auth.php';

$id = $_SESSION['Auth']['User']['id'];
if(isset($id)){
    $qry = $conn->query("SELECT * FROM `users` where id = '{$id}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<div class="col-12">
    <div class="row my-5 d-flex align-items-center justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline rounded-1 shadow border border-success">
                <div class="card-header" style="text-align: center;">
                    <h5>Your Profile</h5>
                </div>
                <div class="card-body rounded-0">
                    <dl>
                        <dt class="text-muted">Full Name</dt>
                        <dd class='pl-4 fs-4 fw-bold'><?= isset($fullname) ? $fullname : '' ?></dd>
                        <dt class="text-muted">Phone</dt>
                        <dd class='pl-4 fs-4 fw-bold'><?= isset($phone) ? $phone : '' ?></dd>
                        <dt class="text-muted">Gmail Address</dt>
                        <dd class='pl-4 fs-4 fw-bold'><?= isset($email) ? $email : '' ?></dd>
                        <dt class="text-muted">Address</dt>
                        <dd class='pl-4 fs-4 fw-bold'><?= isset($address) ? $address : '' ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-5 d-flex align-items-center justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline rounded-1 shadow border border-success">
                <div class="card-header" style="text-align: center;">
                    <h5>Your Appointment Lists</h5>
                </div>
                <?php
                if(!empty($id)){
                $result = $conn->query("SELECT * FROM appointment_list a JOIN users u ON a.cus_id = u.id WHERE a.cus_id = $id");

                if(!empty($result->num_rows))
                {
                    foreach($result as $k => $v){?>
                        <div class="card-body rounded-0">
                            <dl>
                                <dt class="text-muted">Owner Name</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= isset($v['fullname']) ? $v['fullname'] : '' ?></dd>
                                <dt class="text-muted">Appointment Code</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= isset($v['code']) ? $v['code'] : '' ?></dd>
                                <dt class="text-muted">Appointment Day</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= isset($v['schedule']) ? $v['schedule'] : '' ?></dd>
                                <dt class="text-muted">Status</dt>
                                <dd class='pl-4 fs-4 fw-bold'>
                                    <?php if(($v['status']) == 0){
                                            echo '<span class=" rounded-pill badge badge-primary">Pending</span>';
                                        }elseif(($v['status']) == 1){
                                            echo '<span class=" rounded-pill badge badge-success">Confirmed</span>';
                                        }else{
                                            echo '<span class=" rounded-pill badge badge-danger">Cancelled</span>';
                                        }  ?>
                                </dd> 
                            </dl>
                        </div>
                        <hr>
                    <?php }
                }
                else
                {
                    ?>
                    <div class="card-body rounded-0">
                        <dl>
                            <dt class="text-muted">No records found</dt>
                        </dl>
                    </div>
                    <?php
                }
            }
            ?>

            </div>
        </div>
    </div>
</div>