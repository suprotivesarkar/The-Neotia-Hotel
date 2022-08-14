<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
	echo $response = json_encode(array(
			"status" =>false,
			"msg"	 => "Unauthorized Access"
	));
	die(); 
}
$operation = (!empty($_POST['operation']))?FilterInput($_POST['operation']):null; 
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
	  $customfilter.=" AND DATE(pay_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(pay_create_at) <= '$edt' ";
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
	        case 0:$column="pay_id";break;
	        case 1:$column="pay_create_at";break;
	    }
	    return $column; 
	}


	$where .=" ( user_name LIKE '".$search."%' OR user_phone LIKE '".$search."%' OR pay_amount LIKE '".$search."%' OR pay_transaction LIKE '".$search."%') ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT * FROM reservation_pay 
 				INNER JOIN reservation_list on reservation_list.res_id=reservation_pay.pay_res_id_ref AND res_status<>3
 				LEFT JOIN users_list on users_list.user_id=reservation_pay.pay_by
 				WHERE pay_status=1 ".$customfilter;


	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;

	//echo $sqlRec;

	$sqlTot = $PDO->prepare($sqlTot);
	$sqlRec = $PDO->prepare($sqlRec);

	$sqlTot->execute();
	$sqlRec->execute();


if($sqlRec->rowCount()>0){
$i=$start+1;
while($row=$sqlRec->fetch()) { 

	extract($row);	
	$actionlink =null;
	$actionlink.='<td>';
	$actionlink.='<a href="reservation-view?id='.$res_id.'" class="" title="View More""><i class="fa fa-eye"></i></a>
	</td>';


$ptype = 0;
if($pay_type == 1)
	{ $ptype = 'ONLINE'; } else{ $ptype = 'OFFLINE';}

      $subdata   = array();
      $subdata[] = $i++;   
      $subdata[] = $res_g_name;
      $subdata[] = $res_g_phone;
      $subdata[] = date("d-M-y D H:i", strtotime($pay_create_at));
      $subdata[] = date("d-M-y D H:i", strtotime($pay_update_at));
      $subdata[] = 'Rs.'.$pay_amount;
      $subdata[] = $ptype;
      $subdata[] = $pay_transaction;
      $subdata[] = $user_name;
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

