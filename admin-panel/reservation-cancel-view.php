<?php include '_auth.php';
$id = (!empty($_GET['id']))?CleanInput(FilterInput($_GET['id'])):null; 
if (empty($id)) {include '404.php';die();}
$sql = "SELECT *,SUM(reservation_pay.pay_amount) AS totalpay FROM reservation_list
		LEFT JOIN reservation_pay ON reservation_pay.pay_res_id_ref  = reservation_list.res_id AND reservation_pay.pay_status=1
		LEFT JOIN users_list ON users_list.user_id = reservation_list.res_refund_by
		WHERE res_status =3 AND res_id ='$id' GROUP BY reservation_list.res_id";
$sql=$PDO->prepare($sql);
$sql->execute();
if ($sql->rowCount() != 1){include '404.php';die();}
$data = $sql->fetch(PDO::FETCH_OBJ);
/*$find_det = "SELECT * FROM order_details 
					LEFT JOIN product_list ON order_details.order_details_pro_id_ref = product_list.pro_id
					LEFT  JOIN vendor ON order_details.order_details_vendor_id = vendor.vendor_id
					WHERE order_details_order_id_ref='$id' AND order_details_status=1
					ORDER BY order_details_id DESC";
$find_det = $PDO->prepare($find_det);
$find_det->execute(); 
$alldet = $find_det->fetchAll();*/

$findrm ="SELECT * FROM reservation_rooms WHERE resrooms_res_id_ref = '$id' AND resrooms_status = 1 ";
$findrm = $PDO->prepare($findrm);
$findrm->execute();
$roomdet = $findrm->fetchAll();
$roomlist = null;
$roomlist   = '<ul class="list-inline faclist">';
$total =0;
foreach ($roomdet as $eachroom) {
    $name = $eachroom['resrooms_room_no'];
    $roomlist .='<li>'.$name.'</li>';

	$diff = abs(strtotime($eachroom['resrooms_out']) - strtotime($eachroom['resrooms_in']));
	$days = ($diff/(60*60*24));
	$total = $total + ($eachroom['resrooms_roomprice']*$days) + ($eachroom['resrooms_exbed_qty']*$eachroom['resrooms_extrabed_price']);

}
$roomlist.="</ul>";




$create 	  = new DateTime($data->res_create_at);
$cancel 	  = new DateTime($data->res_cancel_time);
$interval     = $cancel->diff($create);
$intervaltime = $interval->format('%m months %d days %H hours %i minutes');


$modeup = null;
if ($data->res_refund_way == '0') {
	$modeup ='cash';
}elseif ($data->res_refund_way == '1') {
	$modeup ='online';
}else{
	$modeup = null;
}

?> 
<!DOCTYPE html>
<html lang="en"> 
<head>  
<title>Details</title>
<?php include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{padding: 5px;background: #e2e2e2;margin: 2px;color: #fff;font-weight: 600;border-radius: 3px;background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #c17a9e 0%, #e260b4 33%, #ee609c 66%, #ee609c 100%);}
.includelist li{padding: 2px 8px;background: #2e7ba9;margin: 3px 2px;border-radius: 5px;color: #fff;font-weight: 600;}
.calbtn{margin-left:15px!important;width:100%!important;padding:10px 25px!important;}
.calbkpan{position:relative;margin-top:5px}.calbkpan input{height:42px;width:100%;padding-right:43px;padding-left:10px;}
.calbkpan button{position:absolute;right:0;top:0;background:#0b7bbd;outline:none;border:none;color:#fff;height:42px;width:40px;padding:0;border-radius:0px;}
.calbkpan button .fa{font-size:22px}
</style>
</head>  
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics mb-2">
<div class="card-title">
<div class="row">
<div class="col-sm-4">
<h5>Details</h5>
</div>
<div class="col-sm-4 text-center">
</div>
<div class="col-sm-4">
<a href="reservation-cancel" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Cancel List</a>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row mb-2">
<div class="col-md-6">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Address Details</h5>
</div>
<div class="card-body">
<div class="table-responsive">          
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<th>NAME</th>
<td><?php echo $data->res_g_name; ?></td>
</tr>
<tr>
<th>PHONE</th>
<td><?php echo $data->res_g_phone; ?></td>
</tr>
<tr>
<th>EMAIL</th>
<td><?php echo $data->res_g_email; ?></td>
</tr>
<tr>
<th>ADDRESS</th>
<td><?php echo $data->res_g_address; ?></td>
</tr>
<tr>
<th>CITY</th>
<td><?php echo $data->res_g_city; ?></td>
</tr>
<tr>
<th>PIN</th>
<td><?php echo $data->res_g_zipcode; ?></td>
</tr>
<tr>
<th>COUNTRY</th>
<td><?php echo $data->res_g_country; ?></td>
</tr>
<tr>
<th>ROOM LIST</th>
<td><?php echo $roomlist; ?></td>
</tr>
<tr>
<th>CANCEL TIME</th>
<td><?= date("d-M-y D H:i", strtotime($data->res_cancel_time)); ?> </td>
</tr>
<tr>
<th>BOOKING TIME</th>
<td><?= date("d-M-y D H:i", strtotime($data->res_create_at)); ?> </td>
</tr>
<tr>
<th>CANCEL WITHIN</th>
<td><?= $intervaltime; ?> </td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="card card-statistics">
<div class="card-title">
<h5>Payment Details</h5>
</div>
<div class="card-body">
<div class="table-responsive">          
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
</table>
</div>
</div>
</div>
<br>
<div class="card card-statistics">
<div class="card-title">
<h5>Refund Status <a href="reservation-cancel" data-toggle="modal" data-target="#upMod" data-amnt="<?= htmlspecialchars($data->res_refund_amount); ?>" data-way="<?= htmlspecialchars($modeup); ?>" data-tid="<?= htmlspecialchars($data->res_refund_transaction_id); ?>" data-note="<?= htmlspecialchars($data->res_refund_note); ?>" class="button x-small pull-right"><i class="fa fa-plus"></i> Update Refund</a></h5>
</div>
<div class="card-body">
<div class="table-responsive">          
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<th>AMOUNT</th>
<td><?= !empty($data->res_refund_amount)?$data->res_refund_amount:'-'; ?> </td>
</tr>
<tr>
<th>WAY</th>
<td>
<?php 
if ($data->res_refund_way=='0') {
	echo "Cash";
}elseif ($data->res_refund_way=='1'){
	echo "Online";
}else{echo "-";} ?>
</td>
</tr>
<tr>
<th>TRANSACTION ID</th>
<td><?= !empty($data->res_refund_transaction_id)?$data->res_refund_transaction_id:'-'; ?> </td>
</tr>
<tr>
<th>NOTE</th>
<td><?= !empty($data->res_refund_note)?$data->res_refund_note:'-'; ?> </td>
</tr>
<tr>
<th>BY</th>
<td><?= !empty($data->user_name)?$data->user_name:'-'; ?> </td>
</tr>
<tr>
<th>Time</th>
<td><?= !empty($data->res_refund_time)?date("d-M-y D H:i:s", strtotime($data->res_refund_time)):'-'; ?></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="upMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Refund</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="upfrm">
<div class="row">
<div class="form-group col-sm-12">
<label for="amount">Amount<span class="req">*</span></label>
<input type="text" class="form-control" id="amount" name="amount" required="">
</div>
<div class="form-group col-sm-12">
<label for="paymode">Payment Mode<span class="req">*</span></label>
<select class="form-control" id="paymode" name="paymode" required="">
<option value="">--Select Payment option--</option>
<option value="cash">CASH</option>
<option value="online">ONLINE</option>
</select>
</div>
<div class="form-group col-sm-12">
<label for="transactionid">Transaction ID</label>
<input type="text" class="form-control" id="transactionid" name="transactionid">
</div>
<div class="form-group col-sm-12">
<label for="transactionnote">Note</label>
<input type="text" class="form-control" id="transactionnote" name="transactionnote">
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Add Now</button>
</div>
</div> 
</form>
</div>
</div>
</div>
</div> 
<?php  include '_footer.php'; ?>
<script type="text/javascript">
$("#upMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$("#amount").val(button.data("amnt"));
$("#paymode").val(button.data("way"));
$("#transactionid").val(button.data("tid"));
$("#transactionnote").val(button.data("note"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/reservation-cancel-data";
    var data = new FormData(this);
    data.append("operation","updaterefund");
    data.append("resid","<?= $id; ?>");
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      contentType: false,
      cache: false,
      processData:false, 
      dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){location.reload();showToast(res.msg,"success");}
        else{showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));
</script>
</body>
</html