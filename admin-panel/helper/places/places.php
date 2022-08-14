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
	//sleep(20);
	$customfilter = null;
	//echo json_encode($_POST);
	//$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	//$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
	//die();
	//if (!empty($sdate)) {
	//  $sdt  = date("Y-m-d",strtotime($sdate));
	//  $customfilter.=" AND DATE(enq_cfe_create_at) >= '$sdt' ";
	//}
	//if (!empty($edate)) {
	//  $edt  = date("Y-m-d",strtotime($edate));
	//  $customfilter.=" AND DATE(enq_cfe_create_at) <= '$edt' ";
	//}

	$draw    = isset($_POST['draw']) ? intval($_POST['draw']) : 10;
	$start   = intval($_POST['start']);
	$length  = intval($_POST['length']);

	if($length == -1) $length = 9999999999;
	$order   = (!empty($_REQUEST['order']))?$_REQUEST['order']:null;
	$search  = trim($_POST['search']['value']);
	$sql     = $sqlRec = $sqlTot = $where = '';

	function sortOrder($column){
	    switch($column){
	        case 0:$column="place_id";break;
	        case 1:$column="place_name";break;
	        case 2:$column="place_slug";break;
	    }
	    return $column; 
	}

	if(!empty($search)){   
	  $where .=" ( place_name LIKE '".$search."%' OR place_slug LIKE '".$search."%'  ) ";    
	}
 
	$sql="SELECT * FROM places_list INNER JOIN places_category ON places_list.place_category_id_ref  =  places_category.places_category_id WHERE place_status<>2 ".$customfilter;
	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;

	//echo $sqlRec;

	$sqlTot = $PDO->prepare($sqlTot);
	$sqlRec = $PDO->prepare($sqlRec);

	$sqlTot->execute();
	$sqlRec->execute();


if($sqlRec->rowCount()>0){
$i=$start+1;
while($row=$sqlRec->fetch()) { 

	extract($row);	
	$actionlink =null;
	$actionlink.='<td>';

    if ($place_status==0) { 
    $actionlink.='<a href="javascript:void(0);" title="Make Active" class="text-success statusup" data-id="'.$place_id.'" data-operation="active"><i class="fa fa-check"></i> || </a>';
    }else if($place_status==1) {
    $actionlink.=' <a href="javascript:void(0);" title="Make Dective" class="text-danger statusup" data-id="'.$place_id.'" data-operation="deactive"><i class="fa fa-lock"></i> || </a>';
	}   
	$actionlink.=' <a href="places-view?id='.$place_id.'" data-id="'.$place_id.'"  class="editbtn" title="View More"><i class="fa fa-eye"></i></a> || <a href="javascript:void(0);" class="statusup" title="Delete" data-id="'.htmlspecialchars($place_id).'" data-operation="delete"><i class="fa fa-trash"></i></a>
	</td>';
      $subdata   = array();
      $subdata[] = $i++;
      $subdata[] = date('d-M-y D H:i', strtotime($place_update_at))."<br/>".date('d-M-y D H:i', strtotime($place_create_at));
      $subdata[] = $places_category_name;
      $subdata[] = $place_name;
      $subdata[] = $place_slug;
      $subdata[] = StatusReport($place_status);
      $subdata[] = $actionlink;
      $subdata[] = null;
      $data[]    = $subdata;
  }
  $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval($sqlTot->rowCount()),  
      "recordsFiltered" => intval($sqlTot->rowCount()),
      "data"            => $data
    );
    echo json_encode($json_data);
  }else{
    $json_data = array(
      "draw"            => intval($draw),  
      "recordsTotal"    => intval(0),  
      "recordsFiltered" => intval(0),
      "data"            => ""
    );
    echo json_encode($json_data);
  }
}

elseif ($operation=="addnew") { 
	$catid          = (!empty($_POST['catid']))?FilterInput($_POST['catid']):null; 
	$name           = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
	$nameurl        = (!empty($_POST['nameurl']))?FilterInput($_POST['nameurl']):null;
	if(empty($name) OR empty($nameurl)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Name"
		));
		die();
	}
	$chk_slug = CheckExists("places_list","(place_name = '$nameurl' OR place_slug = '$name') AND place_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}
	$sql = "INSERT INTO places_list SET
			place_category_id_ref  = :place_category_id_ref,
	        place_name             = :place_name,
	        place_slug             = :place_slug,
	        place_update_at     = :place_update_at,
	        place_create_at	    = :place_create_at	";
	        $insert = $PDO->prepare($sql);;
	        $insert->bindParam(':place_category_id_ref',$catid);
	        $insert->bindParam(':place_name',$name);
	        $insert->bindParam(':place_slug',$nameurl);
	        $insert->bindParam(':place_update_at',$nowTime);
	        $insert->bindParam(':place_create_at',$nowTime);
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
elseif ($operation=="active" OR $operation=="deactive" OR $operation=="delete") {

	$id   = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
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
	$chk_id = CheckExists("places_list","place_id = '$id' AND place_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad  = ($operation=='delete')?", place_delete_at='$nowTime'":null;
	$sql = "UPDATE places_list SET place_status= '$up' , place_update_at='$nowTime' ".$ad. " WHERE place_id= {$id}";
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
elseif($operation=="updatemore") {
	$uptid       = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 
	$catid       = (!empty($_POST['catid']))?$_POST['catid']:null; 
	$name        = (!empty($_POST['name']))?$_POST['name']:null; 
	$nameurl     = (!empty($_POST['nameurl']))?$_POST['nameurl']:null; 
	$intro       = (!empty($_POST['intro']))?trim($_POST['intro']):null; 
    $details     = (!empty($_POST['details']))?trim($_POST['details']):null; 
	$metatitle   = (!empty($_POST['metatitle']))?trim($_POST['metatitle']):null; 
	$metadesc    = (!empty($_POST['metadesc']))?trim($_POST['metadesc']):null; 
	$keywords    = (!empty($_POST['keywords']))?FilterInput($_POST['keywords']):null;


	if(empty($uptid) OR !(filter_var($uptid,FILTER_VALIDATE_INT))) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Data Not Found"
		));
		die();
	}
	$catdet = CheckExists("places_category","places_category_id = '$catid' AND places_category_status<>2");
	if (empty($catdet)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Category"
		));
		die();
	}
	$plsdet = CheckExists("places_list","place_id  = '$uptid' AND place_status<>2");
	if (empty($plsdet)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find place"
		));
		die();
	}
	if(empty($name) OR empty($nameurl)) {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 => "Enter Name AND URL"
		));
		die();
	}
	$chk_slug = CheckExists("places_list","(place_name = '$name' OR place_slug = '$nameurl') AND place_id<>'$uptid' AND place_status<>2");
	if (!empty($chk_slug)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Name Already Exists"
		));
		die();
	}


	$img_thumb=$plsdet->place_thumb;
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
		if ((!empty($plsdet->place_thumb)) AND file_exists("../../../upload/".$plsdet->place_thumb)) {
			@unlink("../../../upload/".$plsdet->place_thumb);
		}
	}

	$img_inner=$plsdet->place_cover;
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
		if ((!empty($plsdet->place_cover)) AND file_exists("../../../upload/".$plsdet->place_cover)) {
			@unlink("../../../upload/".$plsdet->place_cover);
		}
	} 

	$fbimg=NULL;
	if(!empty($_FILES['fbimg']['name'])) {
		$imgnm     = stripslashes($_FILES['fbimg']['name']);
		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
		$filename  = FileName($nameurl).'_'.time().rand(10000,999999999).'.'.$ext;
		if (move_uploaded_file($_FILES['fbimg']['tmp_name'], "../../../upload/social/$filename")) {
	        $fbimg = $filename;
	    }
	}
	$twimg=NULL;
	if(!empty($_FILES['twimg']['name'])){
		$imgnm     = stripslashes($_FILES['twimg']['name']);
		$ext       = strtolower(pathinfo($imgnm, PATHINFO_EXTENSION));
		$filename  = FileName($nameurl).'_'.time().rand(10000,999999999).'.'.$ext;
		if (move_uploaded_file($_FILES['twimg']['tmp_name'], "../../../upload/social/$filename")) {
	        $twimg = $filename;
	    }
	}



	$sql = "UPDATE places_list SET
		        place_category_id_ref  = :place_category_id_ref,
		        place_name          = :place_name,
		        place_slug          = :place_slug,
		        place_thumb     	= :place_thumb,
		        place_cover         = :place_cover,
		        place_brief	        = :place_brief,
		        place_details       = :place_details,
		        place_meta_title    = :place_meta_title,
		        place_meta_desc 	= :place_meta_desc,
		        place_meta_keywords = :place_meta_keywords,
		        place_fb_cover 	= :place_fb_cover,
		        place_tw_cover 	= :place_tw_cover
	            WHERE place_id=:place_id"; 
	            $insert = $PDO->prepare($sql);
		        $insert->bindParam(':place_category_id_ref',$catid);
		        $insert->bindParam(':place_name',$name);
		        $insert->bindParam(':place_slug',$nameurl);
		        $insert->bindParam(':place_thumb',$img_thumb);
		        $insert->bindParam(':place_cover',$img_inner);
		        $insert->bindParam(':place_brief',$intro);
		        $insert->bindParam(':place_details',$details);
		        $insert->bindParam(':place_meta_title',$metatitle);
		        $insert->bindParam(':place_meta_desc',$metadesc);
		        $insert->bindParam(':place_meta_keywords',$keywords);
		        $insert->bindParam(':place_fb_cover',$fbimg);
		        $insert->bindParam(':place_tw_cover',$twimg);
	            $insert->bindParam(':place_id',$uptid);
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
else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}
