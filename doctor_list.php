<?php

$qry = $conn->query("SELECT * FROM `users` u JOIN doctor_details d ON u.`id`=d.`doc_id` WHERE TYPE = '2' ");

?>

<div class="col-12">
    <div class="row my-5 d-flex align-items-center justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline rounded-1 shadow border border-success">
                <div class="card-header" style="text-align: center;">
                    <h5>Doctors List</h5>
                </div>
                <?php
                    if ($qry->num_rows > 0) {
                        foreach ($qry as $k => $v) {
                            ?>
                        <div class="card-body rounded-0">
                            <dl>
                                <dt class="text-muted">Full Name</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= $v['fullname'] ?></dd>
                                <dt class="text-muted">Address</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= $v['address'] ?></dd>
                                <dt class="text-muted">Post</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= $v['profession'] ?></dd>
                                <dt class="text-muted">Speciality</dt>
                                <dd class='pl-4 fs-4 fw-bold'><?= $v['speciality'] ?></dd>
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
                ?> 
            </div>
        </div>
    </div>
</div>