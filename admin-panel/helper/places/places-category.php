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
	$stmt = $PDO->prepare("SELECT * FROM places_category WHERE places_category_status<>2 ORDER BY places_category_order ASC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>INNER</th>
	<th>THUMB</th>
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
	<tr id="<?php echo $places_category_id; ?>">
	<td><?php echo $i++; ?></td>
	<td>
	<?php
	if((!empty($places_category_cover)) AND file_exists("../../../upload/".$places_category_cover)) {
		echo '<img src="../upload/'.$places_category_cover.'" height="20">';
	}else{
		echo '-';
	}
	?>
	</td>
	<td>
	<?php
	if((!empty($places_category_thumb)) AND file_exists("../../../upload/".$places_category_thumb)) {
		echo '<img src="../upload/'.$places_category_thumb.'" height="20">';
	}else{
		echo '-';
	}
	?>
	</td>
	<td><?php echo $places_category_name; ?></td>
	<td><?php echo $places_category_slug; ?></td>
	<td><?php echo StatusReport($places_category_status);  ?></td>
	<td>
	<?php  
    if ($places_category_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($places_category_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($places_category_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $places_category_id; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($places_category_id); ?>" data-name="<?php echo htmlspecialchars($places_category_name); ?>" data-url="<?php echo htmlspecialchars($places_category_slug); ?>" data-metatitle="<?php echo htmlspecialchars($places_category_meta_title); ?>" data-metadesc="<?php echo htmlspecialchars($places_category_meta_desc); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($places_category_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$name       = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
	$nameurl    = (!empty($_POST['nameurl']))?FilterInput($_POST['nameurl']):null;  
	$metatitle  = (!empty($_POST['metatitle']))?FilterInput($_POST['metatitle']):null;  
	$metadesc   = (!empty($_POST['metadesc']))?FilterInput($_POST['metadesc']):null;  
	if(empty($name) OR empty($nameurl)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	$chk_slug = CheckExists("places_category","(places_category_slug = '$nameurl' OR places_category_name = '$name') AND places_category_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}


	$img_thumb=null;
	if(!empty($_FILES['image']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 5 * 1024 * 1024;

		$imgFile  = stripslashes($_FILES['image']['name']);
		$tmpName  = $_FILES['image']['tmp_name'];
		$imgType  = $_FILES['image']['type'];
		$imgSize  = $_FILES['image']['size'];

		$ext = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
		if($imgType!='image/jpeg' && $imgType!='image/jpg' && $imgType!='image/png') {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
			));
			die();
		}
		if ($imgSize>$maxsize) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
			));
			die();
		}
		$width=500;$height=400;
		$dir="../../../upload/"; 
		$img_thumb = FileName($name).'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resize($width,$height,$dir,$img_thumb);
	}


	$img_inner=NULL;
	if(!empty($_FILES['image2']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 5 * 1024 * 1024;

		$imgFile  = stripslashes($_FILES['image2']['name']);
		$tmpName  = $_FILES['image2']['tmp_name'];
		$imgType  = $_FILES['image2']['type'];
		$imgSize  = $_FILES['image2']['size'];

		$ext = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
		if($imgType!='image/jpeg' && $imgType!='image/jpg' && $imgType!='image/png') {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
			));
			die();
		}
		if ($imgSize>$maxsize) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
			));
			die();
		}
		$width=1920;$height=500;
		$dir="../../../upload/"; 
		$img_inner = FileName($name)."_cover_".'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resizeInnerImage($width,$height,$dir,$img_inner);
	} 
	$sql = "INSERT INTO places_category SET
	        places_category_slug       = :places_category_slug,
	        places_category_name       = :places_category_name,
	        places_category_thumb      = :places_category_thumb,
	        places_category_cover      = :places_category_cover,
	        places_category_meta_title = :places_category_meta_title,
	        places_category_meta_desc  = :places_category_meta_desc";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':places_category_slug',$nameurl);
	        $insert->bindParam(':places_category_name',$name);
	        $insert->bindParam(':places_category_thumb',$img_thumb);
	        $insert->bindParam(':places_category_cover',$img_inner);
	        $insert->bindParam(':places_category_meta_title',$metatitle);
	        $insert->bindParam(':places_category_meta_desc',$metadesc);
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
	$metatitle  = (!empty($_POST['upmetatitle']))?FilterInput($_POST['upmetatitle']):null;  
	$metadesc   = (!empty($_POST['upmetadesc']))?FilterInput($_POST['upmetadesc']):null; 

	if(empty($uptid) OR empty($upname) OR empty($upnameurl) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("places_category","places_category_id = '$uptid' AND places_category_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$chk_slug = CheckExists("places_category","(places_category_slug = '$upnameurl' OR places_category_name = '$upname') AND places_category_id<>'$uptid' AND places_category_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}


	$img_thumb=$chk_id->places_category_thumb;
	if(!empty($_FILES['image']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 5 * 1024 * 1024;

		$imgFile  = stripslashes($_FILES['image']['name']);
		$tmpName  = $_FILES['image']['tmp_name'];
		$imgType  = $_FILES['image']['type'];
		$imgSize  = $_FILES['image']['size'];

		$ext = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
		if($imgType!='image/jpeg' && $imgType!='image/jpg' && $imgType!='image/png') {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
			));
			die();
		}
		if ($imgSize>$maxsize) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
			));
			die();
		}
		$width=500;$height=400;
		$dir="../../../upload/"; 
		$img_thumb = FileName($upname).'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resize($width,$height,$dir,$img_thumb);
		if ((!empty($chk_id->places_category_thumb)) AND file_exists("../../../upload/".$chk_id->places_category_thumb)) {
			@unlink("../../../upload/".$chk_id->places_category_thumb);
		}
	}

	$img_inner=$chk_id->places_category_cover;
	if(!empty($_FILES['image2']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 5 * 1024 * 1024;

		$imgFile  = stripslashes($_FILES['image2']['name']);
		$tmpName  = $_FILES['image2']['tmp_name'];
		$imgType  = $_FILES['image2']['type'];
		$imgSize  = $_FILES['image2']['size'];

		$ext = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
		if($imgType!='image/jpeg' && $imgType!='image/jpg' && $imgType!='image/png') {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
			));
			die();
		}
		if ($imgSize>$maxsize) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
			));
			die();
		}
		$width=1920;$height=500;
		$dir="../../../upload/"; 
		$img_inner = FileName($upname)."_cover_".'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resizeInnerImage($width,$height,$dir,$img_inner);
		if ((!empty($chk_id->places_category_cover)) AND file_exists("../../../upload/".$chk_id->places_category_cover)) {
			@unlink("../../../upload/".$chk_id->places_category_cover);
		}
	} 
	$sql = "UPDATE places_category SET
		        places_category_slug     = :places_category_slug,
		        places_category_name     = :places_category_name,
		        places_category_thumb    = :places_category_thumb,
		        places_category_cover    = :places_category_cover,
		        places_category_meta_title   = :places_category_meta_title,
		        places_category_meta_desc    = :places_category_meta_desc
	            WHERE places_category_id=:places_category_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':places_category_slug',$upnameurl);
		        $insert->bindParam(':places_category_name',$upname);
		        $insert->bindParam(':places_category_thumb',$img_thumb);
		        $insert->bindParam(':places_category_cover',$img_inner);
		        $insert->bindParam(':places_category_meta_title',$metatitle);
		        $insert->bindParam(':places_category_meta_desc',$metadesc);
	            $insert->bindParam(':places_category_id',$uptid);
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
	$chk_id = CheckExists("places_category","places_category_id = '$id' AND places_category_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", places_category_delete_at='$nowTime'":null;
	$sql = "UPDATE places_category SET places_category_status= {$up} ".$ad. " WHERE places_category_id= {$id}";
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
	    $sql = "UPDATE places_category SET
		            places_category_order ='$i'
		            WHERE places_category_id ='$value'";
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