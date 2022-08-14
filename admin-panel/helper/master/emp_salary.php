
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
	  $customfilter.=" AND DATE(sal_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(sal_create_at) <= '$edt' ";
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
	        case 0:$column="sal_id";break;
	        case 1:$column="sal_amount";break;
	        case 2:$column="user_name";break;
	    }
	    return $column; 
	}


	$where .=" ( sal_amount LIKE '".$search."%' OR user_name LIKE '".$search."%' ) ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT * FROM emp_salary 
 				INNER JOIN users_list on emp_salary.sal_user_id_ref=users_list.user_id 
 				WHERE sal_status<>2 AND user_status<>2 ".$customfilter;

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

	$actionlink.='<div class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
		<div class="dropdown-menu">
		 <a href="employee-view?id='.$user_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>';
 	$actionlink.='<a href="" data-toggle="modal" data-target="#upMod" data-id="'.htmlspecialchars($sal_id).'" data-eid="'.htmlspecialchars($sal_user_id_ref).'" data-amount="'.htmlspecialchars($sal_amount).'" data-dt="'.htmlspecialchars($sal_date).'" class="dropdown-item editbtn" title="Quick Update"><i class="fa fa-edit"></i> Quick Update</a>';

	// $actionlink.='<a href="product-update?id='.$emp_id.'" title="Update Info" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update</a>';
 	
 	if ($sal_status==0) {
 		$actionlink.='<a href="javascript:void(0);" title="Make Active" class="dropdown-item text-success statusup" data-id="'.$sal_id.'" data-operation="active"><i class="fa fa-check"></i> Active</a>';
 	}
 	else if($sal_status==1) {
 		$actionlink.='<a href="javascript:void(0);" title="Make Dective" class="dropdown-item text-danger statusup" data-id="'.$sal_id.'" data-operation="deactive"><i class="fa fa-lock"></i> Deactive</a>';
 	}

 	$actionlink.='<div class="dropdown-divider"></div>';
 	$actionlink.='<a href="javascript:void(0);" class="dropdown-item statusup" title="Delete" data-id="'.htmlspecialchars($sal_id).'" data-operation="delete"><i class="fa fa-trash"></i> Delete</a>';


	$actionlink.='</td></div></div>';


      $subdata   = array();
      $subdata[] = $i++;   
      //  $subdata[] = '<a href="employee-view?id='.$sal_id.'">'.$emp_name.'</a>';
      $subdata[] = '<b style = "color:#04824d;">' .$user_name.'</b>' ;
      // $subdata[] = '<a href="employee-view?id='.$sal_id.'">'.'<b style = "color: blue">'.$emp_name.'</b>'.'</a>';
      $subdata[] = '₹'.$sal_amount;
      $subdata[] = date ("M",strtotime("$sal_date"));
      $subdata[] = date ("d-M-Y",strtotime("$sal_date"));
      $subdata[] = StatusReport($sal_status);
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



elseif ($operation=="addnew") {

	$eid   = (!empty($_POST['eid']))?FilterInput($_POST['eid']):null;
	$date  = (!empty($_POST['date']))?FilterInput($_POST['date']):null; 
	if(  empty($date)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Amount"
		));
		die();
	}
	if(empty($eid)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Employee Name"
		));
		die();
	}
	$chk_employee = CheckExists("users_list","user_id = '$eid' AND user_status=1");
		if (empty($chk_employee)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "This Employee Name Not Match"
			));
			die();
		}
	
	
	$sql = "INSERT INTO emp_salary SET
	        sal_user_id_ref  = :sal_user_id_ref,
	        sal_amount      = :sal_amount,
	        sal_date      = :sal_date";
	        $insert = $PDO->prepare($sql);;
	        $insert->bindParam(':sal_user_id_ref',$eid);
	        $insert->bindParam(':sal_amount',$chk_employee->user_salary);
	        $insert->bindParam(':sal_date',$date);
	        $insert->execute();
	        if($insert->rowCount() > 0){
	        	echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Added"
				));
	        }else {
	        	echo $response = json_encode(array(
					"status" =>false,
					"msg"	 =>"Something Wrong"
				));
			}
}
elseif($operation=="update") {
	$uptid     = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$upamount   = (!empty($_POST['upamount']))?FilterInput($_POST['upamount']):null; 
    $upeid   = (!empty($_POST['upeid']))?FilterInput($_POST['upeid']):null;
	$uppdate = (!empty($_POST['uppdate']))?FilterInput($_POST['uppdate']):null; 

	if(empty($uptid) OR empty($upamount) OR empty($uppdate) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("emp_salary","sal_id = '$uptid' AND sal_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}		
	$chk_employee = CheckExists("users_list","user_id = '$upeid' AND user_status=1");
		if (empty($chk_employee)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "This Employee Name Not Match"
			));
			die();
		}
	// $chk_slug = CheckExists("category_list","(cat_slug = '$upnameurl' OR cat_name = '$upname') AND cat_id<>'$uptid' AND cat_status<>2");
	// if (!empty($chk_slug)) {
	// 	echo $response = json_encode(array(
	// 			"status" => false,
	// 			"msg"	 => "This Name Already Exists"
	// 	));
	// 	die();
	// }
	$sql = "UPDATE emp_salary SET
		        sal_user_id_ref     = :sal_user_id_ref,
		        sal_amount     = :sal_amount,
		        sal_date    = :sal_date
	            WHERE sal_id=:sal_id";
	            $insert = $PDO->prepare($sql);
	            $insert->bindParam(':sal_user_id_ref',$upeid);
		        $insert->bindParam(':sal_amount',$upamount);
		        $insert->bindParam(':sal_date',$uppdate);
	            $insert->bindParam(':sal_id',$uptid);
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

elseif ($operation=="active" OR $operation=="deactive" OR $operation=="delete") {

	$id =FilterInput($_POST['id']);
	
	if(empty($id) AND !is_numeric($id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	switch ($operation) {
		case 'active':
			$up = 1;
			$msg="Successfully Activated";
			break;
		case 'deactive':
			$up = 0;
			$msg="Successfully Deactivated";
			break;
		case 'delete':
			$up = 2;
			$msg="Successfully Deleted";
			break;
		default:
			$up=1;
			$msg="Something Wrong";
			break;
	}
	$chk_id = CheckExists("emp_salary","sal_id = '$id' AND sal_status<>2");

	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", sal_delete_at=NOW()":null;
	$sql = "UPDATE emp_salary SET sal_status= {$up} ".$ad. " WHERE sal_id= {$id}";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
					echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => $msg
				));
			}else {
					echo $response = json_encode(array(
					"status" =>false,
					"msg"	 =>"No Change Done"
				));
			}
}
else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}