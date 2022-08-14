
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
	//sleep(20);
	$customfilter = null;
	//echo json_encode($_POST);
	$sdate    = (!empty($_POST['sdate']))?CleanInput(FilterInput($_POST['sdate'])):null;
	$edate    = (!empty($_POST['edate']))?CleanInput(FilterInput($_POST['edate'])):null;
	//die();
	if (!empty($sdate)) {
	  $sdt  = date("Y-m-d",strtotime($sdate));
	  $customfilter.=" AND DATE(user_create_at) >= '$sdt' ";
	}
	if (!empty($edate)) {
	  $edt  = date("Y-m-d",strtotime($edate));
	  $customfilter.=" AND DATE(user_create_at) <= '$edt' ";
	}

	$draw    = isset($_POST['draw']) ? intval($_POST['draw']) : 10;
	$start   = intval($_POST['start']);
	$length  = intval($_POST['length']);

	if($length == -1) $length = 9999999999;
	$order   = (!empty($_REQUEST['order']))?$_REQUEST['order']:null;
	$search  = trim($_POST['search']['value']);
	$sql     = $sqlRec = $sqlTot = $where = '';

	function sortOrder($column){
	    switch($column){
	        case 0:$column="user_id";break;
	        case 1:$column="user_code";break;
	        case 2:$column="cat_name";break;
	    }
	    return $column; 
	}


	$where .=" ( user_name LIKE '".$search."%' OR cat_name LIKE '".$search."%' OR role_name LIKE '".$search."%' ) ";    
	if(!empty($search)){   
	}
 	$sql ="SELECT * FROM users_list 
 				INNER JOIN emp_cat on users_list.user_cat_id_ref=emp_cat.cat_id  AND cat_status<>2
 				LEFT JOIN users_role on users_list.user_role_id_ref=users_role.role_id AND role_status<>2
 				WHERE user_status<>2  ".$customfilter;

	$sqlTot .= $sql;
	$sqlRec .= $sql;

	if(isset($where) && $where != '') {
	  $sqlTot .= " AND ( ".$where." ) ";
	  $sqlRec .= " AND ( ".$where." ) ";
	}
	$sqlRec .= " ORDER BY ".sortOrder($order[0]["column"])." ".$order[0]["dir"];
	$sqlRec .= " LIMIT ".$start.",".$length;

	// echo $sqlRec;

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

	$actionlink.='<div class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
		<div class="dropdown-menu">
		<a href="employee-view?id='.$user_id.'" title="View" class="dropdown-item text-primary"><i class="fa fa-eye"></i> View</a>';
 	$actionlink.='<a href="" data-toggle="modal" data-target="#upMod" data-id="'.htmlspecialchars($user_id).'" data-catid="'.htmlspecialchars($user_cat_id_ref).'" data-code="'.htmlspecialchars($user_code).'" data-role="'.htmlspecialchars($user_role_id_ref).'"  data-password="'.htmlspecialchars($user_password).'" data-name="'.htmlspecialchars($user_name).'" data-phone="'.htmlspecialchars($user_phone).'" data-mail="'.htmlspecialchars($user_email).'" data-address="'.htmlspecialchars($user_address).'" data-birth="'.htmlspecialchars($user_dob).'" data-join="'.htmlspecialchars($user_join).'" data-salary="'.htmlspecialchars($user_salary).'"data-leave="'.htmlspecialchars($user_leaving).'" class="dropdown-item editbtn" title="Quick Update"><i class="fa fa-edit"></i> Quick Update</a>';

	// $actionlink.='<a href="product-update?id='.$emp_id.'" title="Update Info" class="dropdown-item text-dark"><i class="fa fa-cog"></i> Update</a>';
 	
 	if ($user_status==0) {
 		$actionlink.='<a href="javascript:void(0);" title="Make Active" class="dropdown-item text-success statusup" data-id="'.$user_id.'" data-operation="active"><i class="fa fa-check"></i> Active</a>';
 	}
 	else if($user_status==1) {
 		$actionlink.='<a href="javascript:void(0);" title="Make Dective" class="dropdown-item text-danger statusup" data-id="'.$user_id.'" data-operation="deactive"><i class="fa fa-lock"></i> Deactive</a>';
 	}

 	$actionlink.='<div class="dropdown-divider"></div>';
 	$actionlink.='<a href="javascript:void(0);" class="dropdown-item statusup" title="Delete" data-id="'.htmlspecialchars($user_id).'" data-operation="delete"><i class="fa fa-trash"></i> Delete</a>';


	$actionlink.='</td></div></div>';


      $subdata   = array();
      $subdata[] = $i++;   
       $subdata[] = '<a href="employee-view?id='.$user_id.'">'.'<b style = "color: #0453a5">'.$user_code.'</b>'.'</a>';
      $subdata[] = $role_name;
      $subdata[] = $cat_name;
      $subdata[] = '<a href="employee-view?id='.$user_id.'">'.'<b style = "color: #e21010">'.$user_name.'</b>'.'</a>';
      $subdata[] = $user_password;
      $subdata[] = $user_phone;
      $subdata[] = $user_email;
      $subdata[] = StatusReport($user_status);
      $subdata[] = $actionlink;
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


 
	$category   = (!empty($_POST['category']))?FilterInput($_POST['category']):null;
	$role   = (!empty($_POST['role']))?FilterInput($_POST['role']):null;
	$empcode    = (!empty($_POST['empcode']))?FilterInput($_POST['empcode']):null;  
	$empname    = (!empty($_POST['empname']))?FilterInput($_POST['empname']):null; 
	$phno       = (!empty($_POST['phno']))?FilterInput($_POST['phno']):null; 
    $mail       = (!empty($_POST['mail']))?FilterInput($_POST['mail']):null;
    $address    = (!empty($_POST['address']))?FilterInput($_POST['address']):null;
    $dob        = (!empty($_POST['dob']))?FilterInput($_POST['dob']):null;
    $join       = (!empty($_POST['join']))?FilterInput($_POST['join']):null;
    $salary   = (!empty($_POST['salary']))?FilterInput($_POST['salary']):null; 
    $password   = (!empty($_POST['password']))?FilterInput($_POST['password']):null; 
    
 

	if(empty($empcode) OR empty($empname) OR empty($phno) OR empty($mail)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Data"
		));
		die();
	}
		if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$phno) OR !filter_var($phno, FILTER_VALIDATE_INT)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => "Invalid Phone No."
	));
	die();
} 
	if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => "Email is Wrong"
	));
	die();
} 

		$check_code = CheckExists("users_list"," user_code = '$empcode' AND user_status<>2");
	if (!empty($check_code)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "This Employee Code Already Exists"
		));
		die();
	}
	if(empty($category)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Category"
		));
		die();
	}
		if(empty($role)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Category"
		));
		die();
	}
	$chk_cat = CheckExists("emp_cat","cat_id = '$category' AND cat_status<>2");
	if (empty($chk_cat)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Category Not Found"
		));
		die();
	}
	$chk_role = CheckExists("users_role","role_id = '$role' AND role_status<>2");
	if (empty($chk_cat)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Role Not Found"
		));
		die();
	}
		if (empty($address)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Address Not Found"
			));
			die();
		}	
	
	
		if (empty($dob)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Date of Birth Not Found"
			));
			die();
		}
		if(empty($join)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Select Joining Date"
			));
			die();
		}
		if ($dob >= $join) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Enter Correct Date Range"
			));
			die();
		}

	$thumb=null;
	if(!empty($_FILES['image']['name'])){

		$valid_ext   = array('jpeg', 'jpg', 'png'); 
		$MimeFilter  = array('image/jpeg', 'image/jpg', 'image/png');
		$MaxSize     = 5 * 1024 * 1024;

		$FileName    = FilterInput($_FILES['image']['name']);
		$tmpName     = $_FILES['image']['tmp_name'];
		$FileTyp     = $_FILES['image']['type'];
		$FileSize    = $_FILES['image']['size']; 
		$MimeType    = mime_content_type($_FILES['image']['tmp_name']);

		$ext         = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Extention Not Allowed"
			));
			die();
		}
		if($FileSize>$MaxSize){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if($FileTyp!='image/jpeg' && $FileTyp!='image/jpg'){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR JPEG"
			));
			die();
		}
		if(!in_array($MimeType, $MimeFilter)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Not Supported"
			));
			die();
		}
		$dir          = "../../../images/employee/";
		$thumb        = FileName($empname).'_'.time().rand(10000,999999).'.'.$ext;
		$width        = 500;
	    $height       = 500;
		$img_file_fu  = ImageProperResize($height,$width,$dir,$thumb,$_FILES["image"]["tmp_name"]);

		
	}
	$canlogin = 0;
	if($role == 3 OR $role == 2 OR $role == 1){
		$canlogin = 1;
	}
	else{
		$canlogin = 0;
	}
	$sql = "INSERT INTO users_list SET
		        user_cat_id_ref  = :user_cat_id_ref,
		        user_role_id_ref  = :user_role_id_ref,
		        user_code        = :user_code,
		        user_name        = :user_name,
		        user_phone       = :user_phone,
		        user_email       = :user_email,
		        user_address     = :user_address,
		        user_dob         = :user_dob,
		        user_join        = :user_join,
		        user_img         = :user_img,
		        user_password    = :user_password,
		        user_canlogin    = :user_canlogin,
		        user_salary      = :user_salary";
		        $insert         = $PDO->prepare($sql);  
		        $insert->bindParam(':user_cat_id_ref',$category);
		        $insert->bindParam(':user_role_id_ref',$role);
		        $insert->bindParam(':user_code',$empcode);
		        $insert->bindParam(':user_name',$empname);
		        $insert->bindParam(':user_phone',$phno);
		        $insert->bindParam(':user_email',$mail);
		        $insert->bindParam(':user_address',$address);
		        $insert->bindParam(':user_dob',$dob);
		        $insert->bindParam(':user_join',$join);
		        $insert->bindParam(':user_img',$thumb);
		        $insert->bindParam(':user_password',$password);
		        $insert->bindParam(':user_canlogin',$canlogin);
		        $insert->bindParam(':user_salary',$salary);
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
 
	$uptid  = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 

	if(empty($uptid) OR !is_numeric($uptid)){
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Not Found"
			));
			die();
	}
	$prodet = CheckExists("users_list","user_id= '$uptid' AND user_status<>2");
	if (empty($prodet)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
    $upcategory   = (!empty($_POST['upcategory']))?FilterInput($_POST['upcategory']):null;
    $uprole   = (!empty($_POST['uprole']))?FilterInput($_POST['uprole']):null;
	$upempcode    = (!empty($_POST['upempcode']))?FilterInput($_POST['upempcode']):null;  
	$upempname    = (!empty($_POST['upempname']))?FilterInput($_POST['upempname']):null; 
	$upphno       = (!empty($_POST['upphno']))?FilterInput($_POST['upphno']):null; 
	$upmail       = (!empty($_POST['upmail']))?FilterInput($_POST['upmail']):null; 
	$upaddress    = (!empty($_POST['upaddress']))?FilterInput($_POST['upaddress']):null; 
	$updob        = (!empty($_POST['updob']))?FilterInput($_POST['updob']):null;
	$upjoin       = (!empty($_POST['upjoin']))?FilterInput($_POST['upjoin']):null;
	$upsalary     = (!empty($_POST['upsalary']))?FilterInput($_POST['upsalary']):null;
	$uppassword     = (!empty($_POST['uppassword']))?FilterInput($_POST['uppassword']):null;
	$upleave      = (!empty($_POST['upleave']))?FilterInput($_POST['upleave']):null;

	if(empty($upempcode) OR empty($upempname) OR empty($upphno) OR empty($upmail)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Enter Details"
		));
		die();
	}
		if(!filter_var($upmail,FILTER_VALIDATE_EMAIL)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => "Email is Wrong"
	));
	die();
} 

	if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$upphno) OR !filter_var($upphno, FILTER_VALIDATE_INT)){
  	echo $response = json_encode(array(
		"status" => false,
		"msg"	 => "Invalid Phone No."
	));
	die();
} 
	
	if(empty($upcategory)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Category"
		));
		die();
	}
	if(empty($uprole)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Select Role"
		));
		die();
	}
	
		if (empty($upaddress)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Address Not Found"
			));
			die();
		}
		// 		if (empty($uppassword)) {
		// 	echo $response = json_encode(array(
		// 			"status" => false,
		// 			"msg"	 => "Set Password"
		// 	));
		// 	die();
		// }
		if(empty($updob)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Date of Birth Not Found"
			));
			die();
		}
		
		if (empty($upjoin)) {
			echo $response = json_encode(array(
					"status" => false,
					"msg"	 => "Select Joining Date"
			));
			die();
		}
		// 		if ($upjoin >= $upleave) {
		// 	echo $response = json_encode(array(
		// 			"status" => false,
		// 			"msg"	 => "Enter Correct Date Range"
		// 	));
		// 	die();
		// }
		$thumb=$prodet->user_img;
	if(!empty($_FILES['upimage']['name'])){

		$valid_ext   = array('jpeg', 'jpg', 'png'); 
		$MimeFilter  = array('image/jpeg', 'image/jpg', 'image/png');
		$MaxSize     = 5 * 1024 * 1024;

		$FileName    = FilterInput($_FILES['upimage']['name']);
		$tmpName     = $_FILES['upimage']['tmp_name'];
		$FileTyp     = $_FILES['upimage']['type'];
		$FileSize    = $_FILES['upimage']['size']; 
		$MimeType    = mime_content_type($_FILES['upimage']['tmp_name']);

		$ext         = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
		if(!in_array($ext, $valid_ext)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Extention Not Allowed"
			));
			die();
		}
		if($FileSize>$MaxSize){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Max file Size: 5MB"
			));
			die();
		}
		if($FileTyp!='image/jpeg' && $FileTyp!='image/jpg' && $FileTyp!='image/png'){
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"Image Type Shoud be JPG OR JPEG"
			));
			die();
		}
		if(!in_array($MimeType, $MimeFilter)) {
			echo $response = json_encode(array(
				"status" =>false, 
				"msg"	 =>"File Not Supported"
			));
			die();
		}
		$dir          = "../../../images/employee/";
		$thumb        = FileName($upempname).'_'.time().rand(10000,999999).'.'.$ext;
		$width        = 600;
	    $height       = 600;
		$img_file_fu  = ImageProperResize($height,$width,$dir,$thumb,$_FILES["upimage"]["tmp_name"]);

				if (!empty($prodet->user_img) AND file_exists("../../../images/employee/".$prodet->user_img)){
			@unlink("../../../images/employee/".$prodet->user_img);
		}
		
	}

		$canlogin = 0;
	if($uprole == 3 OR $uprole == 2 OR $uprole == 1){
		$canlogin = 1;
	}
	else{
		$canlogin = 0;
	}

	$sql = "UPDATE users_list SET 
	            user_cat_id_ref     = :user_cat_id_ref,
	            user_role_id_ref     = :user_role_id_ref,
		        user_code           = :user_code,
		        user_name           = :user_name,
		        user_phone          = :user_phone,
		        user_email          = :user_email,
		        user_address        = :user_address,
		        user_dob            = :user_dob,
		        user_join           = :user_join,
		        user_salary        = :user_salary,
		        user_password        = :user_password,
		        user_img            = :user_img,
		        user_canlogin    = :user_canlogin,
		        user_leaving        = :user_leaving
		        WHERE user_id=:user_id";
		        $insert = $PDO->prepare($sql);  
		        $insert->bindParam(':user_cat_id_ref',$upcategory);
		        $insert->bindParam(':user_role_id_ref',$uprole);
		        $insert->bindParam(':user_code',$upempcode);
		        $insert->bindParam(':user_name',$upempname);
		        $insert->bindParam(':user_phone',$upphno);
		        $insert->bindParam(':user_email',$upmail);
		        $insert->bindParam(':user_address',$upaddress);
		        $insert->bindParam(':user_dob',$updob);
		        $insert->bindParam(':user_join',$upjoin);
		        $insert->bindParam(':user_salary',$upsalary);
		        $insert->bindParam(':user_salary',$upsalary);
		        $insert->bindParam(':user_password',$uppassword);
		        $insert->bindParam(':user_img',$thumb);
		        $insert->bindParam(':user_canlogin',$canlogin);
		        $insert->bindParam(':user_leaving',$upleave);
		        $insert->bindParam(':user_id',$uptid);
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


// elseif ($operation == 'copydata') {
// 	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
// 	if(empty($id) AND !is_numeric($id)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Something Wrong"
// 		));
// 		die();
// 	}

// 	$stmt = "SELECT * FROM users_list WHERE emp_id = '$id' AND emp_status<>1";
// 	$stmt = $PDO->prepare($stmt);
// 	$stmt->execute();
// 	$data=$stmt->fetch(PDO::FETCH_OBJ);
// 	if (empty($data)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Cant Find this Entry"
// 		));
// 		die();
// 	}

// 	$name = $data->emp_name."_".rand(100,1000);

// 	$sql = "INSERT INTO users_list SET
// 		        emp_cat_id_ref     = :emp_cat_id_ref,
// 		        emp_code           = :emp_code,
// 		        emp_name           = :emp_name,
// 		        emp_phone          = :emp_phone,
// 		        emp_email           = :emp_email";
// 		        $insert = $PDO->prepare($sql);  
// 		        $insert->bindParam(':emp_cat_id_ref',$data->emp_cat_id_ref);
// 		        $insert->bindParam(':emp_code',$code);
// 		        $insert->bindParam(':emp_name',$emp_name);
// 		        $insert->bindParam(':emp_phone',$data->emp_phone);
// 		        $insert->bindParam(':emp_email',$data->emp_mail);
// 		        $insert->execute();
// 		        $lastid = $PDO->lastInsertId();
// 		        if($insert->rowCount() > 0){
// 		        	$galfind = "SELECT * FROM product_gallery WHERE pg_pro_id_ref  = '$id' AND pg_img_status<>2";
// 					$galfind = $PDO->prepare($galfind);
// 					$galfind->execute();
// 					$galdata=$galfind->fetchALL(PDO::FETCH_OBJ);
// 					if (!empty($galdata)) {
// 						foreach ($galdata as $eachgal) {

// 							$ext         = strtolower(pathinfo($eachgal->pg_img_fu, PATHINFO_EXTENSION));

// 							$filename_fu = FileName($name).'_'.time().rand(10000,999999999).'_fu'.'.'.$ext;
// 							$filename_lg = FileName($name).'_'.time().rand(10000,999999999).'_lg'.'.'.$ext;
// 							$filename_md = FileName($name).'_'.time().rand(10000,999999999).'_md'.'.'.$ext;
// 							$filename_sm = FileName($name).'_'.time().rand(10000,999999999).'_sm'.'.'.$ext;
// 							$filename_xs = FileName($name).'_'.time().rand(10000,999999999).'_xs'.'.'.$ext;


// 							$dir   = "../../../product/";

// 							copy($dir.$eachgal->pg_img_fu, $dir.$filename_fu);
// 							copy($dir.$eachgal->pg_img_lg, $dir.$filename_lg);
// 							copy($dir.$eachgal->pg_img_md, $dir.$filename_md);
// 							copy($dir.$eachgal->pg_img_sm, $dir.$filename_sm);
// 							copy($dir.$eachgal->pg_img_xs, $dir.$filename_xs);

// 							$sql = "INSERT INTO product_gallery SET
// 						        	pg_pro_id_ref = :pg_pro_id_ref,
// 						        	pg_img_fu     = :pg_img_fu,
// 						        	pg_img_lg     = :pg_img_lg,
// 						        	pg_img_md     = :pg_img_md,
// 						        	pg_img_sm     = :pg_img_sm,
// 						        	pg_img_xs     = :pg_img_xs,
// 						        	pg_img_name   = :pg_img_name";
// 						        	$insert   = $PDO->prepare($sql);;
// 						        	$insert->bindParam(':pg_pro_id_ref',$lastid);
// 						        	$insert->bindParam(':pg_img_fu',$filename_fu);
// 						        	$insert->bindParam(':pg_img_lg',$filename_lg);
// 						        	$insert->bindParam(':pg_img_md',$filename_md);
// 						        	$insert->bindParam(':pg_img_sm',$filename_sm);
// 						        	$insert->bindParam(':pg_img_xs',$filename_xs);
// 						        	$insert->bindParam(':pg_img_name',$eachgal->pg_img_name);
// 						        	$insert->execute();
// 					    }
// 					}
// 		        	echo $response = json_encode(array(
// 						"status" => true, 
// 						"msg"	 => "Successfully Added"
// 					));
// 		        }else {
// 		        	echo $response = json_encode(array(
// 						"status" =>false,
// 						"msg"	 =>"Something Wrong"
// 					));
// 				}







// }

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
	$chk_id = CheckExists("users_list","user_id = '$id' AND user_status<>2");
	if (empty($chk_id)) {
		echo $response = json_encode(array(
				"status" => false,
				"msg"	 => "Cant Find this Entry"
		));
		die();
	}
	$ad = ($operation=='delete')?", user_delete_at=NOW()":null;
	$sql = "UPDATE users_list SET user_status= {$up} ".$ad. " WHERE user_id= {$id}";
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
// elseif ($operation=="stock" OR $operation=="outstock") {


// 	$id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
// 	if(empty($id) AND !is_numeric($id)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Something Wrong"
// 		));
// 		die();
// 	}
// 	switch ($operation) {
// 		case 'stock':
// 			$up = 1;
// 			$msg="In Stock";
// 			break;
// 		case 'outstock':
// 			$up = 0;
// 			$msg="Out of Stock";
// 			break;
// 		default:
// 			$up=1;
// 			$msg="Something Wrong";
// 			break;
// 	}
// 	$chk_id = CheckExists("users_list","emp_id= '$id' AND emp_status<>2");
// 	if (empty($chk_id)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Cant Find this Entry"
// 		));
// 		die();
// 	}
// 	$sql = "UPDATE users_list SET pro_stock= {$up} WHERE pro_id= {$id}";
// 			$insert = $PDO->prepare($sql);
// 			$insert->execute();
// 			if($insert->rowCount() > 0){
// 					echo $response = json_encode(array(
// 					"status" => true, 
// 					"msg"	 => $msg
// 				));
// 			}else {
// 					echo $response = json_encode(array(
// 					"status" =>false,
// 					"msg"	 =>"No Change Done"
// 				));
// 			}
// }

// elseif ($operation=="updatemore") {
// 	$uptid  = (!empty($_POST['uptid']))?FilterInput($_POST['uptid']):null; 

// 	if(empty($uptid) OR !is_numeric($uptid)){
// 			echo $response = json_encode(array(
// 					"status" => false,
// 					"msg"	 => "Not Found"
// 			));
// 			die();
// 	}
// 	$prodet = CheckExists("users_list","emp_id= '$uptid' AND emp_status<>2");
// 	if (empty($prodet)) {
// 		echo $response = json_encode(array(
// 				"status" => false,
// 				"msg"	 => "Cant Find this Entry"
// 		));
// 		die();
// 	}

// 	$high   = (!empty($_POST['high']))?trim($_POST['high']):null; 
// 	$desc   = (!empty($_POST['desc']))?trim($_POST['desc']):null; 
// 	$deal   = (!empty($_POST['deal']))?trim($_POST['deal']):null; 
// 	$section   = (!empty($_POST['section']))?trim($_POST['section']):null; 

// 	$sql = "UPDATE users_list SET 
// 	        pro_highlight     = :pro_highlight,
// 	        pro_description   = :pro_description,
// 	        pro_deal_id_ref   = :pro_deal_id_ref,
// 	        pro_section_id_ref = :pro_section_id_ref
// 	        WHERE pro_id=:pro_id";
// 	        $insert = $PDO->prepare($sql);  
// 	        $insert->bindParam(':pro_highlight',$high);
// 	        $insert->bindParam(':pro_description',$desc);
// 	        $insert->bindParam(':pro_deal_id_ref',$deal);
// 	        $insert->bindParam(':pro_section_id_ref',$section);
// 	        $insert->bindParam(':pro_id',$uptid);
// 	        $insert->execute();
// 	        if($insert->rowCount() > 0){
// 	        	echo $response = json_encode(array(
// 					"status" => true, 
// 					"msg"	 => "Successfully Updated"
// 				));
// 	        }else {
// 	        	echo $response = json_encode(array(
// 					"status" =>false,
// 					"msg"	 =>"No Changes Done"
// 				));
// 			}

 

else {
	echo $response = json_encode(array(
			"status" => false,
			"msg"	 =>" Something Wrong"
	));
	die();
}