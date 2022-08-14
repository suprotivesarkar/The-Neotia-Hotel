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
	$stmt = $PDO->prepare("SELECT * FROM emp_cat WHERE cat_status<>2 ORDER BY cat_id ");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>CATEGORY</th>
	<th>NO. OF EMPLOYEE</th>
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
	<tr>
		<?php 
		$s = "SELECT COUNT(*) as count FROM users_list WHERE user_cat_id_ref='$cat_id' AND user_status<>2";
			$s = $PDO->prepare($s);
			$s->execute(); 
			$det =  $s->fetch();
		    $data = $det['count'];
		?>
	<td><?php echo $i++; ?></td>
	<td><?php echo $cat_name; ?></td>
	<td><?php echo $data; ?></td>
	<td><?php echo StatusReport($cat_status);  ?></td>
	<td> 
	
	<?php  
    if ($cat_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($cat_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($cat_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['cat_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($cat_id); ?>" data-name="<?php echo htmlspecialchars($cat_name); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($cat_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="addnew") {
	// $pname    = (!empty($_POST['pname']))?FilterInput($_POST['pname']):null;   
	$name     = (!empty($_POST['name']))?FilterInput($_POST['name']):null;
	if(empty($name)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	// if(!empty($pid)) {
	// 	$chk_parent = CheckExists("emp_cat","cat_id = '$pid' AND cat_status<>2");
	// 	if (empty($chk_parent)) {
	// 		echo $response = json_encode(array(
	// 				"status" => false,
	// 				"msg"	 => "Parent Not Found"
	// 		));
	// 		die();
	// 	}
	// }
	$chk_slug = CheckExists("emp_cat","(cat_name = '$name') AND cat_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "INSERT INTO emp_cat SET
	        cat_name      = :cat_name";
	        $insert = $PDO->prepare($sql);;
	        $insert->bindParam(':cat_name',$name);
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
	// $uppname   = (!empty($_POST['uppname']))?FilterInput($_POST['uppname']):null; 
    // $uppid     = (!empty($_POST['uppid']))?FilterInput($_POST['uppid']):NULL; 
	$upname    = (!empty($_POST['upname']))?FilterInput($_POST['upname']):null;  

	if( empty($upname) ){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	// if(!empty($uppid)) {
	// 	$chk_parent = CheckExists("emp_cat","cat_id = '$uppid' AND cat_status<>2");
	// 	if (empty($chk_parent)) {
	// 		echo $response = json_encode(array(
	// 				"status" => false,
	// 				"msg"	 => "Parent Not Found"
	// 		));
	// 		die();
	// 	}
	// }
	$chk_id = CheckExists("emp_cat","cat_id = '$uptid' AND cat_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	// $chk_slug = CheckExists("emp_cat","(cat_slug = '$upnameurl' OR cat_name = '$upname') AND cat_id<>'$uptid' AND cat_status<>2");
	// if (!empty($chk_slug)) {
	// 	echo $response = json_encode(array(
	// 			"status" => false,
	// 			"msg"	 => "This Name Already Exists"
	// 	));
	// 	die();
	// }
	$sql = "UPDATE emp_cat SET
		       
		        cat_name      = :cat_name
	            WHERE cat_id=:cat_id";
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':cat_name',$upname);
	            $insert->bindParam(':cat_id',$uptid);
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
// elseif($operation=="updatemore") {
// 	$uptid           = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
// 	$desthumbalt     = (!empty($_POST['desthumbalt']))?FilterInput($_POST['desthumbalt']):null; 
// 	$metatitle       = (!empty($_POST['metatitle']))?FilterInput($_POST['metatitle']):null; 
// 	$metadesc        = (!empty($_POST['metadesc']))?FilterInput($_POST['metadesc']):null; 
// 	$metatitlesocial = (!empty($_POST['metatitlesocial']))?FilterInput($_POST['metatitlesocial']):null; 
// 	$metadescsocial  = (!empty($_POST['metadescsocial']))?FilterInput($_POST['metadescsocial']):null; 

// 	if(empty($uptid) OR !(filter_var($uptid,FILTER_VALIDATE_INT))) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Data Not Found"
// 		));
// 		die();
// 	}
// 	$chk_exists = CheckExists("emp_cat","cat_id = '$uptid' AND cat_status<>2");
// 	if (empty($chk_exists)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Data Not Exists"
// 		));
// 		die();
// 	}

// 	$img_thumb=$chk_exists->cat_thumb;
// 	if(!empty($_FILES['image']['name'])){
// 		$valid_ext = array('jpeg', 'jpg', 'png'); 
// 		$maxsize   = 5 * 1024 * 1024;

// 		$imgFile  = stripslashes($_FILES['image']['name']);
// 		$tmpName  = $_FILES['image']['tmp_name'];
// 		$imgType  = $_FILES['image']['type'];
// 		$imgSize  = $_FILES['image']['size'];

// 		$ext = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
// 		if($imgType!='image/jpeg' && $imgType!='image/jpg' && $imgType!='image/png') {
// 			echo $response = json_encode(array(
// 				"status" =>false, 
// 				"msg"	 =>"Image Type Shoud be JPG OR PNG OR JPEG"
// 			));
// 			die();
// 		}
// 		if ($imgSize>$maxsize) {
// 			echo $response = json_encode(array(
// 				"status" =>false, 
// 				"msg"	 =>"Max file Size: 7MB"
// 			));
// 			die();
// 		}
// 		if(!in_array($ext, $valid_ext)) {
// 			echo $response = json_encode(array(
// 				"status" =>false, 
// 				"msg"	 =>"Image Extention Should be jpg or png or jpeg"
// 			));
// 			die();
// 		}
// 		$width=450;$height=170;
// 		$dir="../../../product/category/"; 
// 		$img_thumb = FileName($chk_exists->cat_slug).'_'.time().rand(10000,999999999).'.'.$ext;
// 		$img_file  = resize($width,$height,$dir,$img_thumb);
// 		if ((!empty($chk_exists->des_thumb)) AND file_exists("../../../product/category/".$chk_exists->des_thumb)) {
// 			@unlink("../../../product/category/".$chk_exists->des_thumb);
// 		}
// 	}

// 	$fbimg=$chk_exists->cat_facebook_cover;
// 	if(!empty($_FILES['fbimg']['name'])) {
// 		$imgnm     = stripslashes($_FILES['fbimg']['name']);
// 		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
// 		$filename  = FileName($chk_exists['cat_slug']).'_'.time().rand(10000,999999999).'.'.$ext;
// 		if (move_uploaded_file($_FILES['fbimg']['tmp_name'], "../../../images/category/social/$filename")) {
// 	        $fbimg = $filename;
// 	        if ((!empty($chk_exists->cat_facebook_cover)) AND file_exists("../../../images/category/social/".$chk_exists->cat_facebook_cover)) {
// 				@unlink("../../../images/category/social/".$chk_exists->cat_facebook_cover);
// 			}
// 	    }
// 	}
// 	$twimg=$chk_exists->cat_twitter_cover;
// 	if(!empty($_FILES['twimg']['name'])){
// 		$imgnm     = stripslashes($_FILES['twimg']['name']);
// 		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
// 		$filename  = FileName($chk_exists->cat_slug).'_'.time().rand(10000,999999999).'.'.$ext;
// 		if (move_uploaded_file($_FILES['twimg']['tmp_name'], "../../../images/category/social/$filename")) {
// 	        $twimg = $filename;
// 	        if ((!empty($chk_exists->cat_twitter_cover)) AND file_exists("../../../images/category/social/".$chk_exists->cat_twitter_cover)) {
// 				@unlink("../../../images/category/social/".$chk_exists->cat_twitter_cover);
// 			}
// 	    }
// 	}



// 	$sql = "UPDATE emp_cat SET
// 		        cat_update_at               = :cat_update_at
// 	            WHERE cat_id=:cat_id"; 
// 	            $insert = $PDO->prepare($sql);
// 		       $insert->bindParam(':cat_update_at',$nowTime);
// 	            $insert->bindParam(':cat_id',$uptid);
// 		        $insert->execute();
// 	            if($insert->rowCount() > 0){
// 	            	echo $response = json_encode(array(
// 						"status" =>true, 
// 						"msg"	 => "Successfully Updated"
// 					));
// 	            }else {
// 	            	echo $response = json_encode(array(
// 						"status" =>false,
// 						"msg"	 =>"No Change Done"
// 					));
// 	   			}
// }
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
	$chk_id = CheckExists("emp_cat","cat_id = '$id' AND cat_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", cat_delete_at=NOW()":null;
	$sql = "UPDATE emp_cat SET cat_status= {$up} ".$ad. " WHERE cat_id= {$id}";
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