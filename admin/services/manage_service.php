<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `service_list` where id = '{$_GET['id']}'");
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
    #cimg{
        object-fit:scale-down;
        object-position:center center;
        height:200px;
        width:200px;
    }
</style>
<div class="container-fluid">
    <form action="" id="service-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">Service</label>
            <input type="text" name="name" id="name" class="form-control form-control-border" placeholder="Enter Service" value ="<?php echo isset($name) ? $name : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="category_ids" class="control-label">For Category <small><em>(Pet Types)</em></small></label>
            <select name="category_ids[]" id="category_ids" class="form-control form-control-border select2" multiple required>
                <?php 
                $categories = $conn->query("SELECT * FROM category_list where delete_flag = 0 ".(isset($category_ids) && !empty($category_ids) ? " or id in ({$category_ids})" : "")." order by name asc");
                while($row = $categories->fetch_assoc()):
                ?>
                <option value="<?= $row['id'] ?>" <?= isset($category_ids) && in_array($row['id'],explode(',', $category_ids)) ? "selected" : "" ?> <?= $row['delete_flag'] == 1 ? "disabled" : "" ?>><?= ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <textarea rows="3" name="description" id="description" class="form-control form-control-sm rounded-0 summernote" data-placeholder="Write the service description here." required><?php echo isset($description) ? $description : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="fee" class="control-label">Fee</label>
            <input type="number" step="any" name="fee" id="fee" class="form-control form-control-border text-right" placeholder="Enter Fee" value ="<?php echo isset($fee) ? $fee : 0 ?>" required>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#category_ids').select2({
                placeholder:"Please Select Pet Type(s) here.",
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('.summernote').each(function(){
                var _this = $(this);
                _this.summernote({
                    height:'15vh',
                    placeholder:_this.attr('data-placeholder'),
                })
            })
        })
        $('#uni_modal #service-form').submit(function(e){
            e.preventDefault();
            if ($('#uni_modal #service-form').valid()) {
                var _this = $(this)
                $('.pop-msg').remove()
                var el = $('<div>')
                    el.addClass("pop-msg alert")
                    el.hide()
                start_loader();
                $.ajax({
                    url:_base_url_+"classes/Master.php?f=save_service",
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    dataType: 'json',
                    error:err=>{
                        console.log(err)
                        alert_toast("An error occured",'error');
                        end_loader();
                    },
                    success:function(resp){
                        if(resp.status == 'success'){
                            location.reload();
                        }else if(!!resp.msg){
                            el.addClass("alert-danger")
                            el.text(resp.msg)
                            _this.prepend(el)
                        }else{
                            el.addClass("alert-danger")
                            el.text("An error occurred due to unknown reason.")
                            _this.prepend(el)
                        }
                        el.show('slow')
                        $('html,body,.modal').animate({scrollTop:0},'fast')
                        end_loader();
                    }
                })
            }
        })
    })
</script>