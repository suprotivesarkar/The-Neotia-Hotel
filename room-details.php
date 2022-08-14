<?php 
include '_top.php'; 
$link=$_SERVER['REQUEST_URI']; 
$link_array=explode('/',$link);
$roomslug=FilterInput(strval(end($link_array)));
if(empty($roomslug) OR $roomslug=="rooms"){include '404.php';die();} 


$stmt = $PDO->prepare("SELECT * FROM room_category WHERE roomcat_slug='$roomslug' AND roomcat_status=1");
$stmt->execute(); 
$data = $stmt->fetch(PDO::FETCH_OBJ);
if(empty($data)){include '404.php';die();}
if(!empty($data->roomcat_coverimg) AND file_exists("images/rooms/room-coverimg/".$data->roomcat_coverimg)){
      $coverimg = "images/rooms/room-coverimg/".$data->roomcat_coverimg;}
else  $coverimg = "images/rooms/room-coverimg/placeholder.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - <?php echo $data->roomcat_name?></title>
<?php include '_header.php'; ?>

</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area" data-img="<?php echo $coverimg;?>">
<div class="container">
<div class="page-title-content">
<h1><?php echo $data->roomcat_name?></h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name"><?php echo $data->roomcat_name?></span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<section class="service-details-area room-details-right-sidebar ptb-100">
<div class="container">
<div class="row">
<div class="col-lg-8">
<div class="service-details-wrap service-right">
<div class="service-img-wrap owl-carousel owl-theme mb-30">
<?php 
$gallist = $PDO->prepare("SELECT * FROM room_gallery WHERE rg_roomcat_id_ref='$data->roomcat_id' AND rg_img_status=1");
$gallist->execute(); 
$galdet=$gallist->fetchAll();
if (empty($galdet)) {
echo '<img src="images/rooms/rgallery/placeholder.jpg" alt="">';
}else{
?>
<?php $count=1; foreach($galdet as $eachgal) {
extract($eachgal);
$mainimg="images/rooms/rgallery/".$rg_img_lg;

?>
<div class="single-services-imgs">
<img src="<?php echo $mainimg; ?>" alt="Image">
</div>
<?php  $count++;} ?>
<?php } ?>
</div>
<h3>About <?php echo $data->roomcat_name;?></h3>
<p><?php echo $data->roomcat_smalldesc;?></p>
<p><?php echo $data->roomcat_fulldesc;?></p>

</div>
</div>
<div class="col-lg-4">
<div class="service-sidebar-area">
<div class="service-list service-card">
<h3 class="service-details-title">From ₹ <?php echo $data->roomcat_price; ?>/night</h3>
<ul>
<li>
<?php echo $data->roomcat_adult; ?> Adult(Max)
<i class='bx bx-user-check'></i>
</li>
<li>
<?php echo $data->roomcat_child; ?> Child(Max)
<i class='bx bx-user-check'></i>
</li>
</ul>
</div>
<div class="service-list service-card">
<h3 class="service-details-title">Ammenities</h3>
<?php 
$facimsg = null; 
if (!empty($data->roomcat_amenities)) {
  $faacilist = explode(',', $data->roomcat_amenities);
  $facimsg   = '<ul>';
  foreach ($faacilist as $eachfaci) {
    $nightstay_chk = CheckExists("room_amenities","am_id = '$eachfaci' AND am_status<>2");
    if (!empty($nightstay_chk)) {
      $name = $nightstay_chk->am_name;
      $facimsg .='<li>'.'<i class="bx bx-check"></i>'.'<p>'.$name.'</p>'.'</li>';
    }
  }
  $facimsg.="</ul>";
}
echo $facimsg;
?>
</div>
<div class="service-list service-card">
<h3 class="service-details-title">Contact Info</h3>
<ul>
<li>
<a href="tel:+919414188006">
+91 9414188006
<i class='bx bx-phone-call bx-rotate-270'></i>
</a>
</li>
<li>
<a href="mailto:hotelthegrandchandiram@info.com">thegrandchandiram@gmail.com
<i class='bx bx-envelope'></i>
</a>
</li>
<li>
Near LIC Building, Chawani Circle, Jhahalwar Road
Kota - 324007 (Rajasthan), India
<i class='bx bx-location-plus'></i>
</li>
<li>
8:00 AM – 10:00 PM
<i class='bx bx-time'></i>
</li>
</ul>
</div>

</div>
</div>
</div>
</div>
</section>


<?php include '_footer.php'; ?>
<script type="text/javascript">
$('.page-title-area').each(function(i, obj) {
var action = $(obj).attr('data-img');
$(obj).css('background-image','url('+action +')');
});
</script>
</body>
</html>