<?php 
include '_top.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Our Rooms</title>
<?php include '_header.php'; ?>
<style type="text/css">
	.rh-img a {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1;
}
.rh-img img {
    max-width: 100%;
    backface-visibility: hidden;
    vertical-align: top;
}
	.sort-by .sort-by-list .rh-feature-box .rh-img a:before, .sort-by .sort-by-grid .rh-feature-box .rh-img a:before {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    opacity: 1;
    background: rgba(213, 166, 119, 0.2);
}
	.price{
	font-size: 33px;
    color: #a26106;
    font-weight: 800;
    margin-bottom: 20px;}
.price span{
	color: #aba3a3;
    font-size: 18px;
    position: relative;
    top: -6px;
}
.rh-feature-box {
    box-shadow: 8px 9px 12px -4px #cac7c4;
}
	.sort-by .sort-by-list div {
    width: 100%;
}
.rh-feature-box {
    box-shadow: 8px 9px 12px -4px #cac7c4;
}
.rh-feature-box {
    text-align: left;
    padding: 20px;
    margin-bottom: 18px;
}
.rh-margin-30 {
    margin: 0 0 30px 0;
}
.rh {
    width: 100%;
    display: inline-block;
    vertical-align: top;
}
.sort-by .sort-by-list .rh-feature-box .rh-img {
    width: 40%;
    float: left;
}
.rh-img {
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
    transform: translateZ(0);
}
.sort-by .sort-by-list .rh-feature-box .feature-detail {
    padding: 0 0 0 20px;
}
.sort-by .sort-by-list .rh-feature-box .feature-detail {
    width: 60%;
}
.rh-feature-box .feature-detail {
    display: inline-block;
    vertical-align: top;
}
.rh {
    display: inline-block;
    vertical-align: top;
}
.sort-by .sort-by-list .rh-feature-box .feature-detail .rh p.rh-top {
    display: block;
    margin: 0 0 10px 0;
}
.rh-feature-box .feature-detail .rh p {
    overflow: hidden;
}
.rh-60{
	padding: 60px 0;
}
.rh-img *, .rh-img *:before, .rh-img *:after{
	transition: all 0.4s ease;
}
.rating-star {
    margin: 13px 10px 19px 0;
    font-size: 23px;
    display: flex;
    align-items: center;
}
.rating-star ul {
    margin: 0 10px 0 0;
}
.rating-star ul li {
    float: left;
    margin: 0 2px 0 0;
}
.rating-star ul li i{
	color: #cc8c18;
}
@media screen and (max-width: 480px){
	.sort-by .sort-by-list .rh-feature-box .feature-detail{
		width: 100%;
        margin-top: 10px;
	}
	.sort-by .sort-by-list .rh-feature-box .rh-img{
		width: 100%;
	}
	.sort-by .sort-by-list .rh-feature-box .feature-detail .rh p.rh-top{
		display: none;
	}
	.rh-feature-box .rating-star{
		margin: none;
	}
}
</style>
</head>
<body>
<?php include '_menu.php'; ?>
<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Our Rooms</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Our Rooms</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<div class="rh-60">
<div class="container">
<div class="row">
<div class="lazy slider" data-sizes="50vw">
<div id="sort-by" class="sort-by">
<div id="grid_list" class="sort-by-list">
<div class="rh-flex">
<?php 
$stmt ="SELECT *
FROM room_category
WHERE roomcat_status=1 ORDER BY RAND() ";
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
<div class="col-md-6 col-sm-6 col-xs-6 xs-pr">
<div class="rh rh-feature-box rh-margin-30">
<div class="rh-img">
<img src="images/rooms/room-thumb/<?php echo $img; ?>" alt="<?php echo $roomcat_thumb_alt; ?>" />
<a href="rooms/<?php echo $roomcat_slug; ?>"></a>
</div>
<div class="feature-detail">
<a href="rooms/<?php echo $roomcat_slug; ?>"><h4><?php echo $roomcat_name; ?></h4></a>
<div class="rating-star">
<ul>
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
</ul>
</div>
<div class="rh">
<p class="rh-top"><?php echo $roomcat_smalldesc; ?></p>
<p class="price">₹<?php echo $roomcat_price; ?>/-<span> pernight</span></p>
<a href="rooms/<?php echo $roomcat_slug; ?>" class="default-btn">VIEW MORE</a>
</div>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


<?php include '_footer.php'; ?>
</body>
</html>