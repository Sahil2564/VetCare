<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `users` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted">Full Name</dt>
        <dd class='pl-4 fs-4 fw-bold'><?= isset($fullname) ? $fullname : '' ?></dd>
        <dt class="text-muted">Phone</dt>
        <dd class='pl-4 fs-4 fw-bold'><?= isset($phone) ? $phone : '' ?></dd>
        <dt class="text-muted">Email Address</dt>
        <dd class='pl-4 fs-4 fw-bold'><?= isset($email) ? $email : '' ?></dd>
        <dt class="text-muted">Address</dt>
        <dd class='pl-4 fs-4 fw-bold'><?= isset($address) ? $address : '' ?></dd>
        <dt class="text-muted">Status</dt>
        <dd class='pl-4 fs-4 fw-bold'><?= isset($user_status) ? $user_status : '' ?></dd>
    </dl>
    <div class="col-12 text-right">
        <button class="btn btn-flat btn-sm btn-dark" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>