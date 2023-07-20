function start_loader() {
    $('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div>')
}

function end_loader() {
    $('#preloader').fadeOut('fast', function() {
        $('#preloader').remove();
    })
}
// function 
window.alert_toast = function($msg = 'TEST', $bg = 'success', $pos = '') {
    var Toast = Swal.mixin({
        toast: true,
        position: $pos || 'top',
        showConfirmButton: false,
        timer: 3500
    });
    Toast.fire({
        icon: $bg,
        title: $msg
    })
}

$(document).ready(function() {
    // Login
    $('#login-frm').submit(function(e) {
        e.preventDefault()
        start_loader()
        if ($('.err_msg').length > 0)
            $('.err_msg').remove()
        $.ajax({
            url: _base_url_ + 'classes/Login.php?f=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err)

            },
            success: function(resp) {
				if(resp == 1){
                    location.href = _base_url_+"./?page=appointment";
                }
                else if(resp == 2)
                {
                    location.href = _base_url_ + "admin/index.php";

                }
                else if(resp == 3)
                {
                    location.href = _base_url_ + "admin/index.php";
                }
                else
                {
                    $('#msg').html('<div class="alert alert-danger">Incorrect email or password</div>');
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
                end_loader();               
               
            }
        })
    })

    // System Info
    $('#system-frm').submit(function(e) {
        e.preventDefault()
            // start_loader()
        if ($('.err_msg').length > 0)
            $('.err_msg').remove()
        $.ajax({
            url: _base_url_ + 'classes/SystemSettings.php?f=update_settings',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    // alert_toast("Data successfully saved",'success')
                    location.reload()
                } else {
                    $('#msg').html('<div class="alert alert-danger err_msg">An Error occured</div>')
                    end_load()
                }
            }
        })
    })

    //time slot
    $("#time_frm").submit(function (event) {
        event.preventDefault();
        if ($('#time_frm').valid()) {
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
    
            var start_secs = start_time.split(':');
            var end_secs = end_time.split(':');

            var start_time_secs = (+start_secs[0]) * 60 * 60 + (+start_secs[1]) * 60;
            var end_time_secs = (+end_secs[0]) * 60 * 60 + (+end_secs[1]) * 60;
    
            if (start_time == '' || end_time == '') {
                alert('Please select time');
            } else if (start_time_secs > end_time_secs) {
                alert('End Time should be greater than Start Time.');
            } else if (start_time == end_time) {
                alert('Start Time and End Time cannot be same.');
            } else {
                $.ajax({
                    url: _base_url_ + 'classes/SystemSettings.php?f=time_slot',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success: function(resp) {
                        if (resp == 1) {
                            debugger
                            location.reload()
                        }else if(resp == 2){
                            debugger
                            $('#msg').html('<div class="alert alert-danger err_msg">Time slot should be between given time</div>')
                            $("html, body").animate({ scrollTop: 0 }, "fast");
                        } else {
                            debugger
                            $('#msg').html('<div class="alert alert-danger err_msg">An Error occured</div>')
                            $("html, body").animate({ scrollTop: 0 }, "fast");
                        }
                        end_loader();

                    }
                })
    
            }
        }
    
    });
})