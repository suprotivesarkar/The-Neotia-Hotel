<?php include '_auth.php';
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$data = CheckExists("reservation_list","res_id = '$id' AND res_status<>3");
if(empty($data)){include '404.php';die();}
?>
<!DOCTYPE html>
<html lang="en"> 
<head> 
<title>Guest Details</title>
<?php include '_header.php'; ?>
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-clockpicker.min.css">
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{padding: 5px;background: #e2e2e2;margin: 2px;color: #fff;font-weight: 600;border-radius: 3px;background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #c17a9e 0%, #e260b4 33%, #ee609c 66%, #ee609c 100%);}
.includelist li{padding: 2px 8px;background: #2e7ba9;margin: 3px 2px;border-radius: 5px;color: #fff;font-weight: 600;}
.calbtn{margin-left:15px!important;width:100%!important;padding:10px 25px!important;}
.calbkpan{position:relative;margin-top:5px}.calbkpan input{height:42px;width:100%;padding-right:43px;padding-left:10px;}
.calbkpan button{position:absolute;right:0;top:0;background:#0b7bbd;outline:none;border:none;color:#fff;height:42px;width:40px;padding:0;border-radius:0px;}
.calbkpan button .fa{font-size:22px}
.empimg {position: absolute; left: 14px; top: 1px;}
.empimg img{width: 70px; border-radius: 50px;}
.stat{display: inline-block;
    background: #f69300;
    margin: 1px 2px;
    padding: 2px 6px;
    color: #fff;
    font-weight: 600;}
</style>
</head>  
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics mb-2">
<div class="card-title">
<div class="empdet"><h5>GUEST NAME - <span class="text-danger bold"><?php echo $data->res_g_name; ?></span> <a href="reservation" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> </h5>
<h5>GUEST PHONE - <span class="text-primary bold"><tr><td><?php  echo $data->res_g_phone;?></td><tr></span></h5>
<div class="stat">
<?php 
$stat = null;
if($data->res_status == 0){ $stat = "PENDING";}
elseif ($data->res_status == 1) {$stat = "CONFIRMED";}
elseif ($data->res_status == 2) {$stat = "CHECKED OUT";}
else{$stat = "Invalid Data"; }

echo $stat;
 ?>	
</div>
</div>
</div>
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link active" href="reservation-view?id=<?php echo $data->res_id; ?>">BRIEF INFO</a></li>
<li class="nav-item"><a class="nav-link" href="res-payupdate?id=<?php echo $data->res_id; ?>">PAYMENT ADD/UPDATE</a></li>
<li class="nav-item"><a class="nav-link" href="res-roomupdate?id=<?php echo $data->res_id; ?>">ROOM ADD/UPDATE</a></li>
</ul>
<div class="table-responsive"> 

<table class="table table-hover table-bordered text-left">
<tbody>
<h5 style="color: #008000; font-weight: 600;">ROOM DETAILS</h5>	  

<?php 
$stmt ="SELECT *
FROM reservation_rooms
WHERE resrooms_res_id_ref = '$data->res_id' AND resrooms_status=1 ";
$res = $PDO->prepare($stmt);
$res->execute();    
$teslist = $res->fetchAll();
?>

<tr>
<th>#</th>
<th>CHECK-IN</th>
<th>CHECK-OUT</th>
<th>DAYS</th>
<th>ROOM TYPE</th>
<th>ROOM NO.</th>
<th>ROOM PRICE</th>
<th>EXTRA-BED PRICE</th>
<th>BED QTY</th>
</tr>
<?php 
$i = 1;
$diff = 0;
$day = null;
foreach ($teslist  as $pkges){ ?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo(date ("d-M-Y",strtotime($pkges['resrooms_in']))); ?></td>
<td><?php echo(date ("d-M-Y",strtotime($pkges['resrooms_out']))); ?></td>
<td><?php 
$diff = abs(strtotime($pkges['resrooms_out']) - strtotime($pkges['resrooms_in']));
$days = ($diff/(60*60*24));
echo $days;
?></td>
<td><?php echo $pkges['resrooms_roomtype']; ?></td>
<td><?php echo $pkges['resrooms_room_no']; ?></td>
<td>₹ <?php echo $pkges['resrooms_roomprice']; ?></td>
<td>₹ <?php echo $pkges['resrooms_extrabed_price']; ?></td>
<td><?php echo $pkges['resrooms_exbed_qty']; ?></td>
</tr>
<?php } ?>
</tbody>
</table><hr>
<h5 style="color: #b7151c; font-weight: 600;">PAYMENT DETAILS</h5>	       
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<th>SL NO.</th>
<th>AMOUNT CREATE DATE</th>
<th>AMOUNT UPDATE DATE</th>
<th>AMOUNT PAID</th>
<th>MODE</th>
<th>TRANSACTION ID</th>
</tr>
<?php 
$i = 1;
$sum=0;
$ptype = null;
$pats ="SELECT *
FROM reservation_pay
WHERE pay_res_id_ref = '$data->res_id' AND pay_status=1 ";
$pay = $PDO->prepare($pats);
$pay->execute();    
$rompay = $pay->fetchAll();
foreach ($rompay  as $payr){ 
$sum = $sum + $payr['pay_amount'];
if($payr['pay_type']==1){$ptype="ONLINE";}else{$ptype="OFFLINE";}
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo(date ("d-M-Y",strtotime($payr['pay_create_at']))); ?></td>
<td><?php echo(date ("d-M-Y",strtotime($payr['pay_update_at']))); ?></td>
<td>₹ <?php echo $payr['pay_amount']; ?></td>
<td><?php echo $ptype; ?></td>
<td><?php echo $payr['pay_transaction']; ?></td>
</tr>
<?php } ?>
<tr>
	<?php 
		
		$romfind = $PDO->prepare("SELECT * FROM reservation_rooms 
		WHERE resrooms_res_id_ref ='$id' AND resrooms_status<>2 ");
		$romfind->execute();
		$resdet=$romfind->fetchAll();
		$total =0;
		foreach ($resdet as $totalres) {
        $diff = abs(strtotime($totalres['resrooms_out']) - strtotime($totalres['resrooms_in']));
		$days = ($diff/(60*60*24));
		$total = $total + ($totalres['resrooms_roomprice']*$days) + ($totalres['resrooms_exbed_qty']*$totalres['resrooms_extrabed_price']);
	}
	?>
<?php $tax = 0; if($total>1000 && $total<=7500){ $tax = 12;}
elseif ($total>7500) {
  $tax = 18;
}
$tottax = $total + Tax($total);
?>
<td colspan="2" style="background-color: #055a15;color: #ffffff;">Total Amount: ₹ <?php echo $total; ?> + ₹<?php echo Tax($total); ?>(<?php echo $tax; ?>% gst) = ₹ <?php echo $tottax; ?></td>
<td colspan="2" style="background-color: #04046d;color: #ffffff;">Amount Paid: ₹ <?php echo $sum; ?></td>
<td colspan="2" style="background-color: #f32520;color: #ffffff;">Due:₹ <?php echo $tottax - $sum; ?></td>
</tr>
</tbody>
</table><hr>
<h5 style="color: #0c4dc3b8; font-weight: 600;">BASIC DETAILS<a data-toggle="modal" data-target="#upMod" class="button pull-right x-small" style="color: white;" ><i class="fa fa-edit"></i> Update Basic Details</a></h5>
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<th>NAME</th>
<td><?php echo $data->res_g_name; ?></td>
<th>PHONE NO.</th>
<td><?php echo $data->res_g_phone; ?></td>
<th>EMAIL ID</th>
<td><?php echo $data->res_g_email; ?></td>
</tr>
<tr>
<th>RESIDENTIAL ADDRESS</th>
<td><?php echo $data->res_g_address; ?></td>
<th> CITY</th>
<td><?php echo $data->res_g_city; ?></td>
<th>ZIPCODE</th>
<td><?php echo $data->res_g_zipcode; ?></td>
</tr>
<tr>
<th>COUNTRY</th>
<td><?php echo $data->res_g_country; ?></td>
<th>ADULT</th>
<td><?php echo $data->res_g_adult; ?></td>
<th>CHILDREN</th>
<td><?php echo $data->res_g_child; ?></td>
</tr>
<tr>
<th>CHECK-IN TIME</th>
<td><?php  echo(date ("h:i A",strtotime("$data->res_g_intime"))); ?></td>
<th>CHECK-OUT TIME</th>
<td><?php if (!empty($data->res_g_outtime)) {
 echo(date ("h:i A",strtotime("$data->res_g_outtime")));
}else{ 'NO OUT';}  ?></td>
<th>GUEST DOC</th>
<td>
<?php if(!empty($data->res_g_doc)){
echo '<a href="../images/guestdoc/'.$data->res_g_doc.'" target="/"><h6 style="color: #005bff; font-weight: 600;">VIEW</h6></a></td>'; } 
else{echo'-NO DOC-';}
?>

</tr>
<tr>
<th>GUEST NOTE</th>
<td><?php echo $data->res_g_note; ?></td>
</tr>
</tbody>
</table>
</div>
<div class="modal fade" id="upMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Basic Details</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid">
<div class="row">
<div class="form-group col-sm-6">
<label for="chkintime">CHECK-IN TIME:<span class="req">*</span></label>
<div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
<input type="text" class="form-control valid" value="" name="chkintime" id="chkintime" aria-invalid="false">
<span class="input-group-addon" style="background: #84ba3f;"><span class="fa fa-clock-o"></span></span>
</div>
</div>
<div class="form-group col-sm-6">
<label for="chkouttime">CHECK-OUT TIME:<span class="req">*</span></label>
<div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
<input type="text" class="form-control valid" value="" name="chkouttime" id="chkouttime" aria-invalid="false">
<span class="input-group-addon" style="background: #84ba3f;"><span class="fa fa-clock-o"></span></span>
</div>
</div>
<div class="form-group col-sm-6">
<label for="upgname">NAME:<span class="req">*</span></label>
<input type="text" class="form-control" id="upgname" name="upgname">
</div>
<div class="form-group col-sm-6">
<label for="upphno">PHONE NO:<span class="req">*</span></label>
<input type="text" class="form-control " id="upphno" name="upphno">
<span class="help-block">Seperate the phone no. by adding a ','(comma) before.</span>
</div>
<div class="form-group col-sm-6">
<label for="upmail">EMAIL ID:<span class="req">*</span></label>
<input type="mail" class="form-control " id="upmail" name="upmail">
<span class="help-block">Seperate the email by adding a ','(comma) before.</span>
</div>
<div class="form-group col-sm-6">
<label for="upaddress">RESIDENTIAL ADDRESS:<span class="req">*</span></label>
<input type="text" class="form-control" id="upaddress" name="upaddress">
</div>
<div class="form-group col-sm-4">
<label for="upcity">CITY:<span class="req">*</span></label>
<input type="text" class="form-control" id="upcity" name="upcity">
</div>
<div class="form-group col-sm-4">
<label for="upzip">ZIPCODE:<span class="req">*</span></label>
<input type="text" class="form-control" id="upzip" name="upzip">
</div>

<div class="form-group col-sm-4">
<label for="upcountry">COUNTRY:<span class="req">*</span></label>
<input type="text" class="form-control" id="upcountry" name="upcountry">
</div>
<div class="form-group col-sm-4">
<label for="upadult">ADULT:<span class="req">*</span></label>
<input type="number" class="form-control" id="upadult" name="upadult">
</div>
<div class="form-group col-sm-4">
<label for="upchild">CHILD:</label>
<input type="number" class="form-control" id="upchild" name="upchild">
</div>
<div class="form-group col-sm-4">
<label for="updoc">GUEST DOC:</label>
<input type="file" class="form-control" id="updoc" name="updoc" >
</div>
<div class="form-group col-sm-12">
<label for="upnote">GUEST NOTE:</label>
<input type="text" class="form-control" id="upnote" name="upnote" >
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Update Now</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript" src="assets/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
	$('.clockpicker').clockpicker();
	$('.tagify').tagsinput({});

	$("#upMod").on('shown.bs.modal',function(e){
  $.ajax({
    type: "POST",
    dataType:"json",
    url: "helper/master/reservation-data",
    data: {"operation":"fetchbasic","id":"<?php echo $data->res_id; ?>"},
    beforeSend: function(){},
    error: function(res){showToast("Something Wrong Try Later","error");},
    success: function(res)
    {      
      if(res.status){
        $("#chkintime").val(res.chkintime);
        $("#chkouttime").val(res.chkouttime);
        $("#upgname").val(res.upgname);
        $("#upphno").val(res.upphno);
        $("#upmail").val(res.upmail);
        $("#upaddress").val(res.upaddress);
        $("#upcity").val(res.upcity);
        $("#upzip").val(res.upzip);
        $("#upcountry").val(res.upcountry);
        $("#upadult").val(res.upadult);
        $("#upchild").val(res.upchild);
        $("#upnote").val(res.upnote);
      }
      else{showToast(res.msg,"error");}
    }
  }); 
});

$.validator.messages.required = '';
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/reservation-data";
    var data = new FormData(this);
    data.append("operation","updatebasic");
    data.append("id","<?php echo $data->res_id; ?>");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          location.reload();
          $("#updoc").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
</script>
</body>
</html>