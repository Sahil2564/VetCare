
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form card border border-success">
            <div class="card-header rounded-0">
                <h5 class="text-center"><b>We've sent a verification code to your Gmail.<br><small>Please enter OTP to verify</small> </b></h5>
            </div>   
            <div class="card-body rounded-0">
            <div id="msg"></div>
                <form id="otp_frm" method="POST" autocomplete="off">
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp" placeholder="Enter OTP" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button btn btn-outline-success" type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
<script>
// Create
$(function(){
  $('#otp_frm').submit(function(e){
    e.preventDefault();
    var _this = $(this);
    // debugger;
    start_loader();
    $.ajax({
      url:_base_url_+"classes/Customer.php?f=otp_verify",
	  data: new FormData($(this)[0]),
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
        success:function(resp){
            if(resp == 1){
            $.confirm({
                title: 'You have entered valid OTP.',
                content: 'You can login now..',
                backgroundDismiss: true,
                buttons: {
                    confirm: {
                        text: 'OK',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            location.href = _base_url_+"admin/login.php";
                        }
                    },
                }
            });
            
            }else{
            	$('#msg').html('<div class="alert alert-danger">Invalid OTP.</div>')
            	$("html, body").animate({ scrollTop: 0 }, "fast");
            }
            end_loader()
        }
    })
  })
});

</script>

</body>
</html>