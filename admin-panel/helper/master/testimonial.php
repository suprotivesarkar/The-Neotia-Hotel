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
	$stmt = $PDO->prepare("SELECT * FROM testimonials WHERE testi_status<>2 ORDER BY testi_id DESC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>IMAGE</th>
	<th>NAME</th>
	<th>STATUS</th>
	<th>ACTION</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){
	extract($row);
	?>
	<tr>
	<td><?php echo $i++; ?></td>
	<td>
	<?php
	if((!empty($testi_img)) AND file_exists("../../../images/testimonial/".$testi_img)) {
		echo '<img src="../images/testimonial/'.$testi_img.'" height="20">';
	}else{
		echo '<img src="../images/testimonial/user.png" height="20">';
	}
	?>		
	</td>
	<td><?php echo $testi_name; ?></td>
	<td><?php echo StatusReport($testi_status);  ?></td>
	<td>
	<?php  
    if ($testi_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="<?php echo htmlspecialchars($testi_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($testi_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="<?php echo $row['testi_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" data-id="<?php echo htmlspecialchars($testi_id); ?>" data-name="<?php echo htmlspecialchars($testi_name); ?>" data-txt="<?php echo htmlspecialchars($testi_text); ?>"><i class="fa fa-edit"> || </i></a> ||  
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($testi_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Testimonials Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$name     = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
	$msg      = (!empty($_POST['msg']))?FilterInput($_POST['msg']):null; 
	if(empty($name)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	if(empty($msg)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Message"
		));
		die();
	}
	$img_thumb=NULL;
	if(!empty($_FILES['image']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 2 * 1024 * 1024;

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
				"msg"	 =>"Max file Size: 2MB"
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
		$width=90;$height=90;
		$dir="../../../images/testimonial/"; 
		$img_thumb = FileName($name).'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resize($width,$height,$dir,$img_thumb);
	}

	$sql = "INSERT INTO testimonials SET
	        testi_name   = :testi_name,
	        testi_text    = :testi_text,
	        testi_img    = :testi_img";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':testi_name',$name);
	        $insert->bindParam(':testi_text',$msg);
	        $insert->bindParam(':testi_img',$img_thumb);
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
	$uptid    = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
    $upname   = (!empty($_POST['upname']))?FilterInput($_POST['upname']):null; 
	$upmsg    = (!empty($_POST['upmsg']))?FilterInput($_POST['upmsg']):null; 

	if(empty($uptid) OR empty($upname) OR empty($upmsg)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$chk_id = CheckExists("testimonials","testi_id = '$uptid' AND testi_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	
	$img_thumb=$chk_id->testi_img;
	if(!empty($_FILES['image']['name'])){
		$valid_ext = array('jpeg', 'jpg', 'png'); 
		$maxsize   = 2 * 1024 * 1024;

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
				"msg"	 =>"Max file Size: 2MB"
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
		$width=90;$height=90;
		$dir="../../../images/testimonial/"; 
		$img_thumb = FileName($upname).'_'.time().rand(10000,999999999).'.'.$ext;
		$img_file  = resize($width,$height,$dir,$img_thumb);
		if ((!empty($chk_id->testi_img)) AND file_exists("../../../images/testimonial/".$chk_id->testi_img)) {
			@unlink("../../../images/testimonial/".$chk_id->testi_img);
		}
	}

	$sql = "UPDATE testimonials SET
	            testi_name     = :testi_name,
		        testi_text      = :testi_text,
		        testi_img      = :testi_img
	            WHERE testi_id=:testi_id";
	            $insert = $PDO->prepare($sql);
	            $insert->bindParam(':testi_name',$upname);
		        $insert->bindParam(':testi_text',$upmsg);
		        $insert->bindParam(':testi_img',$img_thumb);
	            $insert->bindParam(':testi_id',$uptid);
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
	$chk_id = CheckExists("testimonials","testi_id = '$id' AND testi_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", testi_delete_at=NOW()":null;
	$sql = "UPDATE testimonials SET testi_status= {$up} ".$ad. " WHERE testi_id= {$id}";
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