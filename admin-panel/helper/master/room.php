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
  
  
if ($operation=="fetchRooms"){
	$stmt = $PDO->prepare("SELECT * FROM room_category WHERE roomcat_status<>2 ORDER BY roomcat_id ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table_room">
	<thead>
	<tr>
	<th>#</th>
	<th>NAME</th>
	<th>TYPE</th>
	<th>CAPACITY</th>
	<th>STATUS</th>
	<th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);	?> 
	<tr id="<?php echo $roomcat_id; ?>">
	<td><?php echo $i++; ?></td>
	<td><a href="rooms-brief?id=<?php echo $roomcat_id; ?>" title="View" class="text-info bold"><?php echo $roomcat_name; ?></a></td>
	<td><?php echo ($roomcat_type==1)?"NON-AC":"AC"; ?></td>
	<td><?php echo $roomcat_adult."A | ".$roomcat_child."C"; ?></td>
	<td><?php echo StatusReport($roomcat_status);  ?></td>
	<td>
	<a href="rooms-brief?id=<?php echo $roomcat_id; ?>" title="View" class="text-info"><i class="fa fa-eye"></i></a> ||
	<?php  
    if ($roomcat_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($roomcat_id); ?>" data-operation="activeroom"><i class="fa fa-check"></i> || </a>
    <?php }else if($roomcat_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $roomcat_id; ?>" data-operation="deactiveroom"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="room-update?id=<?php echo htmlspecialchars($roomcat_id); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($roomcat_id); ?>" data-operation="deleteroom"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="addRoom") {
	$roomname      = (!empty($_POST['roomname']))?FilterInput($_POST['roomname']):null; 
	$roomurl       = (!empty($_POST['roomurl']))?FilterInput($_POST['roomurl']):null;  
	$roomtype       = (!empty($_POST['roomtype']))?FilterInput($_POST['roomtype']):null;  
	$maxadult      = (!empty($_POST['maxadult']))?FilterInput($_POST['maxadult']):null; 
	$maxchild      = (!empty($_POST['maxchild']))?$_POST['maxchild']:null; 
	$rmsprice      = (!empty($_POST['rmsprice']))?FilterInput($_POST['rmsprice']):null;
	$extrabedcost  = (!empty($_POST['extrabedcost']))?FilterInput($_POST['extrabedcost']):null;
	$roomamenities = (!empty($_POST['roomamenities']))?$_POST['roomamenities']:NULL;

	if(empty($roomname) OR empty($roomurl) OR empty($roomtype)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Field is Empty"
		));
		die();
	} 
	// $acm_r_det = CheckExists("room_category","roomcat_id = '$roomcat' AND roomcat_status<>2");
	// if (empty($acm_r_det)) {
	// 	echo $response = json_encode(array(
	// 				"status" => false,
	// 				"msg"	 => "Room Category Not Found"
	// 	));
	// 	die();
	// }
		$chk_slug = CheckExists("room_category","(roomcat_slug = '$roomurl' OR roomcat_name = '$roomname') AND roomcat_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Room Name Already Exists"
		));
		die();
	}

	if (!empty($roomamenities)) {
		$roomamenities = implode(',', $roomamenities);}
	
	$sql = "INSERT INTO room_category SET
	        roomcat_name               = :roomcat_name,
	        roomcat_slug               = :roomcat_slug,
	        roomcat_type               = :roomcat_type,
	        roomcat_adult              = :roomcat_adult,
	        roomcat_child              = :roomcat_child,
	        roomcat_price              = :roomcat_price,
	        roomcat_extrabed           = :roomcat_extrabed,
	        roomcat_amenities          = :roomcat_amenities";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':roomcat_name',$roomname);
	        $insert->bindParam(':roomcat_slug',$roomurl);
	        $insert->bindParam(':roomcat_type',$roomtype);
	        $insert->bindParam(':roomcat_adult',$maxadult);
	        $insert->bindParam(':roomcat_child',$maxchild);
	        $insert->bindParam(':roomcat_price',$rmsprice);
	        $insert->bindParam(':roomcat_extrabed',$extrabedcost);
	        $insert->bindParam(':roomcat_amenities',$roomamenities);
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

elseif ($operation=="updateRoom") {

	$acmrid        = (!empty($_POST['acmrid']))?FilterInput($_POST['acmrid']):null; 
	$roomurl       = (!empty($_POST['roomurl']))?FilterInput($_POST['roomurl']):null;
	$roomname      = (!empty($_POST['roomname']))?FilterInput($_POST['roomname']):null; 
	$roomtype      = (!empty($_POST['roomtype']))?FilterInput($_POST['roomtype']):null; 
	$maxadult      = (!empty($_POST['maxadult']))?FilterInput($_POST['maxadult']):null; 
	$maxchild      = (!empty($_POST['maxchild']))?FilterInput($_POST['maxchild']):null; 
	$rmsprice      = (!empty($_POST['rmsprice']))?FilterInput($_POST['rmsprice']):null;
	$extrabedcost  = (!empty($_POST['extrabedcost']))?FilterInput($_POST['extrabedcost']):null;
	$imgdesc       = (!empty($_POST['imgdesc']))?FilterInput($_POST['imgdesc']):NULL;
	$smalldesc     = (!empty($_POST['smalldesc']))?$_POST['smalldesc']:NULL;
	$fulldesc      = (!empty($_POST['fulldesc']))?$_POST['fulldesc']:NULL;
	$roomamenities = (!empty($_POST['roomamenities']))?$_POST['roomamenities']:NULL;

	if(empty($acmrid) OR empty($roomname) OR empty($roomurl) OR empty($roomtype)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Field is Empty"
		));
		die();
	} 

	$room_det = CheckExists("room_category","roomcat_id = '$acmrid' AND roomcat_status<>2");
	if (empty($room_det)) {
		echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Room Not Found"
		));
		die();
	}
	if (!empty($roomamenities)) {
		$roomamenities = implode(',', $roomamenities);
	}


	$thumb = $room_det->roomcat_thumb;
	if(!empty($_FILES['timage']['name'])){

		$valid_ext   = array('jpeg', 'jpg'); 
		$MimeFilter  = array('image/jpeg', 'image/jpg');
		$MaxSize     = 5 * 1024 * 1024;

		$FileName    = FilterInput($_FILES['timage']['name']);
		$tmpName     = $_FILES['timage']['tmp_name'];
		$FileTyp     = $_FILES['timage']['type'];
		$FileSize    = $_FILES['timage']['size']; 
		$MimeType    = mime_content_type($_FILES['timage']['tmp_name']);

		$ext         = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Extention Not Allowed"
			));
			die();
		}
		if($FileSize>$MaxSize){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if($FileTyp!='image/jpeg' && $FileTyp!='image/jpg'){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR JPEG"
			));
			die();
		}
		if(!in_array($MimeType, $MimeFilter)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Not Supported"
			));
			die();
		}
		$dir          = "../../../images/rooms/room-thumb/";
		$thumb        = FileName($roomname).'_'.time().rand(10000,999999).'.'.$ext;
		$width        = 730;
	    $height       = 705;
		$img_file_fu  = ImageProperResize($height,$width,$dir,$thumb,$_FILES["timage"]["tmp_name"]);

		if (!empty($room_det->room_thumb) AND file_exists("../../../images/rooms/room-thumb/".$room_det->roomcat_thumb)){
			@unlink("../../../images/rooms/room-thumb/".$room_det->roomcat_thumb);
		}
	}
		$coverimg = $room_det->roomcat_coverimg;
	if(!empty($_FILES['image']['name'])){

		$valid_ext   = array('jpeg', 'jpg'); 
		$MimeFilter  = array('image/jpeg', 'image/jpg');
		$MaxSize     = 5 * 1024 * 1024;

		$FileName    = FilterInput($_FILES['image']['name']);
		$tmpName     = $_FILES['image']['tmp_name'];
		$FileTyp     = $_FILES['image']['type'];
		$FileSize    = $_FILES['image']['size']; 
		$MimeType    = mime_content_type($_FILES['image']['tmp_name']);

		$ext         = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Extention Not Allowed"
			));
			die();
		}
		if($FileSize>$MaxSize){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if($FileTyp!='image/jpeg' && $FileTyp!='image/jpg'){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR JPEG"
			));
			die();
		}
		if(!in_array($MimeType, $MimeFilter)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Not Supported"
			));
			die();
		}
		$dir          = "../../../images/rooms/room-coverimg/";
		$coverimg        = FileName($roomname).'_'.time().rand(10000,999999).'.'.$ext;
		$width        = 1920;
	    $height       = 1080;
		$img_file_fu  = ImageProperResize($height,$width,$dir,$coverimg,$_FILES["image"]["tmp_name"]);

		if (!empty($room_det->room_coverimg) AND file_exists("../../../images/rooms/room-coverimg/".$room_det->roomcat_coverimg)){
			@unlink("../../../images/rooms/room-coverimg/".$room_det->roomcat_coverimg);
		}
	}
	$sql = "UPDATE room_category SET
	        roomcat_name           = :roomcat_name,
	        roomcat_slug           = :roomcat_slug,
	        roomcat_type           = :roomcat_type,
	        roomcat_adult          = :roomcat_adult,
	        roomcat_child          = :roomcat_child,
	        roomcat_price          = :roomcat_price,
	        roomcat_extrabed       = :roomcat_extrabed,
	        roomcat_thumb          = :roomcat_thumb,
	        roomcat_coverimg       = :roomcat_coverimg,
	        roomcat_thumb_alt      = :roomcat_thumb_alt,
	        roomcat_smalldesc      = :roomcat_smalldesc,
	        roomcat_fulldesc       = :roomcat_fulldesc,
	        roomcat_amenities      = :roomcat_amenities
	        WHERE roomcat_id=:roomcat_id";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':roomcat_name',$roomname);
	        $insert->bindParam(':roomcat_slug',$roomurl);
	        $insert->bindParam(':roomcat_type',$roomtype);
	        $insert->bindParam(':roomcat_adult',$maxadult);
	        $insert->bindParam(':roomcat_child',$maxchild);
	        $insert->bindParam(':roomcat_price',$rmsprice);
	        $insert->bindParam(':roomcat_extrabed',$extrabedcost);
	        $insert->bindParam(':roomcat_thumb',$thumb);
	        $insert->bindParam(':roomcat_coverimg',$coverimg);
	        $insert->bindParam(':roomcat_thumb_alt',$imgdesc);
	        $insert->bindParam(':roomcat_smalldesc',$smalldesc);
	        $insert->bindParam(':roomcat_fulldesc',$fulldesc);
	        $insert->bindParam(':roomcat_amenities',$roomamenities);
	        $insert->bindParam(':roomcat_id',$acmrid);
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
// elseif ($operation=="orderRoomList") {
// 	$res  = (!empty($_REQUEST['data']))?$_REQUEST['data']:null;
// 	$i=1;
// 	if (empty($res)) {
// 		echo $response = json_encode(array(
// 			"status" => false, 
// 			"msg"	 => "No Changes Done"
// 		));   
// 		die();
// 	}
// 	foreach ($res as $value) {
// 	    $sql = "UPDATE room SET
// 		            acm_roomcat_order ='$i'
// 		            WHERE acm_roomcat_id='$value'";
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
elseif ($operation=="activeroom" OR $operation=="deactiveroom" OR $operation=="deleteroom") {


	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) AND !is_numeric($id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	switch ($operation) {
		case 'activeroom':
			$up = 1;
			$msg="Successfully Activated";
			break;
		case 'deactiveroom':
			$up = 0;
			$msg="Successfully Deactivated";
			break;
		case 'deleteroom':
			$up = 2;
			$msg="Successfully Deleted";
			break;
		default:
			$up=1;
			$msg="Something Wrong";
			break;
	}
	$chk_id = CheckExists("room_category","roomcat_id = '$id' AND roomcat_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	if($operation=="deleteroom"){
		$sql = "UPDATE  room_list SET room_status = 2 , room_delete_at='$nowTime' WHERE room_roomcat_id_ref = '$id'";
		$insert = $PDO->prepare($sql);
		$insert->execute();
	}
	$ad  = ($operation=='deleteroom')?", roomcat_delete_at='$nowTime'":null;
	$sql = "UPDATE  room_category SET roomcat_status= {$up} ".$ad. " WHERE roomcat_id= '$id'";
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
