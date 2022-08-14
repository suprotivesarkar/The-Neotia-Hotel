<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
	echo $response = json_encode(array(
			"status" =>false,
			"msg"	 => "Unauthorized Access"
	));
	die(); 
}  
$operation =trim($_REQUEST['operation']);
if (empty($operation)){
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Something Wrong"
	));
	die();
}



 
if ($operation=="fetch"){
	//sleep(20);
	$customfilter = null;
	//echo json_encode($_POST);
	$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
	//die();
	if (!empty($sdate)) {
	  $sdt  = date("Y-m-d",strtotime($sdate));
	  $customfilter.=" AND DATE(res_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(res_create_at) <= '$edt' ";
	}

	$draw    = isset($_POST['draw']) ? intval($_POST['draw']) : 10;
	$start   = intval($_POST['start']);
	$length  = intval($_POST['length']);

	if($length == -1) $length = 9999999999;
	$order   = (!empty($_REQUEST['order']))?$_REQUEST['order']:null;
	$search  = trim($_POST['search']['value']);
	$sql     = $sqlRec = $sqlTot = $where = '';

	function sortOrder($column){
	    switch($column){
	        case 0:$column="res_id";break;
	        case 1:$column="res_g_name";break;
	        case 2:$column="res_g_phone";break;
	    }
	    return $column; 
	}


	$where .=" ( res_g_name LIKE '".$search."%' OR res_g_phone LIKE '".$search."%' OR res_id LIKE '".$search."%') ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT
    *, 
    SUM(reservation_pay.pay_amount) AS totalpay 
FROM
    reservation_list
LEFT JOIN reservation_pay ON reservation_pay.pay_res_id_ref  = reservation_list.res_id AND reservation_pay.pay_status=1
WHERE
    res_status = 0 ".$customfilter;

	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlTot .= " GROUP BY reservation_list.res_id ";
	$sqlRec .= " GROUP BY reservation_list.res_id ";

	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;

	 // echo $sqlRec;

	$sqlTot = $PDO->prepare($sqlTot);
	$sqlRec = $PDO->prepare($sqlRec);

	$sqlTot->execute();
	$sqlRec->execute();


if($sqlRec->rowCount()>0){
$i=$start+1;
while($row=$sqlRec->fetch()) {
	extract($row);	
	$findrm ="SELECT * FROM reservation_rooms WHERE resrooms_res_id_ref = '$res_id' AND resrooms_status = 1 ";
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
	
	$actionlink =null;
	$actionlink.='<td>';

	$actionlink.='<div class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
		<div class="dropdown-menu">
		<a href="reservation-view?id='.$res_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>';
 	// $actionlink.='<a href="" data-toggle="modal" data-target="#upMod" data-id="'.htmlspecialchars($user_id).'" data-catid="'.htmlspecialchars($user_cat_id_ref).'" data-code="'.htmlspecialchars($user_code).'" data-role="'.htmlspecialchars($user_role_id_ref).'"  data-password="'.htmlspecialchars($user_password).'" data-name="'.htmlspecialchars($user_name).'" data-phone="'.htmlspecialchars($user_phone).'" data-mail="'.htmlspecialchars($user_email).'" data-address="'.htmlspecialchars($user_address).'" data-birth="'.htmlspecialchars($user_dob).'" data-join="'.htmlspecialchars($user_join).'" data-salary="'.htmlspecialchars($user_salary).'"data-leave="'.htmlspecialchars($user_leaving).'" class="dropdown-item editbtn" title="Quick Update"><i class="fa fa-edit"></i> Quick Update</a>';

	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Info" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Info</a>';
	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Room" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Room</a>';
 	
 	if ($res_status==0) {
 		$actionlink.='<a href="javascript:void(0);" title="Confirm" class="dropdown-item text-success statusup" data-id="'.$res_id.'" data-operation="confirm"><i class="fa fa-check"></i> Confirm</a>';
 	}
 	// else if($res_status==1) {
 	// 	$actionlink.='<a href="javascript:void(0);" title="Make Dective" class="dropdown-item text-danger statusup" data-id="'.$res_id.'" data-operation="deactive"><i class="fa fa-lock"></i> Deactive</a>';
 	// }

 	$actionlink.='<div class="dropdown-divider"></div>';
 	$actionlink.='<a href="javascript:void(0);" class="dropdown-item statusup" title="Delete" data-id="'.htmlspecialchars($res_id).'" data-operation="deletep"><i class="fa fa-trash"></i> Cancel</a>';


	$actionlink.='</td></div></div>';


	$taxtot = $total + Tax($total);
      $subdata   = array();
      $subdata[] = $i++;   
      $subdata[] = $res_id;   
      $subdata[] = '<a href="reservation-view?id='.$res_id.'" title="View">'.'<b style = "color: #f60000">'.$res_g_name.'</b>'.'</a>';
      $subdata[] = $res_g_phone;
      $subdata[] = $roomlist;
      $subdata[] = '₹'.$taxtot;
      $subdata[] = $actionlink;
      $data[]    = $subdata;
  }
  $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval($sqlTot->rowCount()),  
      "recordsFiltered" => intval($sqlTot->rowCount()),
      "data"            => $data
    );
    echo json_encode($json_data);
  }else{
    $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval(0),  
      "recordsFiltered" => intval(0),
      "data"            => ""
    );
    echo json_encode($json_data);
  }
}

elseif ($operation=="fetchcon"){
	//sleep(20);
	$customfilter = null;
	//echo json_encode($_POST);
	$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
	//die();
	if (!empty($sdate)) {
	  $sdt  = date("Y-m-d",strtotime($sdate));
	  $customfilter.=" AND DATE(res_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(res_create_at) <= '$edt' ";
	}

	$draw    = isset($_POST['draw']) ? intval($_POST['draw']) : 10;
	$start   = intval($_POST['start']);
	$length  = intval($_POST['length']);

	if($length == -1) $length = 9999999999;
	$order   = (!empty($_REQUEST['order']))?$_REQUEST['order']:null;
	$search  = trim($_POST['search']['value']);
	$sql     = $sqlRec = $sqlTot = $where = '';

	function sortOrder($column){
	    switch($column){
	        case 0:$column="res_id";break;
	        case 1:$column="res_g_name";break;
	        case 2:$column="res_g_phone";break;
	    }
	    return $column; 
	}


	$where .=" ( res_g_name LIKE '".$search."%' OR res_g_phone LIKE '".$search."%' OR res_id LIKE '".$search."%') ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT
    *, 
    SUM(reservation_pay.pay_amount) AS totalpay 
    FROM
    reservation_list
    LEFT JOIN reservation_pay ON reservation_pay.pay_res_id_ref  = reservation_list.res_id AND reservation_pay.pay_status=1
    WHERE
    res_status = 1 ".$customfilter;

	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlTot .= " GROUP BY reservation_list.res_id ";
	$sqlRec .= " GROUP BY reservation_list.res_id ";

	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;


	// echo $sqlRec;

	$sqlTot = $PDO->prepare($sqlTot);
	$sqlRec = $PDO->prepare($sqlRec);

	$sqlTot->execute();
	$sqlRec->execute();


if($sqlRec->rowCount()>0){
$i=$start+1;
while($row=$sqlRec->fetch()) {
	extract($row);	

	$findrm ="SELECT * FROM reservation_rooms WHERE resrooms_res_id_ref = '$res_id' AND resrooms_status = 1 ";
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
	
	$actionlink =null;
	$actionlink.='<td>';


	$actionlink =null;
	$actionlink.='<td>';

	$actionlink.='<div class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
		<div class="dropdown-menu">
		<a href="reservation-view?id='.$res_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>
		<a href="print-bill?id='.$res_id.'" title="Receipt" class="dropdown-item text-primary" target="/"><i class="fa fa-file-text"></i> Receipt</a>';
 	// $actionlink.='<a href="" data-toggle="modal" data-target="#upMod" data-id="'.htmlspecialchars($user_id).'" data-catid="'.htmlspecialchars($user_cat_id_ref).'" data-code="'.htmlspecialchars($user_code).'" data-role="'.htmlspecialchars($user_role_id_ref).'"  data-password="'.htmlspecialchars($user_password).'" data-name="'.htmlspecialchars($user_name).'" data-phone="'.htmlspecialchars($user_phone).'" data-mail="'.htmlspecialchars($user_email).'" data-address="'.htmlspecialchars($user_address).'" data-birth="'.htmlspecialchars($user_dob).'" data-join="'.htmlspecialchars($user_join).'" data-salary="'.htmlspecialchars($user_salary).'"data-leave="'.htmlspecialchars($user_leaving).'" class="dropdown-item editbtn" title="Quick Update"><i class="fa fa-edit"></i> Quick Update</a>';

	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Info" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Info</a>';
	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Room" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Room</a>';
	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Payment" class="dropdown-item text-dark"><i class="fa fa-plus"></i> Update Payment</a>';
 	
 	if ($res_status==1) {
 		$actionlink.='<a href="javascript:void(0);" title="Check Out" class="dropdown-item text-success statusup" data-id="'.$res_id.'" data-operation="checkout"><i class="fa fa-check"></i> Check Out</a>';
 	}
 	// else if($res_status==1) {
 	// 	$actionlink.='<a href="javascript:void(0);" title="Make Dective" class="dropdown-item text-danger statusup" data-id="'.$res_id.'" data-operation="deactive"><i class="fa fa-lock"></i> Deactive</a>';
 	// }

 	$actionlink.='<div class="dropdown-divider"></div>';
 	$actionlink.='<a href="javascript:void(0);" class="dropdown-item statusup" title="Delete" data-id="'.htmlspecialchars($res_id).'" data-operation="deletec"><i class="fa fa-trash"></i> Delete</a>';


	$actionlink.='</td></div></div>';

	$taxtot = $total + Tax($total);
	$due = $taxtot - $totalpay;

      $subdata   = array();
      $subdata[] = $i++;   
      $subdata[] = $res_id;  
      $subdata[] = '<a href="reservation-view?id='.$res_id.'" title="View">'.'<b style = "color: #045d04">'.$res_g_name.'</b>'.'</a>';
      $subdata[] = $res_g_phone;
      $subdata[] = $roomlist;
      $subdata[] = '₹'.$taxtot;
      $subdata[] = '₹'.$totalpay;
      $subdata[] = '₹'.$due;
      $subdata[] = $actionlink;
      $data[]    = $subdata;
  }
  $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval($sqlTot->rowCount()),  
      "recordsFiltered" => intval($sqlTot->rowCount()),
      "data"            => $data
    );
    echo json_encode($json_data);
  }else{
    $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval(0),  
      "recordsFiltered" => intval(0),
      "data"            => ""
    );
    echo json_encode($json_data);
  }
}
elseif ($operation=="fetchout"){
	//sleep(20);
	$customfilter = null;
	//echo json_encode($_POST);
	$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
	//die();
	if (!empty($sdate)) {
	  $sdt  = date("Y-m-d",strtotime($sdate));
	  $customfilter.=" AND DATE(res_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(res_create_at) <= '$edt' ";
	}

	$draw    = isset($_POST['draw']) ? intval($_POST['draw']) : 10;
	$start   = intval($_POST['start']);
	$length  = intval($_POST['length']);

	if($length == -1) $length = 9999999999;
	$order   = (!empty($_REQUEST['order']))?$_REQUEST['order']:null;
	$search  = trim($_POST['search']['value']);
	$sql     = $sqlRec = $sqlTot = $where = '';

	function sortOrder($column){
	    switch($column){
	        case 0:$column="res_id";break;
	        case 1:$column="res_g_name";break;
	        case 2:$column="res_g_phone";break;
	    }
	    return $column; 
	}


	$where .=" ( res_g_name LIKE '".$search."%' OR res_g_phone LIKE '".$search."%' OR res_id LIKE '".$search."%') ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT
    *, 
    SUM(reservation_pay.pay_amount) AS totalpay 
    FROM
    reservation_list
    LEFT JOIN reservation_pay ON reservation_pay.pay_res_id_ref  = reservation_list.res_id AND reservation_pay.pay_status=1
    WHERE
    res_status = 2 ".$customfilter;

	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlTot .= " GROUP BY reservation_list.res_id ";
	$sqlRec .= " GROUP BY reservation_list.res_id ";

	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;


	// echo $sqlRec;

	$sqlTot = $PDO->prepare($sqlTot);
	$sqlRec = $PDO->prepare($sqlRec);

	$sqlTot->execute();
	$sqlRec->execute();


if($sqlRec->rowCount()>0){
$i=$start+1;
while($row=$sqlRec->fetch()) {
	extract($row);	
$findrm ="SELECT * FROM reservation_rooms WHERE resrooms_res_id_ref = '$res_id' AND resrooms_status = 1 ";
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
	




	$actionlink =null;
	$actionlink.='<td>';

	$actionlink.='<div class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
		<div class="dropdown-menu">
		<a href="reservation-view?id='.$res_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>
		<a href="print-bill?id='.$res_id.'" title="Receipt" class="dropdown-item text-primary" target="/"><i class="fa fa-file-text"></i> Receipt</a>';
 	// $actionlink.='<a href="" data-toggle="modal" data-target="#upMod" data-id="'.htmlspecialchars($user_id).'" data-catid="'.htmlspecialchars($user_cat_id_ref).'" data-code="'.htmlspecialchars($user_code).'" data-role="'.htmlspecialchars($user_role_id_ref).'"  data-password="'.htmlspecialchars($user_password).'" data-name="'.htmlspecialchars($user_name).'" data-phone="'.htmlspecialchars($user_phone).'" data-mail="'.htmlspecialchars($user_email).'" data-address="'.htmlspecialchars($user_address).'" data-birth="'.htmlspecialchars($user_dob).'" data-join="'.htmlspecialchars($user_join).'" data-salary="'.htmlspecialchars($user_salary).'"data-leave="'.htmlspecialchars($user_leaving).'" class="dropdown-item editbtn" title="Quick Update"><i class="fa fa-edit"></i> Quick Update</a>';

	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Info" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Info</a>';
	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Room" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update Room</a>';
	// $actionlink.='<a href="product-update?id='.$res_id.'" title="Update Payment" class="dropdown-item text-dark"><i class="fa fa-plus"></i> Update Payment</a>';
 	

 	$actionlink.='<div class="dropdown-divider"></div>';
 	$actionlink.='<a href="javascript:void(0);" class="dropdown-item statusup" title="Delete" data-id="'.htmlspecialchars($res_id).'" data-operation="deletechk"><i class="fa fa-trash"></i> Delete</a>';


	$actionlink.='</td></div></div>';



	$taxtot = $total + Tax($total);
	$due = $taxtot - $totalpay;

      $subdata   = array();
      $subdata[] = $i++;   
      $subdata[] = $res_id;  
      $subdata[] = '<a href="reservation-view?id='.$res_id.'" title="View">'.'<b style = "color: #5e03a2f5">'.$res_g_name.'</b>'.'</a>';
      $subdata[] = $res_g_phone;
      $subdata[] = $roomlist;
      $subdata[] = '₹'.$taxtot;
      $subdata[] = '₹'.$totalpay;
      $subdata[] = '₹'.$due;
      $subdata[] = $actionlink;
      $data[]    = $subdata;
  }
  $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval($sqlTot->rowCount()),  
      "recordsFiltered" => intval($sqlTot->rowCount()),
      "data"            => $data
    );
    echo json_encode($json_data);
  }else{
    $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval(0),  
      "recordsFiltered" => intval(0),
      "data"            => ""
    );
    echo json_encode($json_data);
  }
}
elseif ($operation=="fetchpay") {
$id    = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
	$stmt = $PDO->prepare("SELECT * FROM reservation_pay 
		LEFT JOIN users_list on users_list.user_id = reservation_pay.pay_by
		WHERE pay_res_id_ref ='$id' AND pay_status<>2 ORDER BY pay_id DESC ");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>PAYMENT CREATE DATE</th>
	<th>PAYMENT UPDATE DATE</th>
	<th>PAYMENT AMOUNT</th>
	<th>PAYMENT MODE</th>
	<th>TRANSACTION ID</th>
	<th>PAYMENT RECIEVED</th>
    <th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	$sum=0;
	$ptype = null;
	while ($row=$stmt->fetch()){
	extract($row);
	$sum = $sum + $pay_amount;

	if($pay_type==1){$ptype="ONLINE";}else{$ptype="OFFLINE";}
	?>
	<tr>
	<td><?php echo $i++; ?></td>
	<td><?php echo(date ("d-M-Y",strtotime("$pay_create_at")));  ?></td>
	<td><?php echo(date ("d-M-Y",strtotime("$pay_update_at")));  ?></td>
	<td>₹ <?php echo $pay_amount; ?></td>
	<td><?php echo $ptype; ?></td>
	<td><?php echo $pay_transaction; ?></td>
	<td><?php echo $user_name; ?></td>
	<td>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($pay_id); ?>" data-amount="<?php echo htmlspecialchars($pay_amount); ?>"  class="editbtn" title="Update"><i class="fa fa-edit"></i></a> ||
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($pay_id); ?>" data-operation="deletepay"><i class="fa fa-trash"></i></a>
	</td>
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

		<td colspan="4" style="background-color: #055a15;color: #ffffff;">Total Amount: ₹ <?=  $total; ?> + ₹<?php echo Tax($total); ?>(<?php echo $tax; ?>% gst) = ₹ <?php echo $tottax; ?></td>
		<td colspan="2" style="background-color: #04046d;color: #ffffff;">Amount Paid: ₹ <?=  $sum; ?></td>
		<td colspan="2" style="background-color: #f32520;color: #ffffff;">Due:₹ <?=  $tottax - $sum; ?></td>
		
	</tr>

	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Payement Data Found</p></div>'; }
}


elseif ($operation=="addpay") {

  $id      = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
  $amount  = (!empty($_POST['amount']))?FilterInput($_POST['amount']):null; 

    if (empty($id) OR !is_numeric($id)) {
      echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"Data Not Found"
        ));
      die();
    }
    if (empty($amount) OR !is_numeric($amount)) {
      echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"Please Enter Amount"
        ));
      die();
    }

  //   $chk_pro = CheckExists("reservation_pay","pay_res_id_ref = '$id' AND pay_status<>2");
  // if (empty($chk_pro)) {
  //   echo $response = json_encode(array(
  //       "status" => false,
  //       "msg"  => "Cant Find this Entry"
  //   ));
  //   die();
  // }

  $sql = "INSERT INTO reservation_pay SET
  		  pay_by             = :pay_by,
          pay_res_id_ref = :pay_res_id_ref,
          pay_amount     = :pay_amount";
          $insert   = $PDO->prepare($sql);
          $insert->bindParam(':pay_by',$_SESSION['adminid']);
          $insert->bindParam(':pay_res_id_ref',$id);
          $insert->bindParam(':pay_amount',$amount);
          $insert->execute();
          if($insert->rowCount() > 0){
            echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Added"
        ));
          }else {
            echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"No Changes Done"
        ));
      }
}

elseif ($operation=="updatepay") {
	$uptid     = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$upamount   = (!empty($_POST['upamount']))?FilterInput($_POST['upamount']):null;  

	if(empty($uptid) OR empty($upamount) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("reservation_pay","pay_id = '$uptid' AND pay_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}		
	$sql = "UPDATE reservation_pay SET
				pay_by             = :pay_by,
				pay_update_at      = :pay_update_at,
		        pay_amount         = :pay_amount
	            WHERE pay_id=:pay_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':pay_by',$_SESSION['adminid']);
		        $insert->bindParam(':pay_update_at',$nowTime);
		        $insert->bindParam(':pay_amount',$upamount);
	            $insert->bindParam(':pay_id',$uptid);
		        $insert->execute();
	            if($insert->rowCount() > 0){
	            	echo $response = json_encode(array(
						"status" =>true, 
						"msg"	 => "Successfully Updated"
					));
	            }else {
	            	echo $response = json_encode(array(
						"status" =>false,
						"msg"	 =>"No Change Done"
					));
	   			}
}

elseif ($operation=="fetchrom") {
$id    = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
	$stmt = $PDO->prepare("SELECT * FROM reservation_rooms
		WHERE resrooms_res_id_ref ='$id' AND resrooms_status<>2 ORDER BY resrooms_id DESC ");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>CHECK-IN</th>
	<th>CHECK-OUT</th>
	<th>ROOM-TYPE</th>
	<th>ROOM NO.</th>
	<th>ROOM PRICE</th>
	<th>BED PRICE</th>
	<th>BED QTY</th>
    <th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	$sum=0;
	while ($row=$stmt->fetch()){
	extract($row);
	?>
	<tr>
	<td><?php echo $i++; ?></td>
	<td><?php echo(date ("d-M-Y",strtotime("$resrooms_in")));  ?></td>
	<td><?php echo(date ("d-M-Y",strtotime("$resrooms_out")));  ?></td>
	<td><?php echo $resrooms_roomtype; ?></td>
	<td><?php echo $resrooms_room_no; ?></td>
	<td>₹ <?php echo $resrooms_roomprice; ?></td>
	<td>₹ <?php echo $resrooms_extrabed_price; ?></td>
	<td><?php echo $resrooms_exbed_qty; ?></td>
	<td>
<!-- <a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($resrooms_id); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || -->
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($resrooms_id); ?>" data-operation="deleterom"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Room Data Found</p></div>'; }
}


elseif ($operation=='fetchbasic') {
	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	$chk_id = CheckExists("reservation_list","res_id = '$id' AND res_status<>3");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Data"
		));
		die();
	}
	echo $response = json_encode(array(
			"status"        => true,
			"chkintime"	    => $chk_id->res_g_intime,
			"chkouttime"    => $chk_id->res_g_outtime,
        	"upgname"		=> $chk_id->res_g_name,
        	"upphno"		=> $chk_id->res_g_phone,
        	"upmail"		=> $chk_id->res_g_email,
        	"upaddress"	    => $chk_id->res_g_address,
        	"upcity"		=> $chk_id->res_g_city,
        	"upzip"		    => $chk_id->res_g_zipcode,
        	"upcountry"  	=> $chk_id->res_g_country,
        	"upadult"		=> $chk_id->res_g_adult,
        	"upchild"		=> $chk_id->res_g_child,
        	"upnote"		=> $chk_id->res_g_note
	));
}

elseif ($operation=="updatebasic") {
	$id            = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
	$chkintime     = (!empty($_POST['chkintime']))?FilterInput($_POST['chkintime']):null;
	$chkouttime    = (!empty($_POST['chkouttime']))?FilterInput($_POST['chkouttime']):null;
	$upgname       = (!empty($_POST['upgname']))?FilterInput($_POST['upgname']):null;
	$upphno        = (!empty($_POST['upphno']))?FilterInput($_POST['upphno']):null;
	$upmail        = (!empty($_POST['upmail']))?FilterInput($_POST['upmail']):null;
	$upaddress     = (!empty($_POST['upaddress']))?FilterInput($_POST['upaddress']):null;
	$upcity        = (!empty($_POST['upcity']))?FilterInput($_POST['upcity']):null;
	$upzip         = (!empty($_POST['upzip']))?FilterInput($_POST['upzip']):null;
	$upcountry     = (!empty($_POST['upcountry']))?FilterInput($_POST['upcountry']):null;
	$upadult       = (!empty($_POST['upadult']))?FilterInput($_POST['upadult']):null;
	$upchild       = (!empty($_POST['upchild']))?FilterInput($_POST['upchild']):null;
	$upnote        = (!empty($_POST['upnote']))?FilterInput($_POST['upnote']):null;

	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	$chk_exists = CheckExists("reservation_list","res_id='$id' AND res_status<>2");
	if (empty($chk_exists)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	    if(empty($upgname) OR empty($upphno)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter Name and Phone No."
        ));
        die();
    } 

    if(empty($upadult)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter No. of Adults"
        ));
        die();
    } 

    if(empty($upaddress)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter Address"
        ));
        die();
    } 

$FileName = $chk_exists->res_g_doc;
if(!empty($_FILES['updoc']['name'])){

        $valid_ext   = array('jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx');
        $mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $maxsize     = 10 * 1024 * 1024;

        $FileName    = FilterInput($_FILES['updoc']['name']);
        $tmpName     = $_FILES['updoc']['tmp_name'];
        $FileTyp     = $_FILES['updoc']['type'];
        $FileSize    = $_FILES['updoc']['size'];
        $MimeType    = mime_content_type($_FILES['updoc']['tmp_name']);

        $ext      = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
        $FileName = basename(strtolower($FileName),".".$ext);
        $FileName = FileName($upgname).'_'.time().rand(10000,999999999).'.'.$ext;

        if(!in_array($ext, $valid_ext)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Extention Not Allowed"
        ));
        die();
        }
        if($FileSize>$maxsize){
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"Max file Size: 10MB"
        ));
        die();
        }
        if(!in_array($FileTyp, $mime_filter)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Not Supported"
        ));
        die();
        }
        if(!in_array($MimeType, $mime_filter)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Not Supported"
        ));
        die();
        }

        $path = "../../../images/guestdoc/".$FileName;
        if (!move_uploaded_file($_FILES["updoc"]["tmp_name"],$path)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"Cant Upload File"
        ));
        die();
        }
        chmod($path,0644);
        if (!empty($chk_exists->res_g_doc) AND file_exists("../../../images/guestdoc/".$chk_exists->res_g_doc)){
	      @unlink("../../../images/guestdoc/".$chk_exists->res_g_doc);
	    }

}


   
      $sql = "UPDATE reservation_list SET
          res_g_intime         =:res_g_intime,
          res_g_outtime        =:res_g_outtime,
          res_g_name           =:res_g_name,
          res_g_phone          =:res_g_phone,
          res_g_email          =:res_g_email,
          res_g_address        =:res_g_address,
          res_g_city           =:res_g_city,
          res_g_zipcode        =:res_g_zipcode,
          res_g_country        =:res_g_country,
          res_g_adult          =:res_g_adult,
          res_g_child          =:res_g_child,
          res_g_doc            =:res_g_doc,
          res_g_note           =:res_g_note
          WHERE res_id=:res_id";
          $insert   = $PDO->prepare($sql);
          $insert->bindParam(':res_g_intime',$chkintime);
          $insert->bindParam(':res_g_outtime',$chkouttime);
          $insert->bindParam(':res_g_name',$upgname);
          $insert->bindParam(':res_g_phone',$upphno);
          $insert->bindParam(':res_g_email',$upmail);
          $insert->bindParam(':res_g_address',$upaddress);
          $insert->bindParam(':res_g_city',$upcity);
          $insert->bindParam(':res_g_zipcode',$upzip);
          $insert->bindParam(':res_g_country',$upcountry);
          $insert->bindParam(':res_g_adult',$upadult);
          $insert->bindParam(':res_g_child',$upchild);
          $insert->bindParam(':res_g_doc',$FileName);
          $insert->bindParam(':res_g_note',$upnote);
          $insert->bindParam(':res_id',$id);
          $insert->execute();
          if($insert->rowCount() > 0){
            echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Updated"
        ));
          }else {
            echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"No Changes Done"
        ));
      }


}

if ($operation=="fetcrooms") {
    // $stmt = "SELECT * FROM roomnum WHERE num_room_id_ref ='$bid' AND num_id NOT IN (
        
    //     SELECT reservation.res_num_id_ref FROM reservation WHERE 
       
    //     (reservation.res_out >'$indate' AND reservation.res_out <='$outdate') OR 
    //     (reservation.res_in >='$indate' AND reservation.res_in <'$outdate')
    // )";

    $resid=(!empty($_POST['resid']))?FilterInput($_POST['resid']):null;
    $bid=(!empty($_POST['cat']))?FilterInput($_POST['cat']):null;
    $indate=(!empty($_POST['indate']))?FilterInput($_POST['indate']):null;
    $outdate=(!empty($_POST['outdate']))?FilterInput($_POST['outdate']):null;
   if(empty($indate)){
    echo "Date Range is wrong!";
    die();
   }
   if(empty($bid)){
    echo '<div class="col-12"><div class="alert alert-danger"><strong>Select Room Category!</strong></div></div>';
    die();
   }
   if(empty($outdate) OR ($indate==$outdate)){
    $outdate = date("Y-m-d",strtotime($indate. ' + 1 days'));
   }

    if (!empty($indate)) {
      $indate  = date("Y-m-d",strtotime($indate));
    }
    if (!empty($outdate)) {
      $outdate  = date("Y-m-d",strtotime($outdate));
    }
    if($indate>$outdate){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date Range is wrong!</strong></div></div>';
         die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date is before Today!</strong></div></div>';
         die();
    }
    

    $chk_model= CheckExists("room_category","roomcat_id = '$bid' AND roomcat_status=1");
    if (empty($chk_model)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Room Type Not Found"
        ));
        die();
    }

    $stmt = "SELECT * FROM room_list WHERE room_roomcat_id_ref ='$bid' AND room_status = 1 AND room_id NOT IN (
        
        SELECT reservation_rooms.resrooms_room_id_ref FROM reservation_rooms 
        INNER JOIN reservation_list on reservation_rooms.resrooms_res_id_ref = reservation_list.res_id AND res_status = 1 WHERE 
        (reservation_rooms.resrooms_out >'$indate' AND reservation_rooms.resrooms_out <='$outdate') OR 
        (reservation_rooms.resrooms_in >='$indate' AND reservation_rooms.resrooms_in <'$outdate') 
    )";
    $stmt= $PDO->prepare($stmt);
    $stmt->execute(); 
    if($stmt->rowCount()>0){ 
        $k=1;
        while ($row=$stmt->fetch()){ 
        	
        	$currid= $row['room_id'];
      	
       $findrm ="SELECT * FROM reservation_rooms WHERE resrooms_res_id_ref = '$resid' AND resrooms_status = 1 AND resrooms_room_id_ref = '$currid' ";
				$findrm = $PDO->prepare($findrm);
				$findrm->execute();
				$roomdet = $findrm->fetch();
				if(empty($roomdet)){


        	?>
            <div class="custom-control custom-checkbox mb-30 form-group col-sm-2">
            <input type="checkbox" class="custom-control-input listcheckbox" name="roomids[]" value="<?php echo $row['room_id']; ?>" id="catid<?php echo $k; ?>">
            <label class="custom-control-label" for="catid<?php echo $k; ?>">Room <?php echo $row['room_no']; ?></label>
            </div>
            <?php 
            $k++; }
        }
        echo '<div class=" col-12"><a href="javascript:void(0);" id="addtoroom" class="button x-small pull-right"><i class="fa fa-plus"> Add Rooms</i></a></h5></div>';
    }else{
        echo  '<div class="col-12"><div class="alert alert-danger"><strong>Sorry No Rooms Available!</strong></div></div>';
    }



}

elseif ($operation == 'addtoroom') {
// unset($_SESSION['remember']);
// die();
$indate=(!empty($_POST['indate']))?FilterInput($_POST['indate']):null;
$outdate=(!empty($_POST['outdate']))?FilterInput($_POST['outdate']):null;
$arr  = (!empty($_POST['arr']))?$_POST['arr']:NULL; 
$eqty  = (!empty($_POST['eqty']))?FilterInput($_POST['eqty']):0; 

    if (empty($arr)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Select Rooms"
        ));
        die();
    }
    if(empty($outdate) OR ($indate==$outdate)){
    $outdate = date("Y-m-d",strtotime($indate. ' + 1 days'));
   }

    if (!empty($indate)) {
      $indate  = date("Y-m-d",strtotime($indate));
    }
    if (!empty($outdate)) {
      $outdate  = date("Y-m-d",strtotime($outdate));
    }
    if($indate>$outdate){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date Range is wrong!</strong></div></div>';
         die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date is before Today!</strong></div></div>';
         die();
    }


// $listarr = explode(",",$arr);
foreach ($arr as $eachlist) {
    $reg_list = CheckExists("room_list","room_id  = '$eachlist' AND room_status<>2");
    if (!empty($reg_list)) {
        
            if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
                $_SESSION['remember'] = array(
                    array(
                        'room_id'   => $eachlist,
                        'in_date'   => $indate,
                        'out_date'   => $outdate,
                        'extrabedqty' => $eqty,
                        'time' => Date('Y-m-d H:i:s')
                    ),
                );
            }else{
                $is_exists = 1;
                foreach ($_SESSION['remember'] as $key => $val) {
                    if ($val['room_id'] == $eachlist) {
                        $is_exists = 2;
                    
                        $_SESSION['remember'][$key]["in_date"] = $indate;
                        $_SESSION['remember'][$key]["out_date"] = $outdate;
                        $_SESSION['remember'][$key]["extrabedqty"] = $eqty;
                        $_SESSION['remember'][$key]["time"] = Date('Y-m-d H:i:s');
                    }else{
                        $is_exists = 1;
                    }
                    if ($is_exists==2) {break;}
                }
                if ($is_exists==1) {
                    $new = array(
                            'in_date'   => $indate,
                            'out_date'   => $outdate,
                            'room_id'   => $eachlist,
                            'extrabedqty'  => $eqty,
                            'time' => Date('Y-m-d H:i:s')
                        );
                    array_push($_SESSION['remember'], $new);
                }
            }


    }
}

  // print_r($_SESSION['remember']);
    echo $response = json_encode(array(
            "status" => true,
            "msg"    => "Success"
    ));
    die();
}

elseif ($operation=="fetchrooms") {
    

   if(isset($_SESSION['remember']) AND (!empty($_SESSION['remember'])) ) {  ?>
        <div class="card card-statistics">
        <div class="card-title eachhigh">
        <h5>Seleted Rooms:</h5>
        </div>
        <div class="card-body">
        <div class="table-responsive">          
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
        <th>#</th>
        <th>Room Type</th>
        <th>Room No.</th>
        <th>Room Price</th>
        <th>Days</th>
        <th>Extra Bed</th>
        <th>Total</th>
        <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
    <?php
        $sum = 0;
        $i=1;
        foreach ($_SESSION['remember'] as $key => $val) {
            $curid   = $val['room_id'];
            $indate   = $val['in_date'];
            $outdate   = $val['out_date'];
            $diff = abs(strtotime($outdate) - strtotime($indate));
            $days = ($diff/(60*60*24));

            $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
            $profind->execute(); 
            $chk_pro=$profind->fetch();

            if (!empty($chk_pro)) {   
            $sum = $sum + ($chk_pro['roomcat_price']*$days) + ($val['extrabedqty']*$chk_pro['roomcat_extrabed']);
            ?>      
<tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $chk_pro['roomcat_name']; ?></td>
        <td><?php  echo $chk_pro['room_no']; ?></td>
        <td>₹ <?php  echo $chk_pro['roomcat_price']; ?></td>
        <td><?php  echo $days; ?></td>
        <td>
     <div class="counter-add-item"> <a class="decrease-btn qtyupdatebtn" data-id="<?= $chk_pro['room_id'];  ?>" data-action="1" href="javascript:;">-</a> 
    <input type="text" value="<?php echo  $val['extrabedqty']; ?>"> <a class="increase-btn qtyupdatebtn" data-id="<?= $chk_pro['room_id'];  ?>" data-action="2" href="javascript:;">+</a>
    </div>
    <p>Extrabed: (₹ <?php echo $chk_pro['roomcat_extrabed']; ?>) x (<?php echo $val['extrabedqty']; ?>) = <?php echo $chk_pro['roomcat_extrabed']*$val['extrabedqty']; ?> </p>
    </td>
            <td>₹ <?php echo ($chk_pro['roomcat_price']*$days)+($chk_pro['roomcat_extrabed']*$val['extrabedqty']); ?></td>
            <td><a class="item-delete delbtn" href="javascript:;" data-id='<?php echo $curid; ?>' title="Remove From Table"><i class="fa fa-trash-o"></i></a></td>
      </tr>

            <?php
            }
        } ?>
<tr>
<td colspan="5"></td>

<td class="grandtotal" style="background: #031b5f; color: white; font-size: 16px;">Total = <span id="totalsum">₹ <?php echo $sum; ?></span></td>
<td colspan="2"></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

<?php

    }
    else{

        echo '';
    }



}
elseif ($operation=="deleteroom") {
    $id = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
    if(empty($id)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    if(!filter_var($id, FILTER_VALIDATE_INT)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }


        if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
            echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
            ));
            die();
        }else{
            foreach ($_SESSION['remember'] as $key => $val) {
                if ($val['room_id'] == $id) {
                    unset($_SESSION['remember'][$key]);
                    echo $response = json_encode(array(
                        "status" => true,
                        "msg"    => "Success"
                    ));
                    die();
                }
            }
        }


    echo $response = json_encode(array(
                                "status" => false,
                                "msg"    => "Failed"
                            ));

}

  elseif ($operation=="quantityupdate") {
    $id     = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
    $action = (!empty($_POST['action']))?FilterInput($_POST['action']):null;
    if(empty($id) OR empty($action)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    if(!filter_var($id, FILTER_VALIDATE_INT)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    $actionarr = array(1,2);
    if (!in_array($action, $actionarr)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }


        if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
            echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
            ));
            die();
        }else{
            foreach ($_SESSION['remember'] as $key => $val) {
                if ($val['room_id'] == $id) {

                    if ($action==1) {
                        $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"] - 1;
                    }else{
                        $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"] + 1;
                    }
                    if ($_SESSION['remember'][$key]["extrabedqty"]<0) {
                        $_SESSION['remember'][$key]["extrabedqty"] = 0;
                    }
                    $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"];
                    $_SESSION['remember'][$key]["time"] = Date('Y-m-d H:i:s');
                    echo $response = json_encode(array(
                        "status" => true,
                        "msg"    => "Success"
                    ));
                    die();
                }
            }
        }

}

elseif ($operation=="addroomup") {
$id      = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
$chkin         = (!empty($_POST['chkin']))?FilterInput($_POST['chkin']):null; 
$chkout        = (!empty($_POST['chkout']))?FilterInput($_POST['chkout']):null;  
if (empty($id) OR !is_numeric($id)) {
      echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"Data Not Found"
        ));
      die();
    }
// $chk_pro = CheckExists("reservation_rooms","resrooms_res_id_ref = '$id' AND resrooms_status<>2");
//   if (empty($chk_pro)) {
//     echo $response = json_encode(array(
//         "status" => false,
//         "msg"  => "Cant Find this Entry"
//     ));
//     die();
//   }
if(empty($chkout) OR ($chkin==$chkout)){
$chkout = date("Y-m-d",strtotime($chkin. ' + 1 days'));
}

if (!empty($chkin)) {
  $chkin  = date("Y-m-d",strtotime($chkin));
}
if (!empty($chkout)) {
  $chkout  = date("Y-m-d",strtotime($chkout));
}
if($chkin>$chkout){
    echo 'Date Range is wrong!';
     die();
}
$today = Date('Y-m-d');
if($chkin<$today OR $chkout<$today){
    echo 'Date is before Today!';
     die();
}

	 foreach ($_SESSION['remember'] as $key => $val) {
	    $curid   = $val['room_id'];
	    $indate   = $val['in_date'];
	    $outdate   = $val['out_date'];
	    $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
	$profind->execute(); 
	$chk_pro=$profind->fetch();
	    if (!empty($chk_pro)) {

	$sql = "INSERT INTO reservation_rooms SET
	resrooms_res_id_ref    = :resrooms_res_id_ref,
	resrooms_room_id_ref   = :resrooms_room_id_ref,
	resrooms_roomtype      = :resrooms_roomtype,
	resrooms_room_no       = :resrooms_room_no,
	resrooms_roomprice     = :resrooms_roomprice,
	resrooms_exbed_qty     = :resrooms_exbed_qty,
	resrooms_extrabed_price = :resrooms_extrabed_price,
	resrooms_in            = :resrooms_in,
	resrooms_out           = :resrooms_out";
	$insert = $PDO->prepare($sql);
	$insert->bindParam(':resrooms_res_id_ref',$id);
	$insert->bindParam(':resrooms_room_id_ref',$curid);
	$insert->bindParam(':resrooms_roomtype',$chk_pro['roomcat_name']);
	$insert->bindParam(':resrooms_room_no',$chk_pro['room_no']);
	$insert->bindParam(':resrooms_roomprice',$chk_pro['roomcat_price']);
	$insert->bindParam(':resrooms_exbed_qty',$val['extrabedqty']);
	$insert->bindParam(':resrooms_extrabed_price',$chk_pro['roomcat_extrabed']);
	$insert->bindParam(':resrooms_in',$indate);
	$insert->bindParam(':resrooms_out',$outdate);
	$insert->execute();
	                if($insert->rowCount() > 0){
                echo $response = json_encode(array(
                    "status" =>true,
                    "msg"    =>"Successfully Added"
                ));
                unset($_SESSION['remember']);
                die();
            }else {
                echo $response = json_encode(array(
                    "status" =>false,
                    "msg"    =>"Something Wrong"
                ));
                die();
            }
	    }
    }

}

elseif ($operation=="confirm" OR $operation=="deletep" OR $operation=="deletec" OR $operation=="checkout" OR $operation=="deletechk") {

	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	$chk_id = CheckExists("reservation_list","res_id = '$id' AND res_status<>3");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Data"
		));
		die();
	}
	switch ($operation) {
		case 'confirm':
			$sql = "UPDATE reservation_list SET res_status=1 WHERE res_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Confirmed"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
			case 'checkout':
			$sql = "UPDATE reservation_list SET res_status=2 WHERE res_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Checkedout"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
		case 'deletep':
			$time = Date('Y-m-d H:i:s');
			$sql = "UPDATE reservation_list INNER JOIN reservation_pay on reservation_pay.pay_res_id_ref=reservation_list.res_id
			INNER JOIN reservation_rooms on reservation_rooms.resrooms_res_id_ref= reservation_list.res_id
			 SET res_status=3, res_delete_at='$time',pay_status=2,pay_delete_at='$time',resrooms_status = 2,resrooms_delete_at ='$time' WHERE res_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deleted"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
			case 'deletec':
			$time = Date('Y-m-d H:i:s');
			$sql = "UPDATE reservation_list INNER JOIN reservation_pay on reservation_pay.pay_res_id_ref=reservation_list.res_id
			INNER JOIN reservation_rooms on reservation_rooms.resrooms_res_id_ref= reservation_list.res_id
			 SET res_status=3, res_delete_at='$time',pay_status=2,pay_delete_at='$time',resrooms_status = 2,resrooms_delete_at ='$time' WHERE res_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deleted"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
			case 'deletechk':
			$time = Date('Y-m-d H:i:s');
			$sql = "UPDATE reservation_list INNER JOIN reservation_pay on reservation_pay.pay_res_id_ref=reservation_list.res_id
			INNER JOIN reservation_rooms on reservation_rooms.resrooms_res_id_ref= reservation_list.res_id
			 SET res_status=3, res_delete_at='$time',pay_status=2,pay_delete_at='$time',resrooms_status = 2,resrooms_delete_at ='$time' WHERE res_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deleted"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
		default:
			echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No Change Done"
			));
			break;
	}
}
elseif ($operation=="deletepay"){
$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
$chk_pay = CheckExists("reservation_pay","pay_id = '$id' AND pay_status<>2");
	if (empty($chk_pay)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Data"
		));
		die();
	}
	$time = Date('Y-m-d H:i:s');
			$sql = "UPDATE reservation_pay SET pay_status=2, pay_delete_at='$time' WHERE pay_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deleted"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
}
elseif ($operation=="deleterom"){
$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
$chk_pay = CheckExists("reservation_rooms","resrooms_id = '$id' AND resrooms_status<>2");
	if (empty($chk_pay)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Data"
		));
		die();
	}
	$time = Date('Y-m-d H:i:s');
			$sql = "UPDATE reservation_rooms SET resrooms_status=2, resrooms_delete_at='$time' WHERE resrooms_id = '$id'";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deleted"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
}