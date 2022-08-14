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
	//die();
	$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
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
    res_status = 3 ".$customfilter;

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
		<a href="reservation-cancel-view?id='.$res_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>';
 	$actionlink.='<div class="dropdown-divider"></div>';
	$actionlink.='</td></div></div>';


	$taxtot = $total + Tax($total);
	$create 	  = new DateTime($res_create_at);
	$cancel 	  = new DateTime($res_cancel_time);
	$interval     = $cancel->diff($create);
	$intervaltime = $interval->format('%m months %d days %H hours %i minutes');


      $subdata   = array();
      $subdata[] = $i++;   
      $subdata[] = date("d-m-Y H:i:s", strtotime($res_create_at))."<br/>".date("d-m-Y H:i:s", strtotime($res_cancel_time))."<br/>".$intervaltime;  
      $subdata[] = $res_id;  
      $subdata[] = '<a href="reservation-cancel-view?id='.$res_id.'" title="View">'.'<b style = "color: #f60000">'.$res_g_name.'</b>'.'</a>';
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


elseif ($operation=="updaterefund") {
	$resid             = (!empty($_POST['resid']))?FilterInput($_POST['resid']):null; 
	$amount            = (!empty($_POST['amount']))?FilterInput($_POST['amount']):null;  
	$paymode           = (!empty($_POST['paymode']))?FilterInput($_POST['paymode']):null;  
	$transactionid     = (!empty($_POST['transactionid']))?FilterInput($_POST['transactionid']):null;   
	$transactionnote   = (!empty($_POST['transactionnote']))?FilterInput($_POST['transactionnote']):null;   

	if(empty($resid) OR empty($amount) OR empty($paymode) OR !is_numeric($resid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("reservation_list","res_id = '$resid' AND res_status=3");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Reservation"
		));
		die();
	}

	$paymodeval = 0;
	if ($paymode == 'online') {
		$paymodeval = 1;
	}
	$sql = "UPDATE reservation_list SET
				res_refund_amount           = :res_refund_amount,
				res_refund_way              = :res_refund_way,
		        res_refund_transaction_id   = :res_refund_transaction_id,
		        res_refund_note             = :res_refund_note,
		        res_refund_by               = :res_refund_by,
		        res_refund_time             = :res_refund_time
	            WHERE res_id=:res_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':res_refund_amount',$amount);
		        $insert->bindParam(':res_refund_way',$paymodeval);
		        $insert->bindParam(':res_refund_transaction_id',$transactionid);
		        $insert->bindParam(':res_refund_note',$transactionnote);
		        $insert->bindParam(':res_refund_by',$_SESSION['adminid']);
		        $insert->bindParam(':res_refund_time',$nowTime);
	            $insert->bindParam(':res_id',$resid);
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