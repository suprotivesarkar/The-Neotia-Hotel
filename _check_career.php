<?php require_once("_top.php");
$name     = (!empty($_POST['name']))?FilterInput(strval($_POST['name'])):null; 
$phone    = (!empty($_POST['phone']))?FilterInput(strval($_POST['phone'])):null; 
$emailid  = (!empty($_POST['emailid']))?FilterInput(strval($_POST['emailid'])):null;


if (empty($name) OR empty($phone) OR empty($emailid)) {
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "<div class='alert alert-danger'><strong>Enter the Details!</strong></div>" 
    ));
    die();
}
if (!ctype_digit($phone) OR strlen($phone)!=10) {
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "<div class='alert alert-danger'><strong>Enter 10 Digit Mobile Number!</strong></div>" 
    ));
    die();
}
if(!preg_match('/^[6-9][0-9]{9}$/',$phone)) {
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "<div class='alert alert-danger'><strong>Phone Number is Not Valid!</strong></div>" 
    ));
    die();
}
if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "<div class='alert alert-danger'><strong>Email ID is Not in Valid form!</strong></div>" 
    ));
    die();
} 

$time= Date('Y-m-d H:i:s');

$FileName = null;
if(!empty($_FILES['resumeimg']['name'])){

$valid_ext   = array('jpeg', 'jpg', 'png', 'pdf');
$mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
$maxsize     = 10 * 1024 * 1024;

$FileName    = FilterInput($_FILES['resumeimg']['name']);
$tmpName     = $_FILES['resumeimg']['tmp_name'];
$FileTyp     = $_FILES['resumeimg']['type'];
$FileSize    = $_FILES['resumeimg']['size'];
$MimeType    = mime_content_type($_FILES['resumeimg']['tmp_name']);

$ext      = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
$FileName = FileName($name).'_'.time().rand(10000,999999999).'.'.$ext;

if(!in_array($ext, $valid_ext)) {
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">File Extension is Not Allowed!</div>'
    ));
}
if($FileSize>$maxsize){
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Max File Size Must Be 10MB!</div>'
    ));
}
if(!in_array($FileTyp, $mime_filter)) {
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">File Format Not Supported!</div>'
    ));
}
if(!in_array($MimeType, $mime_filter)) {
 echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">File Format Not Supported!</div>'
    ));
}

$path = "images/career/".$FileName;
if (!move_uploaded_file($_FILES["resumeimg"]["tmp_name"],$path)) {
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Cant Upload File!</div>'
    ));
}
chmod($path,0644);
}

$time= Date('Y-m-d H:i:s');

$sql = "INSERT INTO career SET
            care_name           = :care_name,
            care_phone          = :care_phone,
            care_email          = :care_email,
            care_file           = :care_file,
            care_date           = :care_date,
            care_ip             = :care_ip";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':care_name',$name);
            $insert->bindParam(':care_phone',$phone);
            $insert->bindParam(':care_email',$emailid);
            $insert->bindParam(':care_file',$FileName);
            $insert->bindParam(':care_date',$time);
            $insert->bindParam(':care_ip',$ip);
            $insert->execute();
            if($insert->rowCount() > 0){
                echo $response = json_encode(array(
                    "status" => true,
                    "msg"    => "<div class='alert alert-success'><strong>Thank You! Will Contact You Soon!</strong></div>" 
            ));
            }
            else {
                echo $response = json_encode(array(
                    "status" => false,
                    "msg"    => "<div class='alert alert-danger'><strong>Something is wrong</strong></div>" 
            ));
            }