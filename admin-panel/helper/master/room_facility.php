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
	$stmt = $PDO->prepare("SELECT * FROM room_facility WHERE fac_status<>2 ORDER BY fac_id ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>NAME</th>
	<th>URL</th>
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
	<tr id="<?php echo $fac_id; ?>">
	<td><?php echo $i++; ?></td>
	<td><?php echo $fac_name; ?></td>
	<td><?php echo $fac_slug; ?></td>
	<td><?php echo StatusReport($fac_status);  ?></td>
	<td>
	<?php  
    if ($fac_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($fac_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($fac_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['fac_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($fac_id); ?>" data-name="<?php echo htmlspecialchars($fac_name); ?>" data-url="<?php echo htmlspecialchars($fac_slug); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($fac_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$name     = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
	$nameurl  = (!empty($_POST['nameurl']))?FilterInput($_POST['nameurl']):null; 
	if(empty($name) OR empty($nameurl)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	$chk_slug = CheckExists("room_facility","(fac_slug = '$nameurl' OR fac_name = '$name') AND fac_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "INSERT INTO room_facility SET
	        fac_slug   = :fac_slug,
	        fac_name   = :fac_name";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':fac_slug',$nameurl);
	        $insert->bindParam(':fac_name',$name);
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
	$upname    = (!empty($_POST['upname']))?FilterInput($_POST['upname']):null; 
	$upnameurl = (!empty($_POST['upnameurl']))?FilterInput($_POST['upnameurl']):null; 

	if(empty($uptid) OR empty($upname) OR empty($upnameurl) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("room_facility","fac_id = '$uptid' AND fac_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$chk_slug = CheckExists("room_facility","(fac_slug = '$upnameurl' OR fac_name = '$upname') AND fac_id<>'$uptid' AND fac_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "UPDATE room_facility SET
		        fac_slug      = :fac_slug,
		        fac_name     = :fac_name
	            WHERE fac_id=:fac_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':fac_slug',$upnameurl);
		        $insert->bindParam(':fac_name',$upname);
	            $insert->bindParam(':fac_id',$uptid);
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
	$chk_id = CheckExists("room_facility","fac_id = '$id' AND fac_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", fac_delete_at='$nowTime'":null;
	$sql = "UPDATE room_facility SET fac_status= {$up} ".$ad. " WHERE fac_id= {$id}";
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
// elseif ($operation=="orderType") {
// 	$res  = (!empty($_POST['data']))?$_POST['data']:null;
// 	if (empty($res)) {
// 		echo $response = json_encode(array(
// 			"status" => false, 
// 			"msg"	 => "No Changes Done"
// 		));   
// 		die();
// 	}
// 	$i=1;
// 	foreach ($res as $value) {
// 	    $sql = "UPDATE room_facility SET
// 		            fac_order ='$i'
// 		            WHERE acm_room_amenity_id ='$value'";
// 		            $update = $PDO->prepare($sql);
// 			        $update->execute();
// 		$i++;
// 	}
// 	echo $response = json_encode(array(
// 			"status" => true, 
// 			"msg"	 => "Success"
// 	));   
// 	die();
// }
// else {
// 	echo $response = json_encode(array(
// 			"status" => false,
// 			"msg"	 =>" Something Wrong"
// 	));
// 	die();
// }