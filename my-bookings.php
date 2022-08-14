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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - My Bookings</title>
<?php include '_header.php'; ?>
</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>My Bookings</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">My Bookings</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<section id="content">
<div id="content-wrap">
<div id="content-main" class="section-flat page-single page-dashboard">
<div class="section-content">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="page-single-content">
<div class="row">
<div class="col-md-12">
<div class="content">
<div class="row">
<div class="col-md-3 col-sm-4">
<ul class="list-dashboard-pages">
<li><a href="profile"><i class="fa fa-user"></i>Personal Profile</a></li>
<li class="active"><a href="my-bookings"><i class="fa fa-clipboard"></i>My Bookings</a></li>
<li><a href="settings"><i class="fa fa-cogs"></i>Settings</a></li>
<li class="user-logout"><a href="logout"><i class="fa fa-sign-out"></i>Logout</a></li>
</ul>
</div>
<div class="col-md-9 col-sm-8">
<div class="panel panel-default">
<div class="panel-heading">
<h4>Booking History</h4>
</div>
<div class="panel-body">
<?php
$stmt = $PDO->prepare("SELECT * FROM reservation_list 
INNER JOIN reservation_pay on reservation_pay.pay_res_id_ref = reservation_list.res_id AND pay_status = 1
WHERE res_mem_id_ref=:memid AND res_status<>0 ORDER BY res_create_at DESC");
$stmt->execute(['memid' => $memberid]); 
if ($stmt->rowCount()>0){  ?>
<div class="table-responsive">          
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>#</th>
<th>BOOKING DATE</th>
<th>PAID AMOUNT</th>
<th>TRANSACTION ID</th>
<th>STATUS</th>
<th>VIEW</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row=$stmt->fetch()) { extract($row); 

 ?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo date('d-M-Y', strtotime($res_create_at)); ?></td>
<td>Rs. <?php echo $pay_amount; ?></td>
<td><?php echo $pay_transaction; ?></td>
<td><?php echo ($res_status==1)?'CONFIRMED':'CHECKED-OUT'; ?></td>
<td><a href="view-booking-details?id=<?php echo $res_id; ?> " style="color: #e2b282;">View Details</a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php }else{
echo '<div class="alert alert-danger"><strong>Sorry, No Past Bookings Found!</strong></div>';
} ?>
</div>
</div>

</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>


<?php include '_footer.php'; ?>
</body>
</html>