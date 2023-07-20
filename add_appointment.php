<?php
require_once('./config.php');
$schedule = $_GET['schedule'];
?>
<style>
.timeslot {
  background-color: #a7abaf;
  width: auto;
  height: 25px;
  color: white;
  padding:3px;
  margin-top: 5px;
  font-size: 14px;
  border-radius: 3px;
  /* vertical-align: center; */
  text-align:center;
}

.hover:hover { 
  background-color: #838d8b5e;
  cursor: pointer;
}

.timeslot-active {
    background-color: green !important;
}
</style>

<?php

    $start_time = $_SESSION['system_info']['start_time'];
    $end_time = $_SESSION['system_info']['end_time'];
    $interval = $_SESSION['system_info']['interval'];
 
	$start = new DateTime($start_time);
	$end = new DateTime($end_time);

    $array = get_time_ranges($start,$end,$interval);
    function get_time_ranges($start,$end,$int=60){
        $timeRanges = [];
        $tr_c = 0;
        while($start < $end) {
    
            $timeRanges[$tr_c]['slot_start_time'] = $start->format('H:i');
            $start->modify('+'.$int.' minutes');    
            $timeRanges[$tr_c]['slot_end_time'] = $start->format('H:i');
            $tr_c++;
        }
        return $timeRanges;
    }

?>

<div class="container-fluid">
    <form action="" id="appointment-form">
        <input type="hidden" name="cus_id" value="<?php echo isset($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : '' ?>">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="schedule" value="<?php echo isset($schedule) ? $schedule : '' ?>">
        <dl>
            <dt class="text-muted">Appointment Schedule</dt>
            <dd class=" pl-3"><b><?= date("F d, Y",strtotime($schedule)) ?></b></dd>
        </dl>
        <hr>
        <div class="row">
            <div class="col-md-10">
                <fieldset>
                    <legend class="text-muted">Pet Information</legend>
                    <div class="form-group">
                        <label for="category_id" class="control-label">Pet Type</label>
                        <select name="category_id" id="category_id" class="form-control form-control-border select2" required>
                            <option value="" selected disabled></option>
                            <?php 
                            $categories = $conn->query("SELECT * FROM category_list where delete_flag = 0 ".(isset($category_id) && !empty($category_id) ? " or id = '{$category_id}'" : "")." order by name asc");
                            while($row = $categories->fetch_assoc()):
                            ?>
                            <option value="<?= $row['id'] ?>" <?= isset($category_id) && in_array($row['id'],explode(',', $category_id)) ? "selected" : "" ?> <?= $row['delete_flag'] == 1 ? "disabled" : "" ?>><?= ucwords($row['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="breed" class="control-label">Breed <small class="text-info"><i>If you don't know the breed, leave this field blank.</i></small></label>
                        <input type="text" name="breed" id="breed" class="form-control form-control-border" placeholder="Breed Type" value ="<?php echo isset($breed) ? $breed : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="age" class="control-label">Age</label>
                        <input type="number" name="age" id="age" class="form-control form-control-border" placeholder="1 yr. old" value ="<?php echo isset($age) ? $age : '' ?>" required>
                    </div>
                </fieldset>
                <div class="form-group">
                    <label for="service_id" class="control-label">Service(s)</label>
                    <?php 
                        $services = $conn->query("SELECT * FROM service_list where delete_flag = 0 ".(isset($service_id) && !empty($service_id) ? " or id in ('{$service_id}')" : "")." order by name asc");
                        while($row = $services->fetch_assoc()){
                            unset($row['description']);
                            $service_arr[] = $row;
                        }
                        ?>
                    <select name="service_id[]" id="service_id" class="form-control form-control-border select2" multiple required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctors" class="control-label">Doctor List</label>
                  
                    <select name="doctor_id" id="doctor_id" class="form-control form-control-border" required>
                        <option value="" selected disabled>Please select doctor</option>
                        <?php 
                        $doctor_list = $conn->query("SELECT * FROM users WHERE TYPE='2' ORDER BY fullname ASC");
                        while($row = $doctor_list->fetch_assoc()):
                        ?>
                        <option value="<?= $row['id'] ?>"><?= ucwords($row['fullname']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
              
            </div>
        </div>
        <hr>
        <div class="row">
            <div class ="col">
                <div class="form-group">
                    <label for="time_slot" class="control-label" >Time Slot</label>
                    <input type="hidden" id="timeslot_id" name="timeslot" value="" />
                    <?php 
                        foreach ($array as $key => $value)  {
                            $start_datetime = $schedule." ".$value['slot_start_time'];
                            $end_datetime = $schedule." ".$value['slot_end_time'];
                            $start_datetime_string = strtotime($start_datetime);
                            $end_datetime_string = strtotime($end_datetime);

                            $ts_disabled = false;
                            if (strtotime(date("Y-m-d H:i:s")) < $start_datetime_string || strtotime(date("Y-m-d H:i:s")) < $end_datetime_string) {
                                $ts_disabled = true;
                            }
                            ?>
                                <div class ="col-md-4">
                                <div class="form-group">
                                    <div class="hover timeslot form-control <?= ($ts_disabled)?'':'timeslot_disabled' ?>  " name="slot_time[]"><?= $value["slot_start_time"] ?>-<?= $value["slot_end_time"] ?></div>
                                </div>
                                </div>
                        <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    var service = $.parseJSON('<?= json_encode($service_arr) ?>') || {};
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#category_id').select2({
                placeholder:"Please Select Pet Type here.",
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#service_id').select2({
                placeholder:"Please Select Sevice(s) Here.",
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
        })
        $('#category_id').change(function(){
            var id = $(this).val()
            $('#service_id').html('')
            $('#service_id').select2('destroy')
            Object.keys(service).map(function(k){
                if($.inArray(id,service[k].category_ids.split(',')) > -1 ){

                    var opt = $("<option>")
                        opt.val(service[k].id)
                        opt.text(service[k].name)
                    $('#service_id').append(opt)
                }
            })
            $('#service_id').select2({
                placeholder:"Please Select Sevice(s) Here.",
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#service_id').val('').trigger('change')
        });

        $('#uni_modal #appointment-form').submit(function(e){
            e.preventDefault();
            if($('#timeslot_id').val() == ''){
                    alert_toast("Please select timeslot",'error');
                    return false;
                }
            if ($('#uni_modal #appointment-form').valid()) {
                var _this = $(this)
                    $('.pop-msg').remove()
                    var el = $('<div>')
                        el.addClass("pop-msg alert")
                        el.hide()
                    start_loader();
                $.ajax({
                        url:_base_url_+"classes/Master.php?f=save_appointment",
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
                            end_loader();
                                setTimeout(() => {
                                    uni_modal("Success","success_msg.php?code="+resp.code)
                                    
                                }, 750);
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
    });

    function check_timeslot(){
        $('.timeslot').each(function(){
            if(!($(this).hasClass('timeslot_disabled'))){
                $(this).addClass('time_trigger');
            }
            else
            {
                $(this).removeClass('hover');
                $(this).css("cursor", "not-allowed");
            }
        })
    }
    $(document).ready(function(){
        check_timeslot();

        $('.time_trigger').on('click',function(){
            $('.timeslot').removeClass('timeslot-active');
            $(this).addClass('timeslot-active');
            var result = $(this).text();

            document.getElementById('timeslot_id').value = result;
        });


    })
  
</script>