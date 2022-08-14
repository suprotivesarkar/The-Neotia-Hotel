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
if($operation=="updatepass") {
    $oldpass   = (!empty($_POST['oldpass']))?FilterInput($_POST['oldpass']):null; 
	$newpass   = (!empty($_POST['newpass']))?FilterInput($_POST['newpass']):null; 

	if(empty($oldpass) OR empty($newpass)) {
		echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Enter Passwords"
		));
		die();
	}
	$chk_exists = CheckExists("users_list","user_password = '$oldpass' AND user_status=1");
	if (empty($chk_exists)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Incorrect Current Password"
		));
		die();
	}
	$sql = "UPDATE users_list SET
	        user_password    = :user_password
	        WHERE user_id=:user_id";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':user_password',$newpass);
	        $insert->bindParam(':user_id',$_SESSION['adminid']);
	        $insert->execute();
	        if($insert->rowCount() > 0){
	        	echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => "Successfully Updated"
				));
	        }else {
	        	echo $response = json_encode(array(
					"status" =>false,
					"msg"	 =>"No Change Done"
				));
			}
}