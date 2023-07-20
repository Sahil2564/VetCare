<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
</style>
<!-- Navbar -->
      <style>
        #login-nav {
          position: fixed !important;
          top: 0 !important;
          z-index: 1037;
          padding: 0.3em 2.5em !important;
        }
        /* #top-Nav{
          top: 2.3em; 
        }*/
        .text-sm .layout-navbar-fixed .wrapper .main-header ~ .content-wrapper, .layout-navbar-fixed .wrapper .main-header.text-sm ~ .content-wrapper {
          margin-top: calc(3.6) !important;
          padding-top: calc(3.2em) !important
      }
      </style>
 

      <!--Bootstrap 4.5.3 navbar-->
      <nav class="navbar navbar-expand-lg navbar-dark">
      <a href="./" class="navbar-brand">
            <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Site Logo" class="img-fluid brand-image img-circle elevation-3 mx-2" style="opacity: .8; height: 50px">
            <span class="mx-3"><?= $_settings->info('short_name') ?></span>
          </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav m-auto">
      <li class="nav-item active">
      <a href="./" class="nav-link <?= isset($page) && $page =='home' ? "active" : "" ?>">Home</a>
      </li>
     
      <li class="nav-item">
      <a href="./?page=services" class="nav-link <?= isset($page) && $page =='services' ? "active" : "" ?>">Our Services</a>
      </li>
      <li class="nav-item">
      <a href="./?page=about" class="nav-link <?= isset($page) && $page =='about' ? "active" : "" ?>">About Us</a>
      </li>
      <li class="nav-item">
      <a href="./?page=doctor_list" class="nav-link <?= isset($page) && $page =='doctor_list' ? "active" : "" ?>">Doctors</a>
      </li>
      <li class="nav-item">
      <a href="./?page=contact_us" class="nav-link <?= isset($page) && $page =='contact_us' ? "active" : "" ?>">Contact Us</a>
      </li>
      <?php if(isset($_SESSION['Auth']['User']['type']) && $_SESSION['Auth']['User']['type'] == '3'): ?>
        <li class="nav-item">
          <a href="./?page=appointment" class="nav-link <?= isset($page) && $page =='appointment' ? "active" : "" ?>">Appointment</a>
        </li>
        <li class="nav-item">
            <div class="btn-group nav-link">
                  <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                  <span>Hello,&nbsp;<?php echo ucwords($_SESSION['Auth']['User']['fullname']) ?></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="./?page=profile"><span class="fa fa-user"></span> Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url.'/classes/Customer.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
                  </div>
              </div>
          </li>
          <span class="ml-3"></span>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a href="./admin" class="nav-link">Login</a>
        </li>
        <li class="nav-item">
          <a href="./?page=admin/registration" class="nav-link">Sign Up</a>
        </li>
      <?php endif; ?>
    
    </ul>
  </div>
</nav>
