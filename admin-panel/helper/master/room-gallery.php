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
elseif ($operation=="fetchGal"){

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
	$stmt = $PDO->prepare("SELECT * FROM room_gallery WHERE rg_roomcat_id_ref='$id' AND rg_img_status<>2 ORDER BY rg_img_order ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table_thumb">
	<thead>
	<tr>
	<th>#</th>
	<th>LG</th>
	<th>MD</th>
	<th>NAME</th>
	<th>ALT</th>
	<th>STATUS</th>
	<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);	?> 
	<tr id="<?php echo $rg_id; ?>">
	<td><?php echo $i++; ?></td>
	<td><img height="30" src="../images/rooms/rgallery/<?php echo $rg_img_lg;  ?>"></td>
	<td><img height="30" src="../images/rooms/rgallery/<?php echo $rg_img_md;  ?>"></td>
	<td><?php echo $rg_img_name; ?></td>
	<td><?php echo $rg_img_alt; ?></td>
	<td><?php echo StatusReport($rg_img_status);  ?></td>
	<td>
	<?php  
	if ($rg_img_status==0) { ?> 
	<a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($rg_id); ?>" data-operation="activethumb"><i class="fa fa-check"></i></a> ||
	<?php }else if($rg_img_status==1) { ?>
	<a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $rg_id; ?>" data-operation="deactivethumb"><i class="fa fa-lock"></i></a> ||
	<?php } ?>
	<a href="" data-toggle="modal" data-target="#upThumb" data-id="<?php echo htmlspecialchars($rg_id); ?>" data-name="<?php echo htmlspecialchars($rg_img_name); ?>" data-alt="<?php echo htmlspecialchars($rg_img_alt); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> ||
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($rg_id); ?>" data-operation="deletethumb"><i class="fa fa-trash"></i></a> 
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Image Found</p></div>'; }
}
elseif ($operation=="addGal") {
	$id        = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	$imgname   = (!empty($_POST['thumbimgname']))?FilterInput($_POST['thumbimgname']):NULL; 
	$imgalt    = (!empty($_POST['thumbimgalt']))?FilterInput($_POST['thumbimgalt']):NULL; 
	if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
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
	if(empty($_FILES['image']['name'])){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select a Image"
		));
		die();
	} 
	
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

	$dir          = "../../../images/rooms/rgallery/";

	$filename_lg  = FileName(!empty($imgname)?$imgname:$data->roomcat_name).'_'.time().rand(10000,9999999).'_lg'.'.'.$ext;
	$width        = 760;
	$height       = 400;
	$img_file_lg  = ImageProperResize($height,$width,$dir,$filename_lg,$_FILES["image"]["tmp_name"]);

	$filename_md  = FileName(!empty($imgname)?$imgname:$data->roomcat_name).'_'.time().rand(10000,9999999).'_md'.'.'.$ext;
	$width        = 600;
	$height       = 500;
	$img_file_md  = ImageProperResize($height,$width,$dir,$filename_md,$_FILES["image"]["tmp_name"]);

	if(empty($img_file_lg) OR empty($img_file_md)){
		echo $response = json_encode(array(
			"status" =>false, 
			"msg"	 =>"Something wrong While Uploading"
		));
		die();
	}
	$sql = "INSERT INTO room_gallery SET
		        rg_roomcat_id_ref   = :rg_roomcat_id_ref,
		        rg_img_lg        = :rg_img_lg,
		        rg_img_md        = :rg_img_md,
		        rg_img_name      = :rg_img_name,
		        rg_img_alt       = :rg_img_alt,
		        rg_update_at     = :rg_update_at,
		        rg_create_at     = :rg_create_at";
		        $insert   = $PDO->prepare($sql);;
		        $insert->bindParam(':rg_roomcat_id_ref',$data->roomcat_id);
		        $insert->bindParam(':rg_img_lg',$filename_lg);
		        $insert->bindParam(':rg_img_md',$img_file_md);
		        $insert->bindParam(':rg_img_name',$imgname);
		        $insert->bindParam(':rg_img_alt',$imgalt);
		        $insert->bindParam(':rg_update_at',$nowTime);
		        $insert->bindParam(':rg_create_at',$nowTime);
		        $insert->execute();
		        if($insert->rowCount() > 0){
		        	echo $response = json_encode(array(
						"status" => true, 
						"msg"	 => "Successfully Added"
					));
		        }else {
		        	echo $response = json_encode(array(
						"status" =>false,
						"msg"	 =>"No Changes Done"
					));
				}
}
elseif ($operation=="upGal"){
	$uptid     = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$entry_id  = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	$imgname   = (!empty($_POST['upthumbimgname']))?FilterInput($_POST['upthumbimgname']):NULL; 
	$imgalt    = (!empty($_POST['upthumbimgalt']))?FilterInput($_POST['upthumbimgalt']):NULL; 
	if(empty($uptid) OR empty($entry_id) OR !(filter_var($uptid,FILTER_VALIDATE_INT)) OR !(filter_var($entry_id,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	$acm_chk = CheckExists("room_category","roomcat_id ='$entry_id' AND roomcat_status<>2");
	if (empty($acm_chk)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No Room Found"
		));
		die();
	}
	$data = CheckExists("room_gallery","rg_id ='$uptid' AND rg_img_status<>2");
	if (empty($data)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "No data Found"
		));
		die();
	}
	$img_file_lg = $data->rg_img_lg;
	$img_file_md = $data->rg_img_md;
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
				"msg"	 =>"Max file Size: 10MB"
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
		$dir          = "../../../images/rooms/rgallery/";

		$filename_lg  = FileName(!empty($imgname)?$imgname:$acm_chk->roomcat_name).'_'.time().rand(10000,9999999).'_lg'.'.'.$ext;
		$width        = 760;
		$height       = 400;
		$img_file_lg  = ImageProperResize($height,$width,$dir,$filename_lg,$_FILES["image"]["tmp_name"]);

		$filename_md  = FileName(!empty($imgname)?$imgname:$acm_chk->roomcat_name).'_'.time().rand(10000,9999999).'_md'.'.'.$ext;
		$width        = 600;
		$height       = 500;
		$img_file_md  = ImageProperResize($height,$width,$dir,$filename_md,$_FILES["image"]["tmp_name"]);

		if(empty($img_file_lg) OR empty($img_file_md)){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Something wrong While Uploading"
			));
			die();
		}
		if (!empty($data->rg_img_lg) AND file_exists("../../../images/rooms/rgallery/".$data->rg_img_lg)){
			@unlink("../../../images/rooms/rgallery/".$data->rg_img_lg);
		}
		if (!empty($data->rg_img_md) AND file_exists("../../../images/rooms/rgallery/".$data->rg_img_md)){
			@unlink("../../../images/rooms/rgallery/".$data->rg_img_md);
		}
	}
	$sql = "UPDATE room_gallery SET
	        rg_img_lg     = :rg_img_lg,
	        rg_img_md     = :rg_img_md,
	        rg_img_name   = :rg_img_name,
	        rg_img_alt    = :rg_img_alt,
	        rg_update_at  = :rg_update_at
	        WHERE rg_id = :rg_id";
	        $insert   = $PDO->prepare($sql);;
	        $insert->bindParam(':rg_img_lg',$img_file_lg);
	        $insert->bindParam(':rg_img_md',$img_file_md);
	        $insert->bindParam(':rg_img_name',$imgname);
	        $insert->bindParam(':rg_img_alt',$imgalt);
	        $insert->bindParam(':rg_update_at',$nowTime);
	        $insert->bindParam(':rg_id',$uptid);
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
	$chk_id = CheckExists("room_gallery","rg_id = '$id' AND rg_img_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Image"
		));
		die();
	}
	$ad  = ($operation=='deletethumb')?", rg_delete_at='$nowTime'":null;
	$sql = "UPDATE room_gallery SET rg_img_status = {$up} ".$ad. " WHERE rg_id = '$id'";
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
elseif ($operation=="orderThumb") {
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
	    $sql = "UPDATE room_gallery SET
		            rg_img_order ='$i'
		            WHERE rg_id ='$value'";
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