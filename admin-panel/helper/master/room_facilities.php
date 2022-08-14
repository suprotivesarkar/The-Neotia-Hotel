<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
	echo $response = json_encode(array(
			"status" =>false,
			"msg"	 => "Unauthorized Access"
	));
	die(); 
}
$operation  = (!empty($_POST['operation']))?FilterInput($_POST['operation']):null; 
if (empty($operation)){
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Something Wrong"
	));
	die();
}
elseif ($operation=="fetchFac"){

	$id = (!empty($_POST['acmrid']))?FilterInput($_POST['acmrid']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo '<div class="alert alert-warning"><p>No Data Found</p></div>';
		die();
	}
	$data = CheckExists("room_category","roomcat_id ='$id' AND roomcat_status<>2");
	if (empty($data)) {
		echo '<div class="alert alert-warning"><p>No Data Found</p></div>';
		die();
	}
	$facdata = CheckExists("room_facility","fac_id ='$id' AND fac_status<>2");
	if (empty($data)) {
		echo '<div class="alert alert-warning"><p>No Facility Found</p></div>';
		die();
	}
	$stmt = $PDO->prepare("SELECT * FROM room_fac INNER JOIN room_facility ON room_facility.fac_id  = room_fac.rfac_fac_id_ref
							WHERE rfac_roomcat_id_ref='$id' AND rfac_status<>2 AND fac_status<>2 ORDER BY rfac_id ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table_thumb">
	<thead>
	<tr>
	<th>#</th>
	<th>FACILITY</th>
	<th>UNIT</th>
	<th>STATUS</th>
	<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);	?> 
	<tr id="<?php echo $rfac_id; ?>">
	<td><?php echo $i++; ?></td>
	<td><?php echo $fac_name; ?></td>
	<td><?php echo $rfac_qty; ?></td>
	<td><?php echo StatusReport($rfac_status);  ?></td>
	<td>
	<?php  
	if ($rfac_status==0) { ?> 
	<a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($rfac_id); ?>" data-operation="activethumb"><i class="fa fa-check"></i></a> ||
	<?php }else if($rfac_status==1) { ?>
	<a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $rfac_id; ?>" data-operation="deactivethumb"><i class="fa fa-lock"></i></a> ||
	<?php } ?>
	<a href="" data-toggle="modal" data-target="#upThumb" data-id="<?php echo htmlspecialchars($rfac_id); ?>" data-facid="<?php echo htmlspecialchars($rfac_fac_id_ref); ?>"  data-alt="<?php echo htmlspecialchars($rfac_qty); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> ||
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($rfac_id); ?>" data-operation="deletethumb"><i class="fa fa-trash"></i></a> 
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}

elseif ($operation=="addFac") {
	$id            = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	$roomfac       = (!empty($_POST['roomfac']))?FilterInput($_POST['roomfac']):null; 
	$facqty        = (!empty($_POST['facqty']))?FilterInput($_POST['facqty']):null; 

	if(empty($facqty) OR empty($roomfac) OR !(filter_var($roomfac,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Field is Empty"
		));
		die();
	} 
	$data = CheckExists("room_category","roomcat_id ='$id' AND roomcat_status<>2");
	if (empty($data)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	$acm_r_det = CheckExists("room_facility","fac_id = '$roomfac' AND fac_status<>2");
	if (empty($acm_r_det)) {
		echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Facility Not Found"
		));
		die();
	}
	$chk_sku = CheckExists("room_fac","(rfac_fac_id_ref = '$roomfac' AND rfac_roomcat_id_ref='$id') AND rfac_status<>2");
	if (!empty($chk_sku)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Facility Already Exists"
		));
		die();
	}

	$sql = "INSERT INTO room_fac SET
			rfac_roomcat_id_ref        = :rfac_roomcat_id_ref,
	        rfac_fac_id_ref         = :rfac_fac_id_ref,
	        rfac_qty               = :rfac_qty";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':rfac_roomcat_id_ref',$data->roomcat_id);
	        $insert->bindParam(':rfac_fac_id_ref',$roomfac);
	        $insert->bindParam(':rfac_qty',$facqty);
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

elseif ($operation=="upFac") {
	$uptid		  =  (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$upid         = (!empty($_POST['upid']))?FilterInput($_POST['upid']):null; 
	$upfac        = (!empty($_POST['upfac']))?FilterInput($_POST['upfac']):null; 
	$upfacqty     = (!empty($_POST['upfacqty']))?FilterInput($_POST['upfacqty']):null; 

	if(empty($uptid) OR empty($upfacqty) OR empty($upfac)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Field is Empty"
		));
		die();
	} 
	$data = CheckExists("room_category","roomcat_id ='$upid' AND roomcat_status<>2");
	if (empty($data)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	$acm_r_det = CheckExists("room_facility","fac_id = '$upfac' AND fac_status<>2");
	if (empty($acm_r_det)) {
		echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Facility Not Found"
		));
		die();
	}
	
	$sql = "UPDATE room_fac SET
			rfac_roomcat_id_ref        = :rfac_roomcat_id_ref,
	        rfac_fac_id_ref         = :rfac_fac_id_ref,
	        rfac_qty               = :rfac_qty
	        WHERE rfac_id = :rfac_id";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':rfac_roomcat_id_ref',$data->roomcat_id);
	        $insert->bindParam(':rfac_fac_id_ref',$upfac);
	        $insert->bindParam(':rfac_qty',$upfacqty);
	        $insert->bindParam(':rfac_id',$uptid);
	        $insert->execute();
	        if($insert->rowCount() > 0){
	        	echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Updated"
				));
	        }else {
	        	echo $response = json_encode(array(
					"status" =>false,
					"msg"	 =>"No Changes Done"
				));
			}

}



elseif ($operation=="activethumb" OR $operation=="deactivethumb" OR $operation=="deletethumb") {


	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	switch ($operation) {
		case 'activethumb':
			$up = 1;
			$msg="Successfully Activated";
			break;
		case 'deactivethumb':
			$up = 0;
			$msg="Successfully Deactivated";
			break;
		case 'deletethumb':
			$up = 2;
			$msg="Successfully Deleted";
			break;
		default:
			$up=1;
			$msg="Something Wrong";
			break;
	}
	$chk_id = CheckExists("room_fac","rfac_id = '$id' AND rfac_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Data"
		));
		die();
	}
	$ad  = ($operation=='deletethumb')?", rfac_delete_at='$nowTime'":null;
	$sql = "UPDATE room_fac SET rfac_status = {$up} ".$ad. " WHERE rfac_id = '$id'";
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