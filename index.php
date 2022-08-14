<?php 
include '_top.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel</title>
<?php include '_header.php'; ?>
<style type="text/css">
.eorik-slider-area-five .slider-item-bg-13{
	background-image:url(images/banner/1.png);
}
.eorik-slider-area-five .slider-item-bg-14{
	background-image:url(images/banner/2.jpg);
}
.eorik-slider-area-five .slider-item-bg-15{
	background-image:url(images/banner/3.jpg);
}
</style>
</head>
<body>
<?php include '_menu.php'; ?>
<section class="eorik-slider-area eorik-slider-area-five">
	<div class="eorik-slider-five owl-carousel owl-theme">
		<div class="eorik-slider-item slider-item-bg-13">
			<div class="d-table">
				<div class="d-table-cell">
					<div class="container">
						<div class="eorik-slider-text overflow-hidden one">
							<h1>Deserves Vacation</h1>  <span>Discover the place where you have fun & enjoy a lot</span>
							<div class="slider-btn">
							<?php if(empty($_SESSION['userid'])){ ?> 
								<a class="default-btn" href="#" data-bs-toggle="modal" data-bs-target="#loginreg"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }else{ ?>
									<a class="default-btn" href="booking"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="eorik-slider-item slider-item-bg-14">
			<div class="d-table">
				<div class="d-table-cell">
					<div class="container">
						<div class="eorik-slider-text overflow-hidden two">
							<h1>Relax Vacation</h1>  <span>Discover the place where you have fun & enjoy a lot</span>
							<div class="slider-btn"> 
								<?php if(empty($_SESSION['userid'])){ ?> 
								<a class="default-btn" href="#" data-bs-toggle="modal" data-bs-target="#loginreg"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }else{ ?>
									<a class="default-btn" href="booking"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="eorik-slider-item slider-item-bg-15">
			<div class="d-table">
				<div class="d-table-cell">
					<div class="container">
						<div class="eorik-slider-text overflow-hidden three">
							<h1>Genius Vacation</h1>  <span>Discover the place where you have fun & enjoy a lot</span>
							<div class="slider-btn"> 
								<?php if(empty($_SESSION['userid'])){ ?> 
								<a class="default-btn" href="#" data-bs-toggle="modal" data-bs-target="#loginreg"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }else{ ?>
									<a class="default-btn" href="booking"> Book To Stay <i class="flaticon-right"></i> </a>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	     </div>
	    <div class="social-link">
		<ul>
		<li>
		<a href="javascript:void(0);">
		<i class="bx bxl-facebook"></i>
		</a>
		</li>
		<li>
		<a href="javascript:void(0);">
		<i class="bx bxl-twitter"></i>
		</a>
		</li>
		<li>
		<a href="javascript:void(0);">
		<i class="bx bxl-instagram"></i>
		</a>
		</li>
		</ul>
		</div>
</section>
<!-- <div class="check-area check-area-five mb-minus-70">
	<div class="container">
		<form class="check-form" action="#" method="post" id="serfrm">
			<div class="row align-items-center">
				<div class="col-lg-3 col-sm-6">
					<div class="check-content">
						<p>Arrival Date</p>
						<div class="form-group">
							<div class="input-group date" id="datetimepicker-1"> <i class="flaticon-calendar"></i> 
								<input type="text" class="form-control " id="chkin" name="chkin" placeholder="Select Date" required=""> <span class="input-group-addon"> <i class="glyphicon glyphicon-th"></i> </span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="check-content">
						<p>Departure Date</p>
						<div class="form-group">
							<div class="input-group date" id="datetimepicker-2"> <i class="flaticon-calendar"></i> 
								<input type="text" class="form-control" name="chkout" id="chkout" placeholder="Select Date"> <span class="input-group-addon"> <i class="glyphicon glyphicon-th"></i> </span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">

							<div class="check-content">
								<p>Room Category</p>
								<div class="form-group">
									<select id="category" name="category" class="form-content" required="">
										<option value="">-- Select Category --</option>
										<?php 
							            $stmt ="SELECT * FROM room_category WHERE roomcat_status=1";
										$res = $PDO->prepare($stmt);
										$res->execute();    
										$catlist = $res->fetchAll(); 
										foreach($catlist as $eachcat){ ?>
								<option value="<?php echo $eachcat['roomcat_id']; ?>"><?php echo $eachcat['roomcat_name']; ?></option>
									<?php } ?>
									</select>
								</div>

					</div>
				</div>
				<div class="col-lg-3">
					<div class="check-btn check-content">
						<button type="submit" class="default-btn actionbtn">Search Now <i class="flaticon-right"></i> 
						</button>
					</div>
				</div>
			</div>
			<div class="rh col-xs-12 outmsg"></div>
		</form>
	</div>
</div> -->
<section class="explore-area pt-170 pb-100">
<div class="container">
<div class="section-title">
<span>The Neotia Hotel</span>
<h2>Alive with your style of living</h2>
</div>
<div class="row align-items-center">
<div class="col-lg-6">
<div class="explore-content mr-30">
<h2>Best memories start here</h2>
<p>The The Neotia Hotel epitomises the most premium and luxurious stays in India be it for business or leisure travellers. We believe in innovation, perfection and excellence. Our objective is to provide discerning travellers memorable, warm and rejuvenating stays in an ambience that embraces the essence of India.</p>
<p>The deeply instilled The Neotia Hotel culture is personified in our associates – people who share our belief of setting new paradigms in the world of hospitality and creating an experience for guests that not only surpasses excellence but creates memorable moments that last a lifetime. We believe in service excellence and taking our guests to reverential heights keeping our philosophy of "Atithi devo Bhava – Our Guest is God" in mind.</p>
<a href="aboutus" class="default-btn">
Explore More
<i class="flaticon-right"></i>
</a>
</div>
</div>
<div class="col-lg-6">
<div class="explore-img explore-img-two">
<img src="images/explore-img.png" alt="Image">
</div>
</div>
</div>
</div>
</section>
<section class="our-rooms-area pb-70">
	<div class="container">
		<div class="section-title"> <span>Our Rooms</span>
			<h2>Fascinating rooms & suites</h2>
		</div>
		<div class="row">
			<?php 
                    $stmt ="SELECT *
                    FROM room_category
                    WHERE roomcat_status=1";
                    $res = $PDO->prepare($stmt);
                    $res->execute();    
                    $romlist = $res->fetchAll();
                    foreach ($romlist  as $pkges){extract($pkges); 
                    if(!empty($roomcat_thumb) AND file_exists('images/rooms/room-thumb/'.$roomcat_thumb)){
                    $img =  $roomcat_thumb;       
                    }else{
                    $img = "placeholder.jpg";
                    }
                    ?>
			<div class="col-lg-6 col-sm-6">
				<div class="single-rooms-three-wrap">
					<div class="single-rooms-three">
						<img src="images/rooms/room-thumb/<?php echo $img; ?>" alt="<?php echo $roomcat_thumb_alt; ?>">
						<div class="single-rooms-three-content">
							<h3><?php echo $roomcat_name; ?></h3>
							<ul class="rating">
								<li> <i class="bx bxs-coffee-alt bx-tada"></i>
								</li>
								<li> <i class="bx bx-wifi bx-tada"></i>
								</li>
								<li> <i class="bx bxs-bath bx-tada"></i>
								</li>
								<li> <i class="bx bxs-slideshow bx-tada"></i>
								</li>
								<li> <i class="bx bxs-dish bx-tada"></i>
								</li>
							              </ul> <span class="price">From ₹<?php echo $roomcat_price; ?>/night</span>  <a href="rooms/<?php echo $roomcat_slug; ?>" class="default-btn"> View More <i class="flaticon-right"></i> </a>
						</div>
					</div>
				</div>
			</div>
		<?php }?>
		</div>
	</div>
</section>

<section class="choose-restaurant-area pb-100">
<div class="container">
<div class="section-title">
<span>Our Services</span>
<h2>More than meets the eye</h2>
</div>
<div class="row">
<div class="col-lg-6">
<div class="choose-restaurant-img">
<img src="assets/img/choose-restaurant-img.jpg" alt="Image">
</div>
</div>
<div class="col-lg-6">
<div class="choose-tab-wrap">
<div class="tab quote-list-tab choose-tab">
<ul class="tabs">
<li>
PALMS Restaurant
</li>
<li>
Vivaldi Banquet Hall
</li>
</ul>
<div class="tab_content">
<div class="tabs_item">
<p>A diamond in the crown of Kota, the finest restaurant in Kota, is a multi cuisine yet pure vegetarian restaurant located in the heart of kota, tasty and authentic Indian, continental and Chinese cuisines are served with music in an atmosphere just right for every mood and occasions.</p>
<p>Facilities :-
<li>Ala carte</li>
<li>Take away</li>
<li>Catering</li></p>
<a class="default-btn" href="aboutus">
Learn About Us
<i class="flaticon-right"></i>
</a>
</div>
<div class="tabs_item">
<p>Vivaldi Banquet hall can cater a party up to the capacity of 800 People at a time, the new design and the location of banquet hall and combination of packages provided from management of The Neotia Hotel, in which a person enjoys the quality and services in the most professional manner.</p>
<p>The milestone of Kota city has made its place spreading all its charm and giving people a chance to forget about all the hard work and tensions involved in making arrangements for parties and start enjoying them. So being a host is a pleasure now.</p>
<a class="default-btn" href="aboutus">
Learn About Us
<i class="flaticon-right"></i>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- <section class="our-rooms-area-two our-rooms-area-four pt-100">
<div class="container">
<div class="section-title">
<span>Our Services</span>
<h2>More than meets the eye</h2>
</div>
<div class="tab industries-list-tab">
<div class="row">
<div class="col-lg-5">
<div class="tabs row">
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-coffee-cup-1"></i>
<span class="booking-title">Resturant</span>
<h3>PALMS  Restaurant</h3>
</div>
</div>
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-podium"></i>
<span class="booking-title">Free cost</span>
<h3>Best rate guarantee</h3>
</div>
</div>
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-airport"></i>
<span class="booking-title">Free cost</span>
<h3>Reservations 24/7</h3>
</div>
</div>
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-slow"></i>
<span class="booking-title">Free cost</span>
<h3>High-speed Wi-Fi</h3>
</div>
</div>
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-coffee-cup-1"></i>
<span class="booking-title">Free cost</span>
<h3>Free breakfast</h3>
</div>
</div>
<div class="col-lg-6 col-sm-6 single-tab">
<div class="single-rooms">
<i class="flaticon-user"></i>
<span class="booking-title">100% free</span>
<h3>One person free</h3>
</div>
</div>
</div>
</div>
<div class="col-lg-7">
<div class="tab_content">
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-1">
</div>
</div>
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-2">
</div>
</div>
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-3">
</div>
</div>
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-4">
</div>
</div>
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-5">
</div>
</div>
<div class="tabs_item">
<div class="our-rooms-single-img room-bg-6">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section> -->

<section class="facilities-area-five pb-100">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4 p-0">
				<div class="facilities-single-bg"></div>
			</div>
			<div class="col-lg-8 p-0">
				<div class="facilities-right-wrap pt-100 pb-70">
					<div class="facilities-title"> <span>AMENITIES</span>
						<h2>Favor will not have execution assumption go on you</h2>
					</div>
					<div class="row mr-0">
						<div class="col-lg-4 col-sm-6">
							<div class="single-facilities-wrap">
								<div class="single-facilities"> <i class="facilities-icon flaticon-pickup"></i>
									<h3>Pick Up & Drop​</h3>
									<p>parkn ipsum dolor sit amet, consectetur adiing elit sed do eiu adiing</p>
									<a href="aboutus" class="icon-btn"> <i class="flaticon-right"></i> 
									</a>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="single-facilities-wrap">
							<div class="single-facilities">
							<i class="facilities-icon flaticon-coffee-cup"></i>
							<h3>Welcome Drink​​​​</h3>
							<p>parkn ipsum dolor sit amet, consectetur adiing elit sed do eiu</p>
							<a href="aboutus" class="icon-btn">
							<i class="flaticon-right"></i>
							</a>
							</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6 offset-sm-3 offset-lg-0">
							<div class="single-facilities-wrap">
								<div class="single-facilities"> <i class="facilities-icon flaticon-garage"></i>
									<h3>​​Parking Space</h3>
									<p>parkn ipsum dolor sit amet, consectetur adiing elit sed do eiu adiing</p>
									<a href="aboutus" class="icon-btn"> <i class="flaticon-right"></i> 
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="facilities-area-four pt-60">
	<div class="container">
		<div class="section-title"> <span>facilities</span>
			<h2>Giving entirely awesome</h2>
		</div>
		<div class="row">
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-speaker bx-tada-hover"></i>
					<h3>Meetings & Special Events</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-coffee-cup bx-tada-hover"></i>
					<h3>Welcome Drink</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-garage bx-tada-hover"></i>
					<h3>Parking Space</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-water bx-tada-hover"></i>
					<h3>Cold & Hot Water</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-pickup bx-tada-hover"></i>
					<h3>Pick Up & Drop</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="singles-facilities"> <i class="flaticon-swimming bx-tada-hover"></i>
					<h3>Swimming Pool</h3>
					<p>Morem ipsum dol sitamcectur Risus commodo vivercs.</p>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="incredible-area ptb-140 jarallax">
<div class="container">
<div class="incredible-content">
<a href="callto:+91 9876543210" class="video-btn popup-youtube">
<i class="bx bxs-bell bx-tada"></i>
</a>
<h2><span>Incredible!</span> Are you coming today</h2>
<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maiores sed obcaecati, magni excepturi, temporibus vero, inventore tenetur assumenda natus sequi labore. Voluptates eligendi dolores quod temporibus aperiam adipisci, quasi reprehenderit. Voluptates eligendi dolores quod temporibus.</p>
<?php if(empty($_SESSION['userid'])){ ?>
<a href="#" data-bs-toggle="modal" data-bs-target="#loginreg" class="default-btn">
Book Now
<i class="flaticon-right"></i>
</a>
<?php }else{?>
<a href="booking" class="default-btn">
Book Now
<i class="flaticon-right"></i>
</a>
<?php }?>
</div>
</div>
<div class="white-shape">
<img src="images/shape/white-shape-top.png" alt="Image">
<img src="images/shape/white-shape-bottom.png" alt="Image">
</div>
</section>

<section class="customer-area ptb-100">
<div class="container-fluid p-0">
<div class="section-title">
<span class="pumpkin-color">TESTIMONIALS</span>
<h2>Few words from the beloved customers</h2>
</div>
<div class="customer-wrap owl-carousel owl-theme">
<?php 
$stmt ="SELECT *
            FROM testimonials
            WHERE testi_status=1 ORDER BY RAND() LIMIT 6 ";
$res = $PDO->prepare($stmt);
$res->execute();    
$teslist = $res->fetchAll();
foreach ($teslist  as $pkges){extract($pkges); 
if(!empty($testi_img) AND file_exists('images/testimonial/'.$testi_img)){
  $img =  $testi_img;       
}else{
  $img = "user.png";
}
?>
<div class="single-customer">
<img src="images/testimonial/<?php echo $img; ?>" alt="Image">
<h3><span class="colors"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span></h3>
<p><?php echo $testi_text; ?></p>
<span class="colors"><?php echo $testi_name; ?></span>
</div>
<?php } ?>
</div>
</div>
</section>

<?php include '_footer.php'; ?>
<script type="text/javascript">
	$("#serfrm").on('submit',(function(e){
e.preventDefault();
    var url="_user_reservation";
    var data = new FormData(this);
    data.append("operation","searchrom");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
     beforeSend: function(){$('.actionbtn').addClass('is-loading');},
      error: function(res){$('.actionbtn').removeClass('is-loading');},
      success: function(res)
      {
         $('.actionbtn').removeClass('is-loading');
        if(res.status){location.href=res.msg;}else{$(".outmsg").html(res.msg);}
      }
    }); 
})); 
</script>
</body>
</html>