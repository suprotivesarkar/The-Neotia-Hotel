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
	$stmt = $PDO->prepare("SELECT * FROM quick_contact WHERE qstatus<>2 AND qsubject IS NOT NULL ORDER BY qid DESC");
	$stmt->execute(); 
	if($stmt->rowCount()>0){ ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="entry_table">
	<thead>
	<tr>
	<th>#</th>
	<th>Name</th>
	<th>Email</th>
	<th>Phone</th>
	<th>Subject</th>
	<th>&nbsp</th>
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
	<td style="color: blue; font-weight: 600;"><a href="" data-toggle="modal" data-target="#viewMod" data-id="<?php echo htmlspecialchars($qid); ?>" title="View More"><?php echo $qname; ?></a></td>
	<td><?php echo $qemail; ?></td>
	<td><?php echo $qmobile; ?></td>
	<td><?php echo $qsubject; ?></td>
	<td>
	<a href="" data-toggle="modal" data-target="#viewMod" data-id="<?php echo htmlspecialchars($qid); ?>" title="View More"><i class="fa fa-eye"></i></a> || 
	<a href="javascript:void(0);" class="statusup" title="Delete" data-id="<?php echo htmlspecialchars($qid); ?>" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    </div>
	<?php }else{echo '<div class="alert alert-warning"><p>No Data Found</p></div>'; }
}
elseif ($operation=="viewmore") {
	$id    = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
	$stmt = $PDO->prepare("SELECT * FROM quick_contact WHERE qstatus<>2 AND qsubject IS NOT NULL AND qid='$id'");
	$stmt->execute(); 
	if($stmt->rowCount()!=1){
		echo '<h1>Not Found</h1>';
		die();
	}
	$det = $stmt->fetch();
	extract($det);
	$sighttxtemail = null;
	?>
	<div class="table-responsive">
	<table class="table table-bordered">
	<tbody>
	  <tr>
	    <td>Name</td>
	    <td><?php echo $qname; ?></td>
	    <td>Phone</td>
	    <td><?php echo $qmobile; ?></td>
	  </tr>
	 <tr>
	    <td>Email</td>
	    <td><?php echo $qemail; ?></td>
	    <td>Subject</td>
	    <td><?php echo $qsubject; ?></td>
	
	   <tr>
	    <td>Message</td>
	    <td colspan="3"><?php echo $qmsg; ?></td>
	  </tr>
	  <tr> 
	    <td>IP</td>
	    <td><?php echo $qip; ?></td>
	  </tr> 
	</tbody>
	</table>
	</div>

<?php
}

elseif ($operation=="active" OR $operation=="deactive" OR $operation=="delete" OR $operation=="deletecare") {

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
	$chk_id = CheckExists("quick_contact","qid = '$id' AND qstatus<>2 AND qsubject IS NOT NULL");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", qdeleteat=NOW()":null;
	$sql = "UPDATE quick_contact SET qstatus = {$up} ".$ad. " WHERE qid= {$id}";
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


// else {
// 	echo $response = json_encode(array(
// 			"status" => false,
// 			"msg"	 =>" Something Wrong"
// 	));
// 	die();
// }
