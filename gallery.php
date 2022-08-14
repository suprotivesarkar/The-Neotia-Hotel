<?php 
include '_top.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Gallery</title>
<?php include '_header.php'; ?>
<link rel="stylesheet" type="text/css" href="assets/css/lightgallery.min.css">
<style type="text/css">
.galimg{margin-bottom:20px}
.l-main-content{padding-top:70px;padding-bottom:100px;border-top:1px solid #ddd}
#masonry{-webkit-column-count:4;-moz-column-count:4;column-count:4;padding:0;-moz-column-gap:5px;-webkit-column-gap:5px;column-gap:5px;margin-top:55px;}
#masonry img{margin-bottom:5px}
#masonry a{cursor:pointer}
@media(max-width:767px){
#masonry{-webkit-column-count:2;-moz-column-count:2;column-count:2;}
}
@media(max-width:500px){
#masonry{-webkit-column-count:1;-moz-column-count:1;column-count:1;}
}
</style>
</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Gallery</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Gallery</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<div class="l-main-content">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div id="masonry">
<?php
$stmt = $PDO->prepare("SELECT * FROM gallery WHERE gal_img_status=1 ORDER BY gal_id DESC");
$stmt->execute(); 
if($stmt->rowCount()>0){ 
while ($row=$stmt->fetch()){
extract($row);
?>
<a class="grid-item" href="images/gallery/<?php echo $gal_img_lg;?>"><img class="img-responsive galimg" src="images/gallery/<?php echo $gal_img;?>" /></a>
<?php } 
} ?>
</div>
</div>
</div>
</div>
</div>


<?php include '_footer.php'; ?>
<script type="text/javascript" src="assets/js/lightgallery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#masonry").lightGallery(); 
});
</script>
</body>
</html>