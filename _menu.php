<?php debug_backtrace() || header("Location: 404");?>
<div class="eorik-nav-style eorik-nav-style-five fixed-top">
	<div class="navbar-area">
		<div class="mobile-nav">
			<a href="./" class="logo">
				<img src="images/logo.png" alt="The Neotia Hotel">
			</a>
		</div>
		<div class="main-nav">
			<nav class="navbar navbar-expand-md navbar-light">
				<div class="container">
					<a class="navbar-brand" href="./">
						<img src="images/logo.png" alt="The Neotia Hotel">
					</a>
					<div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
						<ul class="navbar-nav m-auto mar-nav">
							<li class="nav-item"> <a href="./" class="nav-link active"> Home </a></li>
				<li class="nav-item"> <a href="javascript:void(0);" class="nav-link dropdown-toggle"> Our Rooms <i class='bx bx-chevron-down'></i> </a>
								<ul class="dropdown-menu">
									<?php 
							            $stmt ="SELECT * FROM room_category WHERE roomcat_status=1";
										$res = $PDO->prepare($stmt);
										$res->execute();    
										$catlist = $res->fetchAll(); 
										foreach($catlist as $eachcat){ ?>
<li class="nav-item"> <a href="rooms/<?php echo $eachcat['roomcat_slug']; ?>" class="nav-link"><?php echo $eachcat['roomcat_name']; ?></a>
									</li>
									<?php }?>
								</ul>
							</li>
							<li class="nav-item"> <a href="gallery" class="nav-link dropdown-toggle"> Gallery  </a></li>
							<li class="nav-item"> <a href="aboutus" class="nav-link dropdown-toggle"> About Us </a></li>
							<li class="nav-item"> <a href="contact" class="nav-link dropdown-toggle"> Contact </a></li>
							<?php if(!empty($_SESSION['userid'])){ ?>
								<?php 
									include '_top.php';
									if(!isset($_SESSION['userid'])){header("Location:./");die();}
									$memdet=UserDetails($_SESSION['userid']);
									if (empty($memdet)){header("Location:./");}
									$memberid = $memdet['mem_id'];
									$memname  = $memdet['mem_name'];
									$mememail  = $memdet['mem_email'];
									$memphn  = $memdet['mem_phone'];
									?>
								<li class="nav-item"> <a href="profile" title="Hello, <?php echo $memname; ?>">My Profile</a></li>
								
								<?php }else{ ?>
						<li class="nav-item"> <a class="sidebar-button" href="#" data-bs-toggle="modal" data-bs-target="#loginreg"> Login / Register </a></li>
						<?php } ?>
						</ul>
						<div class="others-option">
							<a class="call-us" href="tel:+918436303116"> <i class="bx bx-phone-call bx-tada"></i></a>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</div>
</div>