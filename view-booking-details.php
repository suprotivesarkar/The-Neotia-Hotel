<?php 
include '_top.php';
$id  = (!empty($_GET['id']))?FilterInput($_GET['id']):null; 
if (empty($id)){header("Location:./");}
$chk_pro = CheckExists("reservation_list","res_id = '$id' AND res_status<>3");
if (empty($chk_pro)){header("Location:./");}
if(!isset($_SESSION['userid'])){header("Location:./");die();}
$memdet=UserDetails($_SESSION['userid']);
if (empty($memdet)){header("Location:./");}
$memberid = $memdet['mem_id'];
 ?> 
 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - My Booking Details</title>
<?php include '_header.php'; ?>
</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>My Booking Details</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">My Booking Details</span></a>
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
<div class="col-md-12 col-sm-12">
<div class="panel panel-default">
<div class="panel-heading">
<h4>Booking Details <a href="my-bookings" title="Back To My-Bookings" style="float: right; padding: 3px 8px;font-size: 18px;background: #a7590b9e;border-radius: 5px; color: white;"><i class="fa fa-long-arrow-left"></i></a></h4>
</div>
<div class="panel-body">
<?php
$stmt = $PDO->prepare("SELECT * FROM reservation_list 
INNER JOIN reservation_rooms on reservation_rooms.resrooms_res_id_ref = reservation_list.res_id AND resrooms_status = 1 AND resrooms_res_id_ref = '$id'
INNER JOIN reservation_pay on reservation_pay.pay_res_id_ref = reservation_list.res_id AND pay_status = 1
WHERE res_mem_id_ref=:memid AND res_status<>0");
$stmt->execute(['memid' => $memberid]); 
if ($stmt->rowCount()>0){  ?>
<div class="table-responsive">          
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>#</th>
<th>BOOK-ID</th>
<th>CHECK-IN</th>
<th>CHECK-OUT</th>
<th>DAYS</th>
<th>CATEGORY</th>
<th>ROOM NO.</th>
<th>ROOM PRICE</th>
<th>STATUS</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row=$stmt->fetch()) { extract($row); 
 ?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $res_id; ?></td>
<td><?php echo date('d-M-Y', strtotime($resrooms_in)); ?></td>
<td><?php echo date('d-M-Y', strtotime($resrooms_out)); ?></td>
<td><?php $diff = abs(strtotime($resrooms_in) - strtotime($resrooms_out));
$days = ($diff/(60*60*24)); echo $days; ?></td>
<td><?php echo $resrooms_roomtype; ?></td>
<td><?php echo $resrooms_room_no; ?></td>
<td>Rs. <?php echo $resrooms_roomprice; ?></td>
<td><?php echo ($res_status==1)?'CONFIRMED':'CHECKED-OUT'; ?></td>
</tr>
<?php } ?>
<tr>
	<td colspan="3"></td>
<td colspan="3" style="color: white; background: #a7590b;">Transaction ID: <?php echo $pay_transaction; ?></td>
<td colspan="3" style="color: white; background: #a7590b;">Amount Paid(including tax): Rs. <?php echo $pay_amount; ?></td>
</tr>

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