<?php debug_backtrace() || header("Location: 404");?>
<div class="wrapper">
<nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
<div class="text-left navbar-brand-wrapper">
<a class="navbar-brand brand-logo" href="dashboard"><img src="assets/img/logo1.png" alt=""></a>
<a class="navbar-brand brand-logo-mini" href="dashboard"><img src="assets/img/logo1.png" alt=""></a>
</div>
<ul class="nav navbar-nav mr-auto">
<li class="nav-item"> <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu ti-align-right"></i></a></li>
<li class="nav-item"> <a class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><?php echo Date('d-M D') ?> <span id="showtime"></span></a>
</li> 
</ul>
<ul class="nav navbar-nav ml-auto">
<li class="nav-item hidden-xs"> <a id="gosite" target="_blank" href="https://www.hotelguruestate.com" class="nav-link"><i class="ti-world"></i></a> 
</li>
<li class="nav-item fullscreen"> <a id="btnFullscreen" href="javascript:void(0);" class="nav-link"><i class="ti-fullscreen"></i></a> 
</li>
<li class="nav-item dropdown mr-30">
<a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><img src="../images/employee/<?php if(!empty($chk_auth->user_img)){ echo $chk_auth->user_img;} else{ echo 'placeholder.png';} ?>" alt="avatar"></a>
<div class="dropdown-menu dropdown-menu-right">
<span class="dropdown-item" ><h5><?php echo $chk_auth->user_name; ?></h5></span>
<a class="dropdown-item" href="settings"><i class="text-info ti-settings"></i>Settings</a>
<a class="dropdown-item" href="logout"><i class="text-danger ti-unlock"></i>Logout</a>
</div>
</li>
</ul>
</nav>
<div class="container-fluid">
<div class="row">
<div class="side-menu-fixed">
<div class="scrollbar side-menu-bg">
<ul class="nav navbar-nav side-menu" id="sidebarnav">
<?php if ($currole==1 OR $currole==2 OR $currole==3) {?>
<li><a href="dashboard"><i class="ti-home"></i><span class="right-nav-text">Dashboard</span></a></li>
<hr class="menuhr">
<li><a href="reservation"><i class="ti-location-pin"></i><span class="right-nav-text">Reservation</span></a></li>
<li><a href="payments"><i class="ti-receipt"></i><span class="right-nav-text">Payments</span></a></li>
<li><a href="quick-contact"><i class="ti-headphone-alt"></i><span class="right-nav-text">Quick Contact</span></a></li>
<li><a href="career"><i class="ti-headphone-alt"></i><span class="right-nav-text">Careers</span></a></li>
<?php } else { }?>
<?php if ($currole==1 OR $currole==2) {?>
<li>
<a href="javascript:void(0);" data-toggle="collapse" data-target="#calendar-menu">
<div class="pull-left"><i class="ti-filter"></i><span class="right-nav-text">Hotel Configuration</span></div>
<div class="pull-right"><i class="ti-plus"></i></div>
<div class="clearfix"></div>
</a>
<ul id="calendar-menu" class="collapse" data-parent="#sidebarnav">
<li><a href="roomnumber">Room Numbers</a></li>
<li><a href="rooms">Room Type</a></li>
<li><a href="room-amenities">Amenities</a></li>
<li><a href="room-facility">Facility</a></li>
</ul>
</li>

<li><a href="testimonials"><i class="ti-comment-alt"></i><span class="right-nav-text">Testimonials</span></a></li>

<li><a href="gallery"><i class="ti-gallery"></i><span class="right-nav-text">Gallery</span></a></li>
<?php } else { }?>
<?php if ($currole==1) {?>
<hr class="menuhr">
<li>
<a href="javascript:void(0);" data-toggle="collapse" data-target="#filter">
<div class="pull-left"><i class="ti-id-badge"></i><span class="right-nav-text">Employees</span></div>
<div class="pull-right"><i class="ti-plus"></i></div>
<div class="clearfix"></div>
</a>
<ul id="filter" class="collapse" data-parent="#sidebarnav">
<li><a href="employee-list">Employee List</a> </li>
<li><a href="employee-cat">Employee Category</a> </li>
<li><a href="employee-sal">Employee Salary</a> </li>
</ul>
</li>
<li><a href="login-activity"><i class="ti-desktop"></i><span class="right-nav-text">Login Activity</span></a></li>
<hr class="menuhr">
<?php } else { }?>
</ul>
</div>
</div>
<div class="content-wrapper">