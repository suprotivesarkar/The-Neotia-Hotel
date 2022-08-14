<?php 
include '_top.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Career</title>
<?php include '_header.php'; ?>
</head>
<body>
<?php include '_menu.php'; ?>
<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Career</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Career</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<section class="terms-conditions ptb-100">
<div class="container">
<div class="single-privacy">
<h3 class="mt-0">Welcome To Hotel The Grand Chandiram's Career</h3>
<p>The Hotel The Grand Chandiram epitomises the most premium and luxurious stays in India be it for business or leisure travellers. We believe in innovation, perfection and excellence. Our objective is to provide discerning travellers memorable, warm and rejuvenating stays in an ambience that embraces the essence of India.</p>
<p>The deeply instilled Hotel The Grand Chandiram culture is personified in our associates – people who share our belief of setting new paradigms in the world of hospitality and creating an experience for guests that not only surpasses excellence but creates memorable moments that last a lifetime. We believe in service excellence and taking our guests to reverential heights keeping our philosophy of "Atithi devo Bhava – Our Guest is God" in mind.</p>
<p>With an impressive bouquet of hotels in the country, The Hotel The Grand Chandiram Palaces, Hotels and Resorts have created a distinct niche for itself among the country's finest hotels. In keeping with our legacy of continuous and sustained growth along with a constant endeavour to expand further, the company envisions bright career prospects and growth opportunities for our employees.</p>
</div>
<div class="main-contact-area contact-info-area contact-info-three pt-100 ">
<div class="col-lg-6">
<div class="contact-wrap contact-pages">
<div class="contact-form contact-form-mb">
<form id="careerform">
<div class="row">
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="text" name="name" id="name" class="form-control" required data-error="Please enter your name" placeholder="Your Name">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-6 col-sm-6">
<div class="form-group">
<input type="email" name="emailid" id="emailid" class="form-control" required data-error="Please enter your email" placeholder="Your Email">
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
<input type="file" name="resumeimg" id="resumeimg" class="form-control" required data-error="Please attach your Resume/CV" placeholder="Your Subject">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-12 col-md-12">
<button type="submit" class="default-btn btn-two">
Send Resume/CV
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
</div>
</div>
</section>

<?php include '_footer.php'; ?>
<script>
$(document).ready(function(){
	$("#careerform").on('submit',(function(e){
    e.preventDefault();
    var url="_check_career";
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
        if(res.status){$("#careerform").trigger('reset');}
      }
	})
  }));
});
</script>
</body>
</html>