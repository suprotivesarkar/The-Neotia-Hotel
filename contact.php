<?php 
include '_top.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Contact</title>
<?php include '_header.php'; ?>
</head>
<body>
<?php include '_menu.php'; ?>
<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Contact Us</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Contact Us</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>
<section class="main-contact-area contact-info-area contact-info-three pt-100 pb-70">
<div class="container">
<div class="section-title">
<span>Contact Us</span>
<h2>Drop us a message for any query</h2>
<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque quibusdam deleniti porro praesentium. Aliquam minus quisquam velit in at nam.</p>
</div>
<div class="row">
<div class="col-lg-6">
<div class="contact-wrap contact-pages">
<div class="contact-form contact-form-mb">
<form id="contactForm">
<div class="row">
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="text" name="name" id="name" class="form-control" required data-error="Please enter your name" placeholder="Your Name">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="email" name="emailid" id="email" class="form-control" required data-error="Please enter your email" placeholder="Your Email">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="text" name="phone" id="phone" required data-error="Please enter your number" class="form-control" placeholder="Your Phone">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="text" name="subject" id="subject" class="form-control" required data-error="Please enter your subject" placeholder="Your Subject">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-12 col-md-12">
<div class="form-group">
<textarea name="message" class="form-control textarea-hight" id="message" cols="30" rows="4" required data-error="Write your message" placeholder="Your Message"></textarea>
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-12 col-md-12">
<button type="submit" class="default-btn btn-two">
Send Message
<i class="flaticon-right"></i>
</button>
<div id="msgSubmit" class="h3 text-center hidden"></div>
<div class="clearfix"></div>
</div>
<div class="rh col-xs-12 outmsg">
</div>
</div>
</form>
</div>
</div>
</div>
<div class="col-lg-6">
<div class="row">
<div class="col-lg-12 col-sm-12">
<div class="single-contact-info">
<i class="bx bx-envelope"></i>
<h3>Email Us:</h3>
<a href="mailto:thegrandchandiram@gmail.com">theneotiahotel@gmail.com</a>
<!-- <a href="mailto:hotelthegrandchandiram@info.com">hotelthegrandchandiram@info.com</a>
 --></div>
</div>
<div class="col-lg-12 col-sm-12">
<div class="single-contact-info">
<i class="bx bx-phone-call"></i>
<h3>Call Us:</h3>
<a href="tel:+918436303116">Tel. +91 8436303116</a>
<a href="tel:+918436303116">Tel. +91 8436303116</a>
</div>
</div>
<div class="col-lg-12 col-sm-12">
<div class="single-contact-info">
<i class="bx bx-location-plus"></i>
<h3>Location</h3>
<a href="javascript:void(0);">Bakkhali, Near Sea Beach<br>
     South 24 Paraganas - 743368, West Bengal, India</a>
</div>
</div>

</div>
</div>
</div>
</div>
</section>


<div class="map-area">
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7221.763162105219!2d75.84889689151001!3d25.173476062108847!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe12f564878ba6367!2sHotel%20The%20Grand%20Chandiram!5e0!3m2!1sen!2sus!4v1617869744337!5m2!1sen!2sus" allowfullscreen=""></iframe>
</div>

<?php include '_footer.php'; ?>
<script>
$(document).ready(function(){
	$("#contactForm").on('submit',(function(e){
    e.preventDefault();
    var url="_check_contact";
    var data = new FormData(this);
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      contentType: false,
      cache: false,
      processData:false, 
      dataType:"json",
      beforeSend: function(){$('.actionbtn').addClass('is-loading');},
      error: function(res){$('.actionbtn').removeClass('is-loading');},
      success: function(res){
        $('.actionbtn').removeClass('is-loading');
        $(".outmsg").html(res.msg);
        if(res.status){$("#contactForm").trigger('reset');}
      }
	})
  }));
});
</script>
</body>
</html>