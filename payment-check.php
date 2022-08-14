<?php
require_once("_top.php"); 
require_once "vendor/autoload.php";
use Razorpay\Api\Api;

if (!isset($_SESSION['bookin']) OR empty($_SESSION['bookin'])) {
    header("Location:./");   
    die(); 
}
$memdet=UserDetails($_SESSION['userid']);
if (!isset($_POST['razorpay_payment_id'])) {
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "Something Wrong" 
    ));
    die(); 
} 
$mem_name =$memdet['mem_name'];
$mem_email =$memdet['mem_email'];

//print_r($_POST);
    
$payid   = $_POST['razorpay_payment_id'];
$api     = new Api(Razorpaykey()['key'],Razorpaykey()['key_secret']);
$payment = $api->payment->fetch($payid);


if ($payment->captured OR $payment->status =='authorized') {

     if(!isset($_SESSION['bookin']) OR (empty($_SESSION['bookin']))) {
             echo $response = json_encode(array(
            "status" => false,
            "msg"    => "No Data Found!"
        ));
        die();
    }


                $amount    = $_SESSION['bookin'][0]['amount']/100;
                $bookingID = $_SESSION['bookin'][0]['bookingID'];
                $name      = $_SESSION['bookin'][0]['name'];
                $email     = $_SESSION['bookin'][0]['email'];
                $phone     = $_SESSION['bookin'][0]['phone'];
                $address   = $_SESSION['bookin'][0]['address'];
                $city      = $_SESSION['bookin'][0]['city'];
                $zipcode   = $_SESSION['bookin'][0]['zipcode'];
                $country   = $_SESSION['bookin'][0]['country'];
                $FileName  = $_SESSION['bookin'][0]['doc_file'];
                $need      = $_SESSION['bookin'][0]['need'];
                $indate    = $_SESSION['bookin'][0]['in_date'];
                $outdate   = $_SESSION['bookin'][0]['out_date'];
                $category  = $_SESSION['bookin'][0]['category'];
                $adult     = $_SESSION['bookin'][0]['adult'];
                $child     = $_SESSION['bookin'][0]['child'];
                $memid    = $_SESSION['userid'];
    $resid = NULL;
    $status = 1;
    $sql = "INSERT INTO reservation_list SET
            res_g_name             = :res_g_name,
            res_g_phone            = :res_g_phone,
            res_g_email            = :res_g_email,
            res_g_address          = :res_g_address,
            res_g_city             = :res_g_city,
            res_g_zipcode          = :res_g_zipcode,
            res_g_country          = :res_g_country,
            res_g_adult            = :res_g_adult,
            res_g_child            = :res_g_child,
            res_g_doc              = :res_g_doc,
            res_mem_id_ref         = :res_mem_id_ref,
            res_status             = :res_status";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':res_g_name',$name);
            $insert->bindParam(':res_g_phone',$phone);
            $insert->bindParam(':res_g_email',$email);
            $insert->bindParam(':res_g_address',$address);
            $insert->bindParam(':res_g_city',$city);
            $insert->bindParam(':res_g_zipcode',$zipcode);
            $insert->bindParam(':res_g_country',$country);
            $insert->bindParam(':res_g_adult',$adult);
            $insert->bindParam(':res_g_child',$child);
            $insert->bindParam(':res_g_doc',$FileName);
            $insert->bindParam(':res_mem_id_ref',$memid);
            $insert->bindParam(':res_status',$status);
            $insert->execute();
            if($insert->rowCount() > 0){
                $resid = $PDO->lastInsertId();
            }else {
                echo $response = json_encode(array(
                    "status" =>false,
                    "msg"    =>"Something Wrong"
                ));
                die();
            }


        $cat_details= CheckExists("room_category","roomcat_id = '$category' AND roomcat_status=1");

        $roomfind = $PDO->prepare("SELECT * FROM room_list WHERE room_roomcat_id_ref ='$category' AND room_status = 1 AND room_id NOT IN ( SELECT reservation_rooms.resrooms_room_id_ref FROM reservation_rooms 
        INNER JOIN reservation_list on reservation_rooms.resrooms_res_id_ref = reservation_list.res_id AND res_status = 1 WHERE 
        (reservation_rooms.resrooms_out <= '$indate' AND reservation_rooms.resrooms_in >= '$outdate') 
        OR
        (reservation_rooms.resrooms_out < '$indate' AND reservation_rooms.resrooms_in >= '$outdate')                  
        OR 
        (reservation_rooms.resrooms_out > '$indate' AND reservation_rooms.resrooms_in < '$outdate')) ORDER BY room_no ASC LIMIT $need ");
        $roomfind->execute(); 
        $avlrooms=$roomfind->fetchAll();
        $exbed = 0;
        $roomlist =null;
        foreach ($avlrooms as $eachroom) {
            $sql = "INSERT INTO reservation_rooms SET
            resrooms_res_id_ref    = :resrooms_res_id_ref,
            resrooms_room_id_ref   = :resrooms_room_id_ref,
            resrooms_roomtype      = :resrooms_roomtype,
            resrooms_room_no       = :resrooms_room_no,
            resrooms_roomprice     = :resrooms_roomprice,
            resrooms_exbed_qty     = :resrooms_exbed_qty,
            resrooms_extrabed_price = :resrooms_extrabed_price,
            resrooms_in            = :resrooms_in,
            resrooms_out           = :resrooms_out";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':resrooms_res_id_ref',$resid);
            $insert->bindParam(':resrooms_room_id_ref',$eachroom['room_id']);
            $insert->bindParam(':resrooms_roomtype',$cat_details->roomcat_name);
            $insert->bindParam(':resrooms_room_no',$eachroom['room_no']);
            $insert->bindParam(':resrooms_roomprice',$cat_details->roomcat_price);
            $insert->bindParam(':resrooms_exbed_qty',$exbed);
            $insert->bindParam(':resrooms_extrabed_price',$cat_details->roomcat_extrabed);
            $insert->bindParam(':resrooms_in',$indate);
            $insert->bindParam(':resrooms_out',$outdate);
            $insert->execute();
            $roomlist = $eachroom['room_no'].",";
        }
        $roomlist =  rtrim($roomlist,",");

            if(empty($resid)){
                echo $response = json_encode(array(
                "status" =>false,
                "msg"    =>"Something Wrong"
                ));
             die();
            }
            $type = 1;
            $sql = "INSERT INTO reservation_pay SET
            pay_res_id_ref       = :pay_res_id_ref,
            pay_create_at        = :pay_create_at,
            pay_update_at        = :pay_update_at,
            pay_amount           = :pay_amount,
            pay_transaction      = :pay_transaction,
            pay_type             = :pay_type";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':pay_res_id_ref',$resid);
            $insert->bindParam(':pay_create_at',$nowTime);
            $insert->bindParam(':pay_update_at',$nowTime);
            $insert->bindParam(':pay_amount',$amount);
            $insert->bindParam(':pay_transaction',$payid);
            $insert->bindParam(':pay_type',$type);
            $insert->execute();
            if($insert->rowCount() > 0){
                echo $response = json_encode(array(
                    "status" => true,
                    "msg"    => "my-bookings"
            
                ));
                unset($_SESSION['bookin']);
                unset($_SESSION['curcat']);
                unset($_SESSION['curin']);
                unset($_SESSION['curout']);
                unset($_SESSION['search']);
$emailmsg = '<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Booking Confirmation Mail</title>
<style>img{border:none;-ms-interpolation-mode:bicubic;max-width:100%}body{background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}table{border-collapse:separate;mso-table-lspace:0;mso-table-rspace:0;width:100%}table td{font-family:sans-serif;font-size:14px;vertical-align:top}.body{background-color:#f6f6f6;width:100%}.container{display:block;margin:0 auto!important;max-width:580px;padding:10px;width:580px}.content{box-sizing:border-box;display:block;margin:0 auto;max-width:580px;padding:10px}.main{background:#fff;border-radius:3px;width:100%}.wrapper{box-sizing:border-box;padding:20px}.content-block{padding-bottom:10px;padding-top:10px}.footer{clear:both;margin-top:10px;text-align:center;width:100%}.footer td,.footer p,.footer span,.footer a{color:#999;font-size:12px;text-align:center}h1,h2,h3,h4{color:#000;font-family:sans-serif;font-weight:400;line-height:1.4;margin:0;margin-bottom:30px}h1{font-size:35px;font-weight:300;text-align:center;text-transform:capitalize}p,ul,ol{font-family:sans-serif;font-size:14px;font-weight:400;margin:0;margin-bottom:15px}p li,ul li,ol li{list-style-position:inside;margin-left:5px}a{color:#3498db;text-decoration:underline}.btn{box-sizing:border-box;width:100%}.btn > tbody > tr > td{padding-bottom:15px}.btn table{width:auto}.btn table td{background-color:#fff;border-radius:5px;text-align:center}.btn a{background-color:#fff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:700;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize}.btn-primary table td{background-color:#3498db}.btn-primary a{background-color:#3498db;border-color:#3498db;color:#fff}.last{margin-bottom:0}.first{margin-top:0}.align-center{text-align:center}.align-right{text-align:right}.align-left{text-align:left}.clear{clear:both}.mt0{margin-top:0}.mb0{margin-bottom:0}.preheader{color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0}.powered-by a{text-decoration:none}hr{border:0;border-bottom:1px solid #f6f6f6;margin:20px 0}@media only screen and (max-width: 620px){table[class=body] h1{font-size:28px!important;margin-bottom:10px!important}table[class=body] p,table[class=body] ul,table[class=body] ol,table[class=body] td,table[class=body] span,table[class=body] a{font-size:16px!important}table[class=body] .wrapper,table[class=body] .article{padding:10px!important}table[class=body] .content{padding:0!important}table[class=body] .container{padding:0!important;width:100%!important}table[class=body] .main{border-left-width:0!important;border-radius:0!important;border-right-width:0!important}table[class=body] .btn table{width:100%!important}table[class=body] .btn a{width:100%!important}table[class=body] .img-responsive{height:auto!important;max-width:100%!important;width:auto!important}}@media all{.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:100%}.apple-link a{color:inherit!important;font-family:inherit!important;font-size:inherit!important;font-weight:inherit!important;line-height:inherit!important;text-decoration:none!important}#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}.btn-primary table td:hover{background-color:#34495e!important}.btn-primary a:hover{background-color:#34495e!important;border-color:#34495e!important}}
.sub{font-size:20px;font-weight:600;text-align:center;color:green;margin-bottom:15px;margin-top:15px}
.table-bordered{border: 1px solid #ddd;border-collapse:collapse;border-spacing:0;margin-bottom:15px;}
.table-bordered>tbody>tr>td{border: 1px solid #ddd;padding:8px}
.table-bordered a{text-decoration:none;}
.mailtitle{font-size: 23px;font-weight: 600;}
.mailtitle span{color:green;text-transform:capitalize;}
@media screen and (max-width: 767px){
.table-responsive {width: 100%;margin-bottom: 15px;overflow-y: hidden;-ms-overflow-style: -ms-autohiding-scrollbar;border: 1px solid #ddd;min-height: .01%;overflow-x: auto;}
.table-responsive>tr>td {white-space: nowrap;}
}
</style>
</head>
<body class="">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
<tr>
<td>&nbsp;</td>
<td class="container">
<div class="content">
<table role="presentation" class="main">
<tr>
<td class="wrapper">
<img src="https://www.hotelthegrandchandiram.com/email/logo.png">
<h2 class="sub">Hotel The Grand Chandiram Booking Confirmation</h2>
<p class="mailtitle">Hi <span >My '.$mem_name.',</span></p>
<p>Thank you for booking at Hotel The Grand Chandiram.</p>
<table class="table-bordered table-responsive">
<tr>
<td>Booking ID</td>
<td colspan="3">'.$resid.'</td>
</tr>
<tr>
<td>Rooms</td>
<td colspan="3">'.$roomlist.'</td>
</tr>
<tr>
<td>Booking Date</td>
<td colspan="3">'.date("d/m/Y", strtotime($indate)).'</td> 
</tr>
<tr>
<td>Adults</td>
<td colspan="3">'.$adult.'</td>
</tr>
<tr>
<td>Time</td>
<td colspan="3">'.date("d-M-Y D H:i:s").'</td>
</tr>
</table>
<br/> 
<br/> 
<p>Please carry Identification proof at the time of check In.</p>
<p>Regards,<br/>
<b>Hotel The Grand Chandiram<b></p>
</td>
</tr>
</table>
<div class="footer">
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="content-block powered-by">Powered by <a href="https://www.hotelthegrandchandiram.com/">Hotel The Grand Chandiram</a></td>
</tr>
</table>
</div>
</div>
</td>
<td>&nbsp;</td>
</tr>
</table>
</body>
</html>';
     require 'vendor/autoload.php';  
      require 'vendor/phpmailer/phpmailer/src/Exception.php';
      require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require 'vendor/phpmailer/phpmailer/src/SMTP.php';
      $mail = new PHPMailer\PHPMailer\PHPMailer();          
      try {
            $mail->SMTPDebug =0;                                 
            // $mail->isSMTP();                                    
            $mail->Host       = 'relay-hosting.secureserver.net';                            
            $mail->SMTPAuth   = false;                               
            $mail->Username   = 'admin@hotelguruestate.com';                 
            $mail->Password   = 'UrhZfbqby5df';                             
            $mail->SMTPSecure = 'ssl';                            
            $mail->Port       = 25;                                   
            $mail->setFrom('admin@hotelguruestate.com', 'Booking Confirmation');
            $mail->addAddress($mem_email);
            $mail->isHTML(true);                                 
            $mail->Subject   = 'Booking Confirmation';
            $mail->Body      = $emailmsg;
            if(!$mail->Send()){} 
      } catch (Exception $e) {} 

                die();
            }else {
                echo $response = json_encode(array(
                    "status" =>false,
                    "msg"    =>"Something Wrong"
                ));
                die();
            }
 



 




}