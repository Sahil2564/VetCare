
  <style>
   
    .register-title{
      text-shadow: 2px 2px black
    }
    #register{
      flex-direction:column !important
    }
    #logo-img{
        height:150px;
        width:150px;
        object-fit:scale-down;
        object-position:center center;
        border-radius:100%;
    }
    #register .col-7,#register .col-5{
      width: 100% !important;
      max-width:unset !important
    }
  </style>
  <?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
</script>
<?php endif;?>
  <div class="h-100 d-flex align-items-center w-100" id="register">
    <div class="col-5 h-100 bg-gradient">
      <div class="d-flex w-100 h-100 justify-content-center align-items-center">
        <div class="card col-sm-12 col-md-6 col-lg-6 card-outline rounded-2 shadow border border-success" style="margin-top: 20px;">
          <div class="card-header rounded-0">
            <h5 class="text-purle text-center"><b>Registration</b></h5>
          </div>
          <div class="card-body rounded-0">
          <div id="msg"></div>
          <form id="register_frm" action="">
          <input type="hidden" name="id">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" class="form-control" name="fullname" id="fullname"  placeholder="Full Name" required autofocus>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" id="address" placeholder="Address" required autofocus>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone Number" pattern="^\d{10}$" required autofocus>
                <small class="text-info"><i>Phone number must be of 10 digits.</i></small>
            </div>
            <div class="form-group">
                <label>Gmail Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Gmail Address" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autofocus>
            </div>
            <button type="submit" class="btn btn-sm btn-success " id="adduser">Sign Up</button>
            <p>Already have an account ? <a href="<?php echo base_url . 'admin/login.php' ?>"><i> Sign In Here</i></a></p>
            <a href="<?php echo base_url ?>">Go to Website</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
// Create
$(function(){
  $('#register_frm').submit(function(e){
    e.preventDefault();
    var _this = $(this)
    start_loader();
    $.ajax({
      url:_base_url_+"classes/Customer.php?f=create_user",
			data: new FormData($(this)[0]),
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
			success:function(resp){
				if(resp == 1){
          location.href = _base_url_+"./?page=user-otp";
				}
        else
        {
					$('#msg').html('<div class="alert alert-danger">Email already exist. Try new one</div>');
					$("html, body").animate({ scrollTop: 0 }, "fast");
				}
        end_loader();
			}
    })
  })
});

</script>