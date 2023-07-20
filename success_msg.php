<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>

<div class="container-fluid">
    <div class="alert alert-success">
        <p>Your Appointment Request has been submitted. The management will reach you as soon as they sees your request. Your appointment code is <b><?= ucwords($_GET['code']) ?></b>. Thank You!</p>
    </div>

    <div class="form-group text-right">
        <button class="btn btn-sm btn-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
    </div>
</div>
<script>
    $(function(){
        $('#uni_modal').on('hide.bs.modal',function(){
            location.reload()
        })
    })
</script>