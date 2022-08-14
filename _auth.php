<?php
//sleep(3);
require_once("_top.php");  
$operation =(!empty($_POST['operation']))?FilterInput($_POST['operation']):null;
if(empty($operation)){
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Something Wrong"
	));
	die();
}
if($operation=="reg"){
$regname  = (!empty($_POST['name']))?FilterInput($_POST['name']):null;
$regmail  = (!empty($_POST['email']))?FilterInput($_POST['email']):null;
$regphone = (!empty($_POST['phone']))?FilterInput($_POST['phone']):null;
$regpass  = (!empty($_POST['password']))?FilterInput($_POST['password']):null;
$cpassword  = (!empty($_POST['cpassword']))?FilterInput($_POST['cpassword']):null;

if(empty($regname) OR empty($regmail) OR empty($regphone) OR empty($regpass) OR empty($cpassword)){
	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Field is Empty</div>'
	));
	die();
}
if(!filter_var($regmail,FILTER_VALIDATE_EMAIL)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Email is Wrong</div>'
	));
	die();
} 
if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$regphone) OR !filter_var($regphone, FILTER_VALIDATE_INT)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Phone Number Invalid</div>'
	));
	die();
} 
if(strlen($regpass)>13 OR strlen($regpass)<=5) {
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Password Length Should be 6 to 12.</div>'
	));
	die();
} 
if($regpass != $cpassword){
	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Password does not match.</div>'
	));
	die();
}

$stmt = $PDO->prepare("SELECT * FROM members WHERE (mem_email=:email OR mem_phone=:phone) AND mem_status<>2");
$stmt->execute(['email' => $regmail,'phone' => $regphone]); 
if($stmt->rowCount()>0){ 
	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">You already Exist..Try Login Instead</div>'
	));
	die();
}
$sql = "INSERT INTO members SET
    mem_name     = :name,
    mem_phone    = :phone,
    mem_email    = :email, 
    mem_password = :pass";
    $insert = $PDO->prepare($sql);
    $insert->bindParam(':name',$regname);
    $insert->bindParam(':phone',$regphone);
    $insert->bindParam(':email',$regmail);
    $insert->bindParam(':pass',$regpass);
    $insert->execute();
    if($insert->rowCount()>0){
	    $_SESSION['userid']   = $PDO->lastInsertId();
	        $memid = $_SESSION['userid'];

    	echo $response = json_encode(array(
			"status" => true, 
			"msg"	 => '<div class="alert alert-success">Success</div>'
		));
    }else{
    	echo $response = json_encode(array(
			"status" =>false,
			"msg"	 =>'<div class="alert alert-danger">Something Wrong</div>'
		));
	}
}
elseif ($operation=="login"){
	$username  = (!empty($_POST['username']))?FilterInput($_POST['username']):null;
	$password  = (!empty($_POST['password']))?FilterInput($_POST['password']):null;
	if(empty($username) OR empty($password)){
		echo $response = json_encode(array(
				"status" =>false,
				"msg"	 =>'<div class="alert alert-danger">Field is Empty</div>'
		));
		die();
	}
	$stmt = $PDO->prepare("SELECT * FROM members WHERE (mem_phone=:mem_phone OR mem_email=:mem_email) AND mem_status=1 AND mem_password=:password");
	$stmt->execute(['mem_phone' => $username,'mem_email' => $username,'password' => $password]); 
	if($stmt->rowCount()==1){ 
		$row = $stmt->fetch();
	    $_SESSION['userid']   = $row['mem_id']; 
	    $memid = $_SESSION['userid'];
		echo $response = json_encode(array(
			"status" => true,
			"msg"	 => '<div class="alert alert-success">Success</div>',
		));
		die();
	}else{
		echo $response = json_encode(array(
			"status" => false,
			"msg"	 => '<div class="alert alert-danger">Wrong User</div>'
		));
		die();
	}
}


elseif($operation=="updateset") {
$regmail  = (!empty($_POST['emails']))?FilterInput($_POST['emails']):null;
$regphone = (!empty($_POST['phnno']))?FilterInput($_POST['phnno']):null;
$regpass  = (!empty($_POST['password']))?FilterInput($_POST['password']):null;
if(empty($regmail) OR empty($regphone) OR empty($regpass)){
	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Field is Empty</div>'
	));
	die();
}
if(!filter_var($regmail,FILTER_VALIDATE_EMAIL)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Email is Wrong</div>'
	));
	die();
} 
if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$regphone) OR !filter_var($regphone, FILTER_VALIDATE_INT)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Phone Number Invalid</div>'
	));
	die();
} 
if(strlen($regpass)>13 OR strlen($regpass)<=5) {
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => '<div class="alert alert-danger">Password Length Should be 6 to 12.</div>'
	));
	die();
} 
		$memid = $_SESSION['userid'];

		$sql = "UPDATE members SET
				mem_phone    = :mem_phone,
			    mem_email    = :mem_email, 
			    mem_password = :mem_password
			   WHERE mem_id       = :mem_id";
			    $insert = $PDO->prepare($sql);
			    $insert->bindParam(':mem_id',$memid);
			    $insert->bindParam(':mem_phone',$regphone);
			    $insert->bindParam(':mem_email',$regmail);
			    $insert->bindParam(':mem_password',$regpass);
			    $insert->execute();
			    if($insert->rowCount() > 0){
	        	echo $response = json_encode(array(
					"status" => true, 
					"msg"	 => '<div class="alert alert-success">Successfully Updated!</div>',
				));
	        }else {
	        	echo $response = json_encode(array(
					"status" =>false,
					"msg"	 =>'<div class="alert alert-danger">No Changes Done!</div>',
				));
			}

        }