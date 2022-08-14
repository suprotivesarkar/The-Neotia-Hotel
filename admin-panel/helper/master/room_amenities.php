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
	$stmt = $PDO->prepare("SELECT * FROM room_amenities WHERE am_status<>2 ORDER BY am_id ASC");
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
	<tr id="<?php echo $am_id; ?>">
	<td><?php echo $i++; ?></td>
	<td><?php echo $am_name; ?></td>
	<td><?php echo $am_slug; ?></td>
	<td><?php echo StatusReport($am_status);  ?></td>
	<td>
	<?php  
    if ($am_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($am_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($am_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['am_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($am_id); ?>" data-name="<?php echo htmlspecialchars($am_name); ?>" data-url="<?php echo htmlspecialchars($am_slug); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($am_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
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
	$chk_slug = CheckExists("room_amenities","(am_slug = '$nameurl' OR am_name = '$name') AND am_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "INSERT INTO room_amenities SET
	        am_slug   = :am_slug,
	        am_name   = :am_name";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':am_slug',$nameurl);
	        $insert->bindParam(':am_name',$name);
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
	$chk_id = CheckExists("room_amenities","am_id = '$uptid' AND am_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$chk_slug = CheckExists("room_amenities","(am_slug = '$upnameurl' OR am_name = '$upname') AND am_id<>'$uptid' AND am_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "UPDATE room_amenities SET
		        am_slug      = :am_slug,
		        am_name     = :am_name
	            WHERE am_id=:am_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':am_slug',$upnameurl);
		        $insert->bindParam(':am_name',$upname);
	            $insert->bindParam(':am_id',$uptid);
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
	$chk_id = CheckExists("room_amenities","am_id = '$id' AND am_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", am_delete_at='$nowTime'":null;
	$sql = "UPDATE room_amenities SET am_status= {$up} ".$ad. " WHERE am_id= {$id}";
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
// 	    $sql = "UPDATE room_amenities SET
// 		            am_order ='$i'
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