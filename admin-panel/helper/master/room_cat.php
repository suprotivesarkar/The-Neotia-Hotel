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
if ($operation=="fetch"){
	$stmt = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category on room_list.room_roomcat_id_ref = room_category.roomcat_id 
		WHERE room_status<>2 AND roomcat_status<>2  ORDER BY room_no ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>ROOM TYPE</th>
	<th>ROOM NO.</th>
	<th>STATUS</th>
	<th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);
	?> 
	<tr id="<?php echo $room_id; ?>">
	<td ><?php echo $i++; ?></td>
	<td style="color: #450796; font-weight: 600;"><?php echo $roomcat_name; ?></td>
	<td><?php echo $room_no; ?></td>
	<td><?php echo StatusReport($room_status);  ?></td>
	<td>
	<?php  
    if ($room_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($room_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($room_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['room_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($room_id); ?>" data-name="<?php echo htmlspecialchars($room_roomcat_id_ref); ?>" data-url="<?php echo htmlspecialchars($room_no); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($room_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$room     = (!empty($_POST['room']))?FilterInput($_POST['room']):null; 
	$roomnum  = (!empty($_POST['roomnum']))?FilterInput($_POST['roomnum']):null; 
	if(empty($room) OR empty($roomnum)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Details"
		));
		die();
	}
	$chk_slug = CheckExists("room_list","(room_no = '$roomnum') AND room_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Room No. Already Exists"
		));
		die();
	}

	$sql = "INSERT INTO room_list SET
	        room_roomcat_id_ref   = :room_roomcat_id_ref,
	        room_no            = :room_no";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':room_roomcat_id_ref',$room);
	        $insert->bindParam(':room_no',$roomnum);
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
	$uproom    = (!empty($_POST['uproom']))?FilterInput($_POST['uproom']):null; 
	$uproomno = (!empty($_POST['uproomno']))?FilterInput($_POST['uproomno']):null; 

	if(empty($uptid) OR empty($uproom) OR empty($uproomno) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields are Empty"
		));
		die();
	}
	$chk_id = CheckExists("room_list","room_id = '$uptid' AND room_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$chk_slug = CheckExists("room_list","(room_no = '$uproomno') AND room_id='$uptid' AND room_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Room No. Already Exists"
		));
		die();
	}
	$sql = "UPDATE room_list SET
		        room_roomcat_id_ref      = :room_roomcat_id_ref,
		        room_no             = :room_no
	            WHERE room_id=:room_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':room_roomcat_id_ref',$uproom);
		        $insert->bindParam(':room_no',$uproomno);
	            $insert->bindParam(':room_id',$uptid);
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


	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
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
	$chk_id = CheckExists("room_list","room_id = '$id' AND room_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", room_delete_at='$nowTime'":null;
	$sql = "UPDATE room_list SET room_status= {$up} ".$ad. " WHERE room_id= {$id}";
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
elseif ($operation=="orderType") {
	$res  = (!empty($_POST['data']))?$_POST['data']:null;
	if (empty($res)) {
		echo $response = json_encode(array(
			"status" => false, 
			"msg"	 => "No Changes Done"
		));   
		die();
	}
	$i=1;
	foreach ($res as $value) {
	    $sql = "UPDATE room_list SET
		            room_id ='$i'
		            WHERE room_id ='$value'";
		            $update = $PDO->prepare($sql);
			        $update->execute();
		$i++;
	}
	echo $response = json_encode(array(
			"status" => true, 
			"msg"	 => "Success"
	));   
	die();
}
else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}