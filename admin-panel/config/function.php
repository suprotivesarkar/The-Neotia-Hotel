<?php

function ProPrefix(){
    $pkgprefix="HT";
    return $pkgprefix;
}

function CheckExists($table_name,$Where_contion){
	global $PDO;$rowdet=null;
	$where = (!empty($Where_contion)?"WHERE ".$Where_contion :null); 
	$stmt  = "SELECT * FROM $table_name ".$where;
	$res   = $PDO->prepare($stmt);
	$res->execute();
	if($res->rowCount()>0){
		$rowdet=$res->fetch(PDO::FETCH_OBJ);
	}
	return $rowdet;
}

function Tax($total){
 $tax = 0;
 if ($total > 1000 && $total <= 7500) {
 	$tax = ($total*0.12);
 }
 elseif ($total > 7500 ) {
 	$tax = ($total*0.18);
 }
 return $tax;
}

function ImageProperResize($height,$width,$dir,$name,$imgfile,$quality=0){

	$image_info                     = getimagesize($imgfile);
	list($width_orig, $height_orig) = getimagesize($imgfile);

	$ratio       = max($width/$width_orig, $height/$height_orig);
	$height_orig = ceil($height / $ratio);
	$x           = ($width_orig - $width / $ratio) / 2;
	$width_orig  = ceil($width / $ratio);

	$fullpath    = $dir.$name;

	$imgString   = file_get_contents($imgfile);
	$image       = imagecreatefromstring($imgString);
	$tmp         = imagecreatetruecolor($width, $height);
	switch ($image_info['mime'])
	{
	    case 'image/jpeg':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $r = @imagejpeg($tmp,$fullpath,60);
	        break;
	    case 'image/png':
	    	$srcimage = imagecreatefrompng($imgfile);
		    $img      = imagecreatetruecolor($width, $height);
		    $bga      = imagecolorallocatealpha($img, 0, 0, 0, 127);
		    imagecolortransparent($img, $bga);
		    imagefill($img,0,0,$bga);
		    imagetruecolortopalette($img, false, 255);
		    imagealphablending($img, FALSE);
		    imagesavealpha($img, TRUE);
		    imagecopyresampled($img, $srcimage, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		    $r = @imagepng($img, $fullpath,9.9);
		    imagedestroy($srcimage);
			imagedestroy($img);
	        break;
	    case 'image/gif':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $background = imagecolorallocate($tmp, 0, 0, 0); 
	        imagecolortransparent($tmp, $background);
	        $r = @imagegif($tmp,$fullpath);
	        break;
	    default:
			exit;
			break;
	}
	chmod($fullpath,0644);
	return $name;
	imagedestroy($image);
	imagedestroy($tmp);
	die();

}




function MaxResize($width,$dir,$name,$imgfile,$quality=0){
	$image_info                     = getimagesize($imgfile);
	list($width_orig, $height_orig) = getimagesize($imgfile);

	if ($width_orig>$width) {
		$ratio  = $width_orig/$height_orig;
		$height = ceil($width/$ratio);
	}
	else{
		$width  = $width_orig;
		$height = $height_orig;
	}
	$path        = $dir.$name;
	$imgString   = file_get_contents($imgfile);
	$image       = imagecreatefromstring($imgString);
	$tmp         = imagecreatetruecolor($width, $height);
	switch ($image_info['mime'])
	{
	    case 'image/jpeg':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $r = @imagejpeg($tmp,$path,60);
	        break;
	    case 'image/png':
	    	$srcimage = imagecreatefrompng($imgfile);
		    $img      = imagecreatetruecolor($width, $height);
		    $bga      = imagecolorallocatealpha($img, 0, 0, 0, 127);
		    imagecolortransparent($img, $bga);
		    imagefill($img,0,0,$bga);
		    imagetruecolortopalette($img, false, 255);
		    imagealphablending($img, FALSE);
		    imagesavealpha($img, TRUE);
		    imagecopyresampled($img, $srcimage, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		    $r = @imagepng($img, $path,9.9);
		    imagedestroy($srcimage);
			imagedestroy($img);
	        break;
	    case 'image/gif':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $background = imagecolorallocate($tmp, 0, 0, 0); 
	        imagecolortransparent($tmp, $background);
	        $r = @imagegif($tmp,$path);
	        break;
	    default:
			exit;
			break;
	}
	chmod($path,0644);
	return $name;
	imagedestroy($image);
	imagedestroy($tmp);
	die();
} 




function CleanInput($string) {
   //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\- ]/', '', $string); // Removes special chars.
}


function PercentageOff($mrp,$sp){
  
  $per = (($mrp - $sp) / ($mrp)) * 100;
  return floor($per);
}




function PkgPrefix(){
    $pkgprefix="AH";
    return $pkgprefix;
}

function CaptchaCode(){
  $capcode = substr(str_shuffle('0123456789'),0,rand(3,5));
  $_SESSION['capcode'] = $capcode;
  $img   = imagecreatetruecolor(55, 45);
  $color = imagecolorallocate($img, 255, 255, 255);
  $bg    = imagecolorallocate($img, 61, 142, 185);
  imagefill($img, 0, 0, $bg);
  imagestring($img, 10, 6, 15,$capcode, $color);
  ob_start ();
  imagepng($img);
  imagedestroy($img);
  $data = ob_get_contents ();
  ob_end_clean ();
  $image = "data:image/jpeg;base64,".base64_encode($data);
  return $image;
}

function VehicalList($id){
	$html = null;
	switch($id){
		case 1: $html = 'Hatchback - Wagnor/ Indica/ Alto';break;
		case 2: $html = 'Sedan - Swift/ Dzire/ Indigo';break;
		case 3: $html = 'Standard - Sumo/ Bolero/ Maxx';break;
		case 4: $html = 'Luxury - Innova/ Xylo';break;
		case 5: $html = 'Coaster Bus';break;
	}
	return $html;
}
function ServiceList($id){
	$html = null;
	switch($id){
		case 1: $html = 'One Way Transfer';break;
		case 2: $html = 'Return Only';break;
		case 3: $html = 'Tour Package';break;
	}
	return $html;
}

function SocialInfo(){
	global $PDO;
	$rowdet=null;
	$stmt  = "SELECT * FROM socials WHERE social_id=1";
	$res   = $PDO->prepare($stmt);
	$res->execute();
	if($res->rowCount()>0){
		$rowdet=$res->fetch(PDO::FETCH_OBJ);
	}
	return $rowdet;
}

function CheckInWishlist($memid,$proid,$type){
	global $PDO;
	if(empty($memid) OR empty($proid) OR empty($type)){return false;die();}

	$stmt = $PDO->prepare("SELECT * FROM wishlist WHERE wish_mem_id_ref=:memid AND wish_product_id_ref=:proid AND wish_type=:wish_type AND wish_status=1");
	$stmt->execute(['memid' => $memid, 'proid' => $proid, 'wish_type' =>$type]); 
	if ($stmt->rowCount()>=1){ 
		return true;
	}else{
		return false;
	}
}

function UserDetails($memid){
	global $PDO;$memberdet=null;
	if (empty($memid)){return $memberdet;die();}
	$str = "SELECT * FROM members WHERE mem_id={$memid} AND mem_status=1";
	$res = $PDO->prepare($str);
	$res->execute();
	if($res->rowCount()>0){
		$memberdet=$res->fetch();
	}
	return $memberdet;
}

function LocationParentDetails($locid){
	global $PDO;$row=null;
	if (empty($locid) OR !is_numeric($locid)) {
		return null;
		die();
	}
	$str = "SELECT * FROM travel_points WHERE tp_id='$locid' AND tp_status<>2";
	$res = $PDO->prepare($str);
	$res->execute();
	if($res->rowCount()>0){
		$row=$res->fetch();
	}
	return $row;
} 

function StatusReport($id){
	switch ($id) {
		case '0':
			$msg="<i class='fa fa-times' style='color:red;'></i>";
			break;
		case '1':
			$msg="<i class='fa fa-check-circle' style='color:green;'></i>";
			break;
		default:
			$msg="-";
			break;
	}
	return $msg;
}

function FileName($original){
    $slug = str_replace(" ", "_", $original);
    $slug = preg_replace('/[^\w\d\-\_]/i', '', $slug);
    return strtolower($slug);
}


function FilterInput($input){
	$input = strip_tags(stripslashes(trim($input)));
	return $input;
}

/*function MaxResize($width,$dir,$name,$imgfile,$quality=0){
	$image_info                     = getimagesize($imgfile);
	list($width_orig, $height_orig) = getimagesize($imgfile);

	if ($width_orig>$width) {
		$ratio  = $width_orig/$height_orig;
		$height = ceil($width/$ratio);
	}
	else{
		$width  = $width_orig;
		$height = $height_orig;
	}
	$path        = $dir.$name;
	$imgString   = file_get_contents($imgfile);
	$image       = imagecreatefromstring($imgString);
	$tmp         = imagecreatetruecolor($width, $height);
	switch ($image_info['mime'])
	{
	    case 'image/jpeg':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $r = @imagejpeg($tmp,$path,60);
	        break;
	    case 'image/png':
	    	$srcimage = imagecreatefrompng($imgfile);
		    $img      = imagecreatetruecolor($width, $height);
		    $bga      = imagecolorallocatealpha($img, 0, 0, 0, 127);
		    imagecolortransparent($img, $bga);
		    imagefill($img,0,0,$bga);
		    imagetruecolortopalette($img, false, 255);
		    imagealphablending($img, FALSE);
		    imagesavealpha($img, TRUE);
		    imagecopyresampled($img, $srcimage, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		    $r = @imagepng($img, $path,9.9);
		    imagedestroy($srcimage);
			imagedestroy($img);
	        break;
	    case 'image/gif':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $background = imagecolorallocate($tmp, 0, 0, 0); 
	        imagecolortransparent($tmp, $background);
	        $r = @imagegif($tmp,$path);
	        break;
	    default:
			exit;
			break;
	}
	chmod($path,0644);
	return $name;
	imagedestroy($image);
	imagedestroy($tmp);
	die();
} */



function ResizeImage($height,$width,$dir,$name,$imgfile,$quality=0){
	$image_info                     = getimagesize($imgfile);
	list($width_orig, $height_orig) = getimagesize($imgfile);

	$ratio       = max($width/$width_orig, $height/$height_orig);
	$height_orig = ceil($height / $ratio);
	$x           = ($width_orig - $width / $ratio) / 2;
	$width_orig  = ceil($width / $ratio);

	$path        = $dir.$name;
	$imgString   = file_get_contents($imgfile);
	$image       = imagecreatefromstring($imgString);
	$tmp         = imagecreatetruecolor($width, $height);
	switch ($image_info['mime'])
	{
	    case 'image/jpeg':
	        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $width_orig, $height_orig);
	        $r = @imagejpeg($tmp,$path,100);
	        break;
	    case 'image/png':
	    	$srcimage = imagecreatefrompng($imgfile);
		    $img      = imagecreatetruecolor($width, $height);
		    $bga      = imagecolorallocatealpha($img, 0, 0, 0, 127);
		    imagecolortransparent($img, $bga);
		    imagefill($img,0,0,$bga);
		    imagetruecolortopalette($img, false, 255);
		    imagealphablending($img, FALSE);
		    imagesavealpha($img, TRUE);
		    imagecopyresampled($img, $srcimage, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		    $r = @imagepng($img, $path,9.9);
		    imagedestroy($srcimage);
			imagedestroy($img);
	        break;
	    case 'image/gif':
	        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        $background = imagecolorallocate($tmp, 0, 0, 0); 
	        imagecolortransparent($tmp, $background);
	        $r = @imagegif($tmp,$path);
	        break;
	    default:
			exit;
			break;
	}
	chmod($path,0644);
	return $name;
	imagedestroy($image);
	imagedestroy($tmp);
	die();
} 
 
function resize($width,$height,$dir,$name){
	  list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
	  $ratio = max($width/$w, $height/$h);
	  $h = ceil($height / $ratio);
	  $x = ($w - $width / $ratio) / 2;
	  $w = ceil($width / $ratio);
	  $path = $dir.$name;
	  $imgString = file_get_contents($_FILES['image']['tmp_name']);
	  $image     = imagecreatefromstring($imgString);
	  $tmp       = imagecreatetruecolor($width, $height);
	  imagecopyresampled($tmp, $image,
	    0, 0,
	    $x, 0,
	    $width, $height,
	    $w, $h);
	  switch ($_FILES['image']['type']) {
	    case 'image/jpeg':
		      imagejpeg($tmp, $path, 60);
		      break;
	    case 'image/png':
		      imagepng($tmp, $path, 0);
		      break;
	    case 'image/gif':
		      imagegif($tmp, $path);
		      break;
	    default:
		      exit;
		      break;
	  }
	  chmod($path,0644);
	  return $name;
	  imagedestroy($image);
	  imagedestroy($tmp);
} 
function resizeInnerImage($width,$height,$dir,$name){
	  list($w, $h) = getimagesize($_FILES['image2']['tmp_name']);
	  $ratio = max($width/$w, $height/$h);
	  $h = ceil($height / $ratio);
	  $x = ($w - $width / $ratio) / 2;
	  $w = ceil($width / $ratio);
	  $path = $dir.$name;
	  $imgString = file_get_contents($_FILES['image2']['tmp_name']);
	  $image     = imagecreatefromstring($imgString);
	  $tmp       = imagecreatetruecolor($width, $height);
	  imagecopyresampled($tmp, $image,
	    0, 0,
	    $x, 0,
	    $width, $height,
	    $w, $h);
	  switch ($_FILES['image2']['type']) {
	    case 'image/jpeg':
		      imagejpeg($tmp, $path, 55);
		      break;
	    case 'image/png':
		      imagepng($tmp, $path, 0);
		      break;
	    case 'image/gif':
		      imagegif($tmp, $path);
		      break;
	    default:
		      exit;
		      break;
	  }
	  chmod($path,0644);
	  return $name;
	  imagedestroy($image);
	  imagedestroy($tmp);
} 
function Saluation($id){
	switch($id){
		case 1: $html  = 'Mr';break;
		case 2: $html  = 'Ms';break;
		case 3: $html  = 'Dr';break;
		default: $html = null;break;
	}
	return $html;
}

function Razorpaykey(){
$key = array('key' => 'rzp_test_fUA8Vz6R9COqZa','key_secret' => 'P88OmkV1kTTyXQjbWAbqDYRh');
// $key = array('key' => 'rzp_live_43WCPS1GfSpHBG','key_secret' => 'xTrRcLjaCTVMnKqVqWyqovex');
return $key;
}




