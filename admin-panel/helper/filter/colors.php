<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
	echo $response = json_encode(array(
			"status" =>false,
			"msg"	 => "Unauthorized Access"
	));
	die();
}
$operation =trim($_POST['operation']);
if (empty($operation)){
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Something Wrong"
	));
	die();
}
if ($operation=="fetch"){
	$stmt = $PDO->prepare("SELECT * FROM filter_color WHERE color_status<>2 ORDER BY color_id DESC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>NAME</th>
	<th>URL</th>
	<th>COLOR</th>
	<th>STATUS</th>
	<th>ACTIONS</th>
	</tr>
	</thead>
	<tbody> 
	<?php   
	$i=1;
	while ($row=$stmt->fetch()){extract($row); ?> 
	<tr>
	<td><?php echo $i++; ?></td>
	<td><?php echo $color_name; ?></td>
	<td><?php echo $color_slug; ?></td>
	<td><?php echo $color_code; ?></td>
	<td><?php echo StatusReport($color_status);  ?></td>
	<td>
	<?php 
    if ($color_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-inverse statusup" data-id="<?php echo htmlspecialchars($color_id); ?>" data-operation="active"><i class="fa fa-check"></i> || </a>
    <?php }else if($color_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-inverse statusup" data-id="<?php echo $row['color_id']; ?>" data-operation="deactive"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
	<a href="" data-toggle="modal" data-target="#upMod" class="editbtn" title="Update" data-id="<?php echo htmlspecialchars($color_id); ?>" data-name="<?php echo htmlspecialchars($color_name); ?>" data-url="<?php echo htmlspecialchars($color_slug); ?>" data-code="<?php echo htmlspecialchars($color_code); ?>"><i class="fa fa-edit"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($color_id); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Color Found</p></div>'; }
}
elseif ($operation=="addnew") {
	$name      = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
	$nameurl   = (!empty($_POST['nameurl']))?FilterInput($_POST['nameurl']):null; 
	$colorcode = (!empty($_POST['colorcode']))?FilterInput($_POST['colorcode']):null; 
	if(empty($name)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	$stmt = $PDO->prepare("SELECT * FROM filter_color WHERE (color_name=:color_name OR color_slug=:slug) AND color_status<>2");
	$stmt->execute(['color_name' => $name, 'slug' => $nameurl]); 
	if($stmt->rowCount()>0){ 
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => " This is Already Exists"
		));
		die();
	}
	$sql = "INSERT INTO filter_color SET
	        color_name  = :name,
	        color_slug  = :nameurl,
	        color_code  = :color_code";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':name',$name);
	        $insert->bindParam(':nameurl',$nameurl);
	        $insert->bindParam(':color_code',$colorcode);
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
	
	$uptid        = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$upname       = (!empty($_POST['upname']))?FilterInput($_POST['upname']):null; 
	$upnameurl    = (!empty($_POST['upnameurl']))?FilterInput($_POST['upnameurl']):null; 
	$upcolorcode  = (!empty($_POST['upcolorcode']))?FilterInput($_POST['upcolorcode']):null; 


	if(empty($uptid) OR empty($upname) OR empty($upnameurl) OR !is_numeric($uptid)){
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Fields is Empty"
		));
		die();
	}
	$stmt = $PDO->prepare("SELECT * FROM filter_color WHERE color_id=:color_id AND color_status<>2");
	$stmt->execute(['color_id' => $uptid]); 
	if($stmt->rowCount()<=0){ 
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$stmt = $PDO->prepare("SELECT * FROM filter_color WHERE (color_name=:color_name OR color_slug=:slug)  AND color_id<>:id AND color_status<>2");
	$stmt->execute(['color_name' => $upname, 'slug' => $upnameurl, 'id' => $uptid]); 
	if($stmt->rowCount()>0){ 
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => " This is Already Exists"
		));
		die();
	}
	$sql = "UPDATE filter_color SET
	            color_name  = :color_name,
	            color_slug  = :color_slug,
	            color_code  = :color_code
	            WHERE color_id=:color_id";
	            $insert = $PDO->prepare($sql);
	            $insert->bindParam(':color_name',$upname);
	            $insert->bindParam(':color_slug',$upnameurl);
	            $insert->bindParam(':color_code',$upcolorcode);
	            $insert->bindParam(':color_id',$uptid);
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
	$stmt = $PDO->prepare("SELECT * FROM filter_color WHERE color_id=:id");
	$stmt->execute(['id' => $id]); 
	if ($stmt->rowCount()!=1){ 
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => " Cant Find to Activate"
		));
		die();
	}
	$ad = ($operation=='delete')?", color_delete_at=NOW()":null;
	$sql = "UPDATE filter_color SET color_status= {$up} ".$ad. " WHERE color_id= {$id}";
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
}else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}