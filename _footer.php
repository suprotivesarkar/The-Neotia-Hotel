<?php debug_backtrace() || header("Location: 404");?>
<footer class="footer-top-area pt-140 jarallax">
<div class="container">
<div class="footer-middle-area pt-60">
<div class="footer-logo" style="margin-bottom: 20px;">
<a href="./">
<img src="images/logo.png" alt="Image" style="max-width: 300px;">
</a>
</div>
<div class="row">
<div class="col-lg-5 col-md-6">

<div class="single-widget">

<h3>Contact Info</h3>
<ul class="information">
<li class="address">
<i class="flaticon-maps-and-flags"></i>
<span>Address</span>
Bakkhali, Near Sea Beach<br>
     South 24 Paraganas - 743368, West Bengal, India
</li>
<li class="address">
<i class="flaticon-call"></i>
<span>Phone</span>
<a href="tel:+918436303116">
+91 8436303116
</a>
</li>
<li class="address">
<i class="flaticon-envelope"></i>
<span>Email</span>
<a href="mailto:theneotiahotel@gmail.com">
theneotiahotel@gmail.com
</a>
</li>
</ul>
</div>
</div>
<div class="col-lg-2 col-md-6">
<div class="single-widget">
<h3>Quick Links</h3>
<ul>
<li>
<a href="./">
<i class="right-icon bx bx-chevrons-right"></i>
Home
</a>
</li>
<li>
<a href="rooms">
<i class="right-icon bx bx-chevrons-right"></i>
Our Rooms
</a>
</li>
<li>
<a href="aboutus">
<i class="right-icon bx bx-chevrons-right"></i>
About Us
</a>
</li>
<li>
<a href="gallery">
<i class="right-icon bx bx-chevrons-right"></i>
Gallery
</a>
</li>
<li>
<a href="contact">
<i class="right-icon bx bx-chevrons-right"></i>
Contact
</a>
</li>
</ul>
</div>
</div>
<div class="col-lg-2 col-md-6">
<div class="single-widget">
<h3>Services</h3>
<ul>
<li>
<a href="career">
<i class="right-icon bx bx-chevrons-right"></i>
Career
</a>
</li>
<li>
<a href="javascript:void(0);">
<i class="right-icon bx bx-chevrons-right"></i>
Resturant
</a>
</li>
<li>
<a href="terms-and-conditions">
<i class="right-icon bx bx-chevrons-right"></i>
Terms & Conditions
</a>
</li>
<li>
<a href="privacy-policy">
<i class="right-icon bx bx-chevrons-right"></i>
Privacy Policy
</a>
</li>
<li>
<a href="javascript:void(0);">
<i class="right-icon bx bx-chevrons-right"></i>
Cancellation Policy
</a>
</li>
</ul>
</div>

</div>
<div class="col-lg-3 col-md-6">
	<div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div>
</div>

</div>
</div>

<div class="footer-bottom-area">
<div class="row align-items-center">
<div class="col-lg-6">
<div class="copy-right">
<p>Copyright <i class="bx bx-copyright"></i> <?php echo date('Y'); ?>  <a href="./">The Neotia Hotel</a>. All Rights Reserved</p>
</div>
</div>
<div class="col-lg-6">
<div class="designed">
<p>Maintained By <i class='bx bx-heart'></i> <a href="javascript:void(0)">Group F</a></p>
</div>
</div>
</div>
</div>
</div>
<div class="footer-shape">
<img src="images/shape/white-shape-bottom.png" alt="Image">
</div>
</footer>
<div class="go-top"> <i class='bx bx-chevrons-up bx-fade-up'></i>  <i class='bx bx-chevrons-up bx-fade-up'></i>
</div>

<!--   <div class="modal fade" id="loginreg" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> -->
<div class="modal fade enq" id="loginreg" tabindex="-1" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-bs-dismiss="modal"> <i class="fa fa-times" aria-hidden="true"></i> 
</button>
<h4 class="modal-title">Login / Register</h4>
</div>
<div class="modal-body">
<div class="row">
<div class="col-xs-12">
<div class="well">
    <div class="login-form-container" id="lg1">
    <form id="logfrm" autocomplete="off">
        <div class="form-group col-xs-12">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required title="Please enter you username" placeholder="example@gmail.com / 9876543210">
        </div>
        <div class="form-group col-xs-12">
            <label class="control-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required title="Please enter your password" placeholder="Password">
        </div>
      
        <div class="checkbox">
            <div class="col-lg-6 col-sm-6 form-condition">
			<div class="agree-label">
			<input type="checkbox" id="chb1">
			<label for="chb1">
			Remember Me
			</label>
			</div>
			</div>
			
<!--             <p class="help-block">(if this is a private computer)</p> -->
        </div> <button type="submit" class="rh-check-btn btn-block btn-warning btn-lg logbtn col-sm-12" style="border: none; transition: 0.3s;">Log In</button>  <a href="javascript:;" class="btn btn-default btn-block" id="register">Create Account</a>
    </form><div class="logmsg col-sm-12"></div>
</div>
    <div class="login-form-container" id="lg2" style="display: none;">
        <form id="regform" autocomplete="off">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label for="name" class="control-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required title="Please enter your full-name" placeholder="First Name" />
            </div>
 
            <div class="form-group  col-md-12 col-sm-12 col-xs-12">
                <label for="email" class="control-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" required title="Please enter your email" placeholder="Email" />
            </div>
            <div class="form-group  col-md-12 col-sm-12 col-xs-12">
                <label for="email" class="control-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required title="Please enter your phone" placeholder="Phone" />
            </div>
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">Password</label>
                <input type="text" class="form-control" id="password" name="password" required title="Please enter your password" placeholder="Password" />
            </div>
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">Confirm Password</label>
                <input type="text" class="form-control" id="cpassword" name="cpassword" required title="Confirm password" placeholder="Confirm Password" />
            </div>
            <div class="alert alert-error hide">Wrong username or password</div>
            <div class="form-group signup col-md-12">
                <label>
                    <input type="checkbox" name="remember" /> Remember login
                </label>
                <button type="submit" class="rh-check-btn btn-block btn-warning btn-lg regbtn col-sm-12" style="border: none; transition: 0.3s;">Create Account</button>  <a href="javascript:;" class="btn btn-default btn-block" id="login">Already have an account? Login</a>
            </div>
        </div>
    </form> <div class="regmsg col-sm-12"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<a href="https://api.whatsapp.com/send?phone=918436303116&amp;text=Hello" target="_blank" class="whatsappbtn"><i class="fa fa-whatsapp"></i></a>

<section class="navbottom" id="navbottom">
<ul class="listmenu">
<li><a href="./"><i class="fa fa-home"></i> Home</a></li>
<?php if(empty($_SESSION['userid'])){ ?>
<li><a href="#" data-bs-toggle="modal" data-bs-target="#loginreg"><i class="fa fa-building"></i>Book Now</a></li>
<?php }else{?>
<li><a href="booking"><i class="fa fa-building"></i> Book Now</a></li>
<?php } ?>
<?php if(!empty($_SESSION['userid'])){ ?>
<li><a href="profile"><i class="fa fa-user"></i> Profile</a></li>
<?php }else{ ?>
    <li> <a href="#" data-bs-toggle="modal" data-bs-target="#loginreg"><i class="fa fa-user"></i> Login</a>
<?php } ?>
<li><a href="tel:+918436303116"><i class="fa fa-phone"></i>Call</a></li>
<li><a href="https://goo.gl/maps/EqxLzcCkTRFvfFUU6"><i class="fa fa-map-marker"></i>Location</a></li>
</ul>   
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/meanmenu.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/magnific-popup.min.js"></script>
<script src="assets/js/jquery.mixitup.min.js"></script>
<script src="assets/js/appear.min.js"></script>
<script src="assets/js/odometer.min.js"></script>
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<script src="assets/js/ofi.min.js"></script>
<script src="assets/js/jarallax.min.js"></script>
<script src="assets/js/form-validator.min.js"></script>
<script src="assets/js/contact-form-script.js"></script>
<script src="assets/js/ajaxchimp.min.js"></script>
<script type="text/javascript" src="assets/js/flatpickr.js"></script>
<script src="assets/js/custom.js"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v9.0" nonce="hIgGhnfG"></script>