<?php include '_auth.php'; ?>
<?php
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$stmt = $PDO->prepare("SELECT * FROM room_category WHERE roomcat_id='$id' AND roomcat_status<>2");
$stmt->execute(); 
$data = $stmt->fetch(PDO::FETCH_OBJ);
if(empty($data)){include '404.php';die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Room Details</title>
<?php  include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{display: inline-block;background: #1f7da0;margin: 10px 2px;padding: 4px 11px;border-radius: 24px;color: #fff;font-weight: 600;}
</style>
</head>
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Room Details - <?= $data->roomcat_name; ?> <a href="rooms" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> <a href="room-update?id=<?php echo $id; ?>" class="button x-small pull-right mx-2"><i class="fa fa-pencil"></i> Update</a></h5></div>
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link active" href="rooms-brief?id=<?= $data->roomcat_id; ?>">Brief Info</a></li>
<li class="nav-item"><a class="nav-link" href="rooms-gallery?id=<?= $data->roomcat_id; ?>">Gallery</a></li>
<li class="nav-item"><a class="nav-link" href="room-facilities?id=<?= $data->roomcat_id; ?>">Facility</a></li>
</ul>
<div class="table-responsive">          
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<td>NAME</td>
<td><?php echo $data->roomcat_name; ?></td>
<td>TYPE</td>
<td><?php echo ($data->roomcat_type==1)?"NON-AC":"AC"; ?></td>
</tr>
<tr>
<td>ADULT</td>
<td><?php echo $data->roomcat_adult; ?></td>
<td>CHILD</td>
<td><?php echo $data->roomcat_child; ?></td>
</tr>
<tr>
<td>Starting Price</td>
<td>₹ <?php echo $data->roomcat_price; ?></td>
<td>Extra Bed Cost</td>
<td>₹ <?php echo $data->roomcat_extrabed; ?></td>
</tr>
<tr>
<td>Small-Description</td>
<td colspan="3"><?php echo $data->roomcat_smalldesc; ?></td>
</tr>
<tr>
<td>Full-Description</td>
<td colspan="3"><?php echo $data->roomcat_fulldesc; ?></td>
</tr>
<tr>
<td>Amenities</td>
<td colspan="3">
<?php 
$facimsg = null; 
if (!empty($data->roomcat_amenities)) {
  $faacilist = explode(',', $data->roomcat_amenities);
  $facimsg   = '<ul class="list-inline faclist">';
  foreach ($faacilist as $eachfaci) {
    $nightstay_chk = CheckExists("room_amenities","am_id = '$eachfaci' AND am_status<>2");
    if (!empty($nightstay_chk)) {
      $name = $nightstay_chk->am_name;
      $facimsg .='<li>'.$name.'</li>';
    }
  }
  $facimsg.="</ul>";
}
echo $facimsg;
?>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>        
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript">
</script>
</body>
</html>