<?php require_once("_top.php");
$name     = (!empty($_POST['name']))?FilterInput(strval($_POST['name'])):null; 
$phone    = (!empty($_POST['phone']))?FilterInput(strval($_POST['phone'])):null; 
$emailid  = (!empty($_POST['emailid']))?FilterInput(strval($_POST['emailid'])):null;
$subject = (!empty($_POST['subject']))?FilterInput(strval($_POST['subject'])):null; 
$message  = (!empty($_POST['message']))?FilterInput(strval($_POST['message'])):null; 

if (empty($name) OR empty($phone) OR empty($emailid) OR empty($subject) OR empty($message)) {
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

$sql = "INSERT INTO quick_contact SET
            qname           = :qname,
            qmobile         = :qmobile,
            qemail          = :qemail,
            qsubject        = :qsubject,
            qmsg            = :qmsg,
            qip             = :qip";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':qname',$name);
            $insert->bindParam(':qmobile',$phone);
            $insert->bindParam(':qemail',$emailid);
            $insert->bindParam(':qmsg',$message);
            $insert->bindParam(':qsubject',$subject);
            $insert->bindParam(':qip',$ip);
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