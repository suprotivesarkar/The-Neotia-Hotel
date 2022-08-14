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
	$oldid     = (!empty($_POST['oldid']))?FilterInput($_POST['oldid']):null; 
    $oldpass   = (!empty($_POST['oldpass']))?FilterInput($_POST['oldpass']):null; 
	$newid     = (!empty($_POST['newid']))?FilterInput($_POST['newid']):null; 
	$newpass   = (!empty($_POST['newpass']))?FilterInput($_POST['newpass']):null; 

	if(empty($oldid) OR empty($oldpass) OR empty($newid) OR empty($newpass)) {
		echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Field is Empty"
		));
		die();
	}
	$chk_exists = CheckExists("matrix","matrix_username = '$oldid' AND matrix_password = '$oldpass' AND matrix_status=1");
	if (empty($chk_exists)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant find"
		));
		die();
	}
	$sql = "UPDATE matrix SET
	        matrix_username    = :matrix_username,
	        matrix_password    = :matrix_password
	        WHERE matrix_id=:matrix_id";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':matrix_username',$newid);
	        $insert->bindParam(':matrix_password',$newpass);
	        $insert->bindParam(':matrix_id',$_SESSION['adminid']);
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
elseif($operation=="updatesocial") {
	$fb     = (!empty($_POST['fb']))?FilterInput($_POST['fb']):null; 
    $tw     = (!empty($_POST['tw']))?FilterInput($_POST['tw']):null; 
	$yt     = (!empty($_POST['yt']))?FilterInput($_POST['yt']):null; 
	$ins    = (!empty($_POST['ins']))?FilterInput($_POST['ins']):null; 
	$ogid   = (!empty($_POST['ogid']))?FilterInput($_POST['ogid']):null; 
	$cardid = (!empty($_POST['cardid']))?FilterInput($_POST['cardid']):null; 

	$sql = "UPDATE socials SET
	        facebook     = :facebook,
	        twitter      = :twitter,
	        youtube      = :youtube,
	        instagram    = :instagram,
	        fb_og_id     = :fb_og_id,
	        tw_card_id   = :tw_card_id
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':facebook',$fb);
	        $insert->bindParam(':twitter',$tw);
	        $insert->bindParam(':youtube',$yt);
	        $insert->bindParam(':instagram',$ins);
	        $insert->bindParam(':fb_og_id',$ogid);
	        $insert->bindParam(':tw_card_id',$cardid);
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
elseif($operation=="updatefbimage") {

	$rowinfo=CheckExists("socials","social_id = '1'");

	if ((!empty($rowinfo['fb_site_img'])) AND file_exists("../../../images/social/".$rowinfo['fb_site_img'])) {
		@unlink("../../../images/social/".$rowinfo['fb_site_img']);
	}

	$fbimage=null;
	if(!empty($_FILES['fbimage']['name'])){

		$imgnm     = stripslashes($_FILES['fbimage']['name']);
		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
		$filename  = "himalayan_wheels_".time().rand(10000,999999999).'.'.$ext;
		if (!move_uploaded_file($_FILES['fbimage']['tmp_name'], "../../../images/social/$filename")) {
	        echo $response = json_encode(array(
				"status" => true, 
				"msg"	 => "Cant Upload FB Image"
			));
			die();
	    } 
	}
	$sql = "UPDATE socials SET
	        fb_site_img    = :fb_site_img
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':fb_site_img',$filename);
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
elseif($operation=="updatetwimage") {

	$rowinfo=CheckExists("socials","social_id = '1'");

	if ((!empty($rowinfo['tw_site_img'])) AND file_exists("../../../images/social/".$rowinfo['tw_site_img'])) {
		@unlink("../../../images/social/".$rowinfo['tw_site_img']);
	}

	$twimg=null;
	if(!empty($_FILES['twimg']['name'])){

		$imgnm     = stripslashes($_FILES['twimg']['name']);
		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
		$filename  = "himalayan_wheels_".time().rand(10000,999999999).'.'.$ext;
		if (!move_uploaded_file($_FILES['twimg']['tmp_name'], "../../../images/social/$filename")) {
	        echo $response = json_encode(array(
				"status" => true, 
				"msg"	 => "Cant Upload Twitter Image"
			));
			die();
	    } 
	}
	$sql = "UPDATE socials SET
	        tw_site_img    = :tw_site_img
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':tw_site_img',$filename);
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
elseif($operation=="updatesitemap") {

	$sitemap=null;
	if(!empty($_FILES['sitemap']['name'])){
		$sitemap  = stripslashes($_FILES['sitemap']['name']);
		if (!move_uploaded_file($_FILES['sitemap']['tmp_name'], "../../../$sitemap")) {
	        echo $response = json_encode(array(
				"status" => true, 
				"msg"	 => "Cant Upload Sitemap"
			));
			die();
	    } 
	}
	$sql = "UPDATE socials SET
	        sitemap      = :sitemap
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':sitemap',$sitemap);
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
elseif($operation=="updategoogle") {

	$gfile=null;
	if(!empty($_FILES['gfile']['name'])){
		$gfile  = stripslashes($_FILES['gfile']['name']);
		if (!move_uploaded_file($_FILES['gfile']['tmp_name'], "../../../$gfile")) {
	        echo $response = json_encode(array(
				"status" => true, 
				"msg"	 => "Cant Upload Sitemap"
			));
			die();
	    } 
	}
	$sql = "UPDATE socials SET
	        google_file      = :google_file
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':google_file',$gfile);
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
elseif($operation=="updatebing") {

	$bingfile=null;
	if(!empty($_FILES['bingfile']['name'])){
		$bingfile  = stripslashes($_FILES['bingfile']['name']);
		if (!move_uploaded_file($_FILES['bingfile']['tmp_name'], "../../../$bingfile")) {
	        echo $response = json_encode(array(
				"status" => true, 
				"msg"	 => "Cant Upload Sitemap"
			));
			die();
	    } 
	}
	$sql = "UPDATE socials SET
	        bing_file      = :bing_file
	        WHERE social_id='1'";
	        $insert = $PDO->prepare($sql);
	        $insert->bindParam(':bing_file',$bingfile);
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
else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}