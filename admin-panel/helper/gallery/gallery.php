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
	$stmt = $PDO->prepare("SELECT * FROM gallery ORDER BY gal_id DESC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>Image</th>
	<th>Name</th>
	<th>STATUS</th>
	<th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);	?> 
	<tr>
	<td><?php echo $i++; ?></td>
	<td><img height="30" src="../images/gallery/<?php echo $gal_img;  ?>"></td>
	<td><?php echo $gal_img_name; ?></td>
	<td><?php echo StatusReport($gal_img_status);  ?></td>
	<td>
	<?php  
    if ($gal_img_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($gal_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($gal_img_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['gal_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($gal_id); ?>" data-pid="<?php echo htmlspecialchars($gal_img_name); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($gal_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Image Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$imagename  = (!empty($_POST['imagename']))?FilterInput($_POST['imagename']):null; 

	if(empty($_FILES['image']['name'])){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select a Image"
		));
		die();
	}
	$valid_ext = array('jpeg', 'jpg', 'png'); 
	$maxsize   = 15 * 1024 * 1024;

	$imgFile1  = stripslashes($_FILES['image']['name']);
	$tmpNm1    = $_FILES['image']['tmp_name'];
	$imgTyp1   = $_FILES['image']['type'];
	$imgSize1  = $_FILES['image']['size'];

	$ext = strtolower(pathinfo($imgFile1, PATHINFO_EXTENSION));

	if($imgTyp1!='image/jpeg' && $imgTyp1!='image/jpg' && $imgTyp1!='image/png'){
		echo $response = json_encode(array(
			"status" =>false, 
			"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
		));
		die();
	}
	if($imgSize1>$maxsize){
		echo $response = json_encode(array(
			"status" =>false, 
			"msg"	 =>"Max file Size: 15MB"
		));
		die();
	}
	if(!in_array($ext, $valid_ext)){
		echo $response = json_encode(array(
			"status" =>false, 
			"msg"	 =>"Image Extention Should be jpg or png or jpeg"
		));
		die();
	}

	$width    = 400;
	$width_lg = 800;
	$dir    = "../../../images/gallery/";
	if (!empty($imagename)) {
		$filename    = FileName($imagename).'_'.time().rand(10000,999999999).'.'.$ext;
		$filename_lg = FileName($imagename).'_'.time().rand(10000,999999999).'_lg'.'.'.$ext;
	}
	else {
		$filename    = time().rand(10000,999999999).'.'.$ext;
		$filename_lg = time().rand(10000,999999999).'_lg'.'.'.$ext;
	}
	$img_file    = MaxResize($width,$dir,$filename,$_FILES["image"]["tmp_name"]);
	$img_file_lg = MaxResize($width_lg,$dir,$filename_lg,$_FILES["image"]["tmp_name"]);

	if(empty($img_file)) {
		echo $response = json_encode(array(
			"status" =>false, 
			"msg"	 =>"Something wrong While Uploading"
		));
		die();
	}

	$sql = "INSERT INTO gallery SET
	        gal_img       = :gal_img,
	        gal_img_lg    = :gal_img_lg,
	        gal_img_name  = :gal_img_name";
	        $insert = $PDO->prepare($sql);;
	        $insert->bindParam(':gal_img',$img_file);
	        $insert->bindParam(':gal_img_lg',$filename_lg);
	        $insert->bindParam(':gal_img_name',$imagename);
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
	$uptid      = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$imagename  = (!empty($_POST['upimagename']))?FilterInput($_POST['upimagename']):null; 

	$chk_id = CheckExists("gallery","gal_id = '$uptid' AND gal_img_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Image"
		));
		die();
	}

	$img_file     = $chk_id->gal_img;
	$img_file_lg  = $chk_id->gal_img_lg;
	if(!empty($_FILES['image']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 15 * 1024 * 1024;

		$imgFile1  = stripslashes($_FILES['image']['name']);
		$tmpNm1    = $_FILES['image']['tmp_name'];
		$imgTyp1   = $_FILES['image']['type'];
		$imgSize1  = $_FILES['image']['size'];

		$ext = strtolower(pathinfo($imgFile1, PATHINFO_EXTENSION));

		if($imgTyp1!='image/jpeg' && $imgTyp1!='image/jpg' && $imgTyp1!='image/png'){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
			));
			die();
		}
		if($imgSize1>$maxsize){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 15MB"
			));
			die();
		}
		if(!in_array($ext, $valid_ext)){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
			));
			die();
		}

		$width    = 400;
		$width_lg = 800;
		$dir    = "../../../images/gallery/";
		if (!empty($imagename)) {
			$filename    = FileName($imagename).'_'.time().rand(10000,999999999).'.'.$ext;
			$filename_lg = FileName($imagename).'_'.time().rand(10000,999999999).'_lg'.'.'.$ext;
		}
		else {
			$filename    = time().rand(10000,999999999).'.'.$ext;
			$filename_lg = time().rand(10000,999999999).'_lg'.'.'.$ext;
		}
		$img_file    = MaxResize($width,$dir,$filename,$_FILES["image"]["tmp_name"]);
		$img_file_lg = MaxResize($width_lg,$dir,$filename_lg,$_FILES["image"]["tmp_name"]);

		if(empty($img_file)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Something wrong While Uploading"
			));
			die();
		}
		if (!empty($chk_id->gal_img) AND file_exists("../../../images/gallery/".$chk_id->gal_img)){
			@unlink("../../../images/gallery/".$chk_id->gal_img);
			@unlink("../../../images/gallery/".$chk_id->gal_img_lg);
		}
	}
	
	$gal_img_name  = $chk_id->gal_img_name;
	if (!empty($imagename)) {
		$gal_img_name  = $imagename;
	}

	$sql = "UPDATE gallery SET
	        gal_img       = :gal_img,
	        gal_img_lg    = :gal_img_lg,
	        gal_img_name  = :gal_img_name
	        WHERE gal_id=:gal_id";
	        $insert = $PDO->prepare($sql);;
	        $insert->bindParam(':gal_img',$img_file);
	        $insert->bindParam(':gal_img_lg',$img_file_lg);
	        $insert->bindParam(':gal_img_name',$imagename);
	        $insert->bindParam(':gal_id',$uptid);
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
elseif ($operation=="active" OR $operation=="deactive" OR $operation=="delete") {

	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	if(empty($id) AND !is_numeric($id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Something Wrong"
		));
		die();
	}
	$chk_id = CheckExists("gallery","gal_id = '$id' AND gal_img_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Image"
		));
		die();
	}
	switch ($operation) {
		case 'active':
			$sql = "UPDATE gallery SET gal_img_status=1 WHERE gal_id= {$id}";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Activated"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
		case 'deactive':
			$sql = "UPDATE gallery SET gal_img_status=0 WHERE gal_id= {$id}";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Deactivated"
				));
			}else {
				echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "No Change Done"
				));
			}
			break;
		case 'delete':
			$sql = "DELETE FROM gallery WHERE gal_id= {$id}";
			$insert = $PDO->prepare($sql);
			$insert->execute();
			if($insert->rowCount() > 0){
				if (!empty($chk_id['gal_img']) AND file_exists("../../../images/gallery/".$chk_id['gal_img'])){
					@unlink("../../../images/gallery/".$chk_id['gal_img']);
					@unlink("../../../images/gallery/".$chk_id['gal_img_lg']);
				}
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
else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}