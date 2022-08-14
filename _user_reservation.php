<?php 
require_once "vendor/autoload.php";
use Razorpay\Api\Api;
include '_top.php'; 
$operation=(!empty($_POST['operation']))?FilterInput($_POST['operation']):null;
if (empty($operation)){
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "Something Wrongfsd"
    ));
    die();
}
if ($operation=="searchrom") {
     $cid=(!empty($_POST['category']))?FilterInput($_POST['category']):null;
    $indate=(!empty($_POST['chkin']))?FilterInput($_POST['chkin']):null;
    $outdate=(!empty($_POST['chkout']))?FilterInput($_POST['chkout']):null;
   if(empty($indate)){
   	echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger"><strong>Indate Invalid!</strong></div>'
        ));
        die();
   }
   if(empty($cid)){
   	echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger"><strong>Select Room Category!</strong></div>'
        ));
        die();
   }
   if(empty($outdate) OR ($indate==$outdate)){
    $outdate = date("Y-m-d",strtotime($indate. ' + 1 days'));
   }

    if (!empty($indate)) {
      $indate  = date("Y-m-d",strtotime($indate));
    }
    if (!empty($outdate)) {
      $outdate  = date("Y-m-d",strtotime($outdate));
    }
    if($indate>$outdate){
    	   	echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger"><strong>Date Range is wrong!</strong></div>'
        ));
        die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
    		echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger"><strong>Date is before Today!</strong></div>'
        ));
        die();
    }
    

    $chk_model= CheckExists("room_category","roomcat_id = '$cid' AND roomcat_status=1");
    if (empty($chk_model)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Room Type Not Found"
        ));
        die();
    }

    			unset($_SESSION['search']);
    		
                $_SESSION['search'] = array(
                    array(
                        // 'cat_id'   => $cid,
                        'in_date'   => $indate,
                        'out_date'   => $outdate,
                        
                    ),
                );
     echo $response = json_encode(array(
                "status" => true,
                "msg"    => "rooms/".$chk_model->roomcat_slug
        ));
        die();

}

elseif ($operation=="fetcrom") {
    
    $bid=(!empty($_POST['cat']))?FilterInput($_POST['cat']):null;
    $indate=(!empty($_POST['indate']))?FilterInput($_POST['indate']):null;
    $outdate=(!empty($_POST['outdate']))?FilterInput($_POST['outdate']):null;
   if(empty($indate)){
    echo "Date Range is wrong!";
    die();
   }
   if(empty($bid)){
    echo '<div class="col-sm-12"><div class="alert alert-danger"><strong>Select Room Category!</strong></div></div>';
    die();
   }
   if(empty($outdate) OR ($indate==$outdate)){
    $outdate = date("Y-m-d",strtotime($indate. ' + 1 days'));
   }

    if (!empty($indate)) {
      $indate  = date("Y-m-d",strtotime($indate));
    }
    if (!empty($outdate)) {
      $outdate  = date("Y-m-d",strtotime($outdate));
    }
    if($indate>$outdate){
        echo '<div class="col-sm-12"><div class="alert alert-danger"><strong>Date Range is wrong!</strong></div></div>';
         die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
        echo '<div class="col-sm-12"><div class="alert alert-danger"><strong>Date is before Today!</strong></div></div>';
         die();
    }
    

    $chk_model= CheckExists("room_category","roomcat_id = '$bid' AND roomcat_status=1");
    if (empty($chk_model)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Room Type Not Found"
        ));
        die();
    }

    $stmt = "SELECT COUNT(*)AS count FROM room_list WHERE room_roomcat_id_ref ='$bid' AND room_status = 1 AND room_id NOT IN (
        
        SELECT reservation_rooms.resrooms_room_id_ref FROM reservation_rooms 
        INNER JOIN reservation_list on reservation_rooms.resrooms_res_id_ref = reservation_list.res_id AND res_status = 1 WHERE 
        (reservation_rooms.resrooms_out <= '$indate' AND reservation_rooms.resrooms_in >= '$outdate') 
        OR
        (reservation_rooms.resrooms_out < '$indate' AND reservation_rooms.resrooms_in >= '$outdate')                  
        OR 
        (reservation_rooms.resrooms_out > '$indate' AND reservation_rooms.resrooms_in < '$outdate')
    )";
    $stmt= $PDO->prepare($stmt);
    $stmt->execute(); 
    $res = $stmt->fetch();
    if($res['count']>0){ 
        $_SESSION['curcat'] = $bid;
        $_SESSION['curin'] = $indate;
        $_SESSION['curout'] = $outdate;
         ?>
               <div class="col-sm-12"><div class="alert alert-success"><strong><?php echo $res['count']; ?> Rooms are Available!</strong></div></div>
               <div class="form-group col-sm-3">
                    <label for="uadult">Adult</label>
                    <input type="number" min="1" class="form-control" id="uadult" name="uadult" placeholder="Enter no. of Adult" value="">
                    </div>
                    <div class="form-group col-sm-3">
                    <label for="uchild">Child</label>
                    <input type="number" class="form-control" id="uchild" name="uchild" placeholder="Enter no. of Child" value="">
                    </div>
            <?php 
       
    }else{
        echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Sorry, No Rooms Available!</strong></div></div>';
    }

}

elseif ($operation=="romneed") {
   $adult=(!empty($_POST['ad']))?FilterInput($_POST['ad']):null;
   $child=(!empty($_POST['ch']))?FilterInput($_POST['ch']):0;
        if(!isset($_SESSION['curcat']) OR empty($_SESSION['curcat']))
        {
            echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Something is wrong!</strong></div></div>';
            die();
        }
         $cat = $_SESSION['curcat'];
        $indate = $_SESSION['curin'];
        $outdate = $_SESSION['curout'];
        $stmt = "SELECT COUNT(*)AS count FROM room_list WHERE room_roomcat_id_ref ='$cat' AND room_status = 1 AND room_id NOT IN (
        
        SELECT reservation_rooms.resrooms_room_id_ref FROM reservation_rooms 
        INNER JOIN reservation_list on reservation_rooms.resrooms_res_id_ref = reservation_list.res_id AND res_status = 1 WHERE 
        (reservation_rooms.resrooms_out >'$indate' AND reservation_rooms.resrooms_out <='$outdate') OR 
        (reservation_rooms.resrooms_in >='$indate' AND reservation_rooms.resrooms_in <'$outdate') 
    )";
    $stmt= $PDO->prepare($stmt);
    $stmt->execute(); 
    $res = $stmt->fetch();

       
        $chk_cat = CheckExists("room_category","roomcat_id = '$cat' AND roomcat_status=1");
        if (empty($chk_cat)) {
            echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Something is wrong!</strong></div></div>';
            die();
         }
         if(empty($adult) OR !ctype_digit($adult) OR $adult<=0){
            echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Enter Adult in numeric!</strong></div></div>';
            die();
         }
          $diff = abs(strtotime($outdate) - strtotime($indate));
        $days = ($diff/(60*60*24));
        $totad = $chk_cat->roomcat_adult;
        $need = $adult / $totad;
        if(($child>$chk_cat->roomcat_child*ceil($need))){
            echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Child capacity is '.$chk_cat->roomcat_child. ' per room!</strong></div></div>';
            die();
         }
         if(ceil($need) > $res['count'] ){
            echo  '<div class="col-sm-12"><div class="alert alert-danger"><strong>Room availability exceeded! Rooms availability is '.$res['count'].'</strong></div></div>';
            die();
         }


?>
        <div class="col-sm-6 col-sm-offset-3">
<div class="table-responsive">
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>Adults:</td>
            <td><?php echo $adult; ?></td>
        </tr>
         <tr>
            <td>Child:</td>
            <td><?php echo $child; ?></td>
        </tr>
        <tr>
            <td>Rooms Required:</td>
            <td><?php echo ceil($need); ?></td>
        </tr>
        <tr>
            <td>Room Category:</td>
            <td><?php echo $chk_cat->roomcat_name; ?> </td>
        </tr>
        <tr>
            <td>Room Price:</td>
            <td>Rs. <?php echo $chk_cat->roomcat_price; ?></td>
        </tr>
        <tr>
            <td>Total:</td>
            <td>Rs. <?= $chk_cat->roomcat_price; ?> x <?= ceil($need); ?>rooms = Rs. <?php echo ($chk_cat->roomcat_price*ceil($need)); ?></td>
        </tr>
         <tr>
            <td>Grand Total:</td>
            <td>Rs. <?php echo ($chk_cat->roomcat_price*ceil($need)); ?> x <?= $days ?>days = Rs. <?php echo ($chk_cat->roomcat_price*ceil($need))*$days; ?></td>
        </tr>

        <tr>
            <td>Tax:</td>
            <td>Rs. <?php echo Tax(($chk_cat->roomcat_price*ceil($need))*$days); ?>(<?php $tax = 0; if($chk_cat->roomcat_price*ceil($need)*$days>1000 && $chk_cat->roomcat_price*ceil($need)*$days<=7500){ $tax = 12;}elseif ($chk_cat->roomcat_price*ceil($need)*$days>7500) {$tax = 18;} echo $tax;?>% gst)</td>
        </tr>
        <tr>
            <td>Total Sum:</td>
            <td>Rs. <?php echo (($chk_cat->roomcat_price*ceil($need))+Tax($chk_cat->roomcat_price*ceil($need)))*$days; ?></td>
        </tr>

    </tbody>
</table>
</div>
<p>Do you want to book now?<button type="button" class="button col-sm-12 btn-block btn-lg btn-warning entrybtn" id="booknowp">Book Now</button></p>

</div>

<?php
}


elseif ($operation=="savebookin") {


$adult=(!empty($_POST['ad']))?FilterInput($_POST['ad']):null;
$child=(!empty($_POST['ch']))?FilterInput($_POST['ch']):0;
$name=(!empty($_POST['name']))?FilterInput($_POST['name']):null;
$phone=(!empty($_POST['phone']))?FilterInput($_POST['phone']):null;
$email=(!empty($_POST['email']))?FilterInput($_POST['email']):null;
$address=(!empty($_POST['address']))?FilterInput($_POST['address']):null;
$city=(!empty($_POST['city']))?FilterInput($_POST['city']):null;
$zipcode=(!empty($_POST['zipcode']))?FilterInput($_POST['zipcode']):null;
$country=(!empty($_POST['country']))?FilterInput($_POST['country']):null;

if(empty($_SESSION['userid'])){
    echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger">Please, Login Before Payment!</div>'
        ));
        die();
}

  if(empty($name) OR empty($phone)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger">Enter Name and Phone No.!</div>'
        ));
        die();
    } 
 if(empty($address)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger">Enter Residential Address!</div>'
        ));
        die();
    } 
    if(empty($city)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger">Enter City!</div>'
        ));
        die();
    } 
    if(empty($zipcode)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => '<div class="alert alert-danger">Enter Zipcode!</div>'
        ));
        die();
    } 
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Email is Invalid!</div>'
    ));
    die();
} 
if(!preg_match('/^[6-9]{1}[0-9]{9}$/',$phone) OR !filter_var($phone, FILTER_VALIDATE_INT)){
    echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Phone Number Should be 10 Digit Numeric</div>'
    ));
    die();
} 

 if(!isset($_SESSION['curcat']) OR empty($_SESSION['curcat']))
        {
           echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Category is empty!</div>'
    ));
        }
$category=$_SESSION['curcat'];
$indate=$_SESSION['curin'];
$outdate=$_SESSION['curout'];
$chk_cat = CheckExists("room_category","roomcat_id = '$category' AND roomcat_status=1");
        if (empty($chk_cat)) {
           echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Invalid Category!</div>'
    ));
         }
 if(empty($adult) OR !ctype_digit($adult) OR $adult<=0){
            echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Please Enter Adult(in numeric)!</div>'
    ));
         }
        $diff = abs(strtotime($outdate) - strtotime($indate));
        $days = ($diff/(60*60*24));
        $totad = $chk_cat->roomcat_adult;
        $need = $adult / $totad;
        $rneed = ceil($need);
        $total = ($chk_cat->roomcat_price*ceil($need))*$days;
        $gstreq=  Tax($total);
        $totalsum = $total + $gstreq;
        if(($child>$chk_cat->roomcat_child*ceil($need))){
        echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="col-sm-12"><div class="alert alert-danger"><strong>Child capacity is '.$chk_cat->roomcat_child. ' per room!</strong></div></div>'
            ));
         }
if(empty($indate)){
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Date Range is Wrong!</div>'
    ));
}
if(empty($outdate) OR ($indate==$outdate)){
$outdate = date("Y-m-d",strtotime($indate. ' + 1 days'));
}

if (!empty($indate)) {
  $indate  = date("Y-m-d",strtotime($indate));
}
if (!empty($outdate)) {
  $outdate  = date("Y-m-d",strtotime($outdate));
}
if($indate>$outdate){
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Date Range is Wrong!</div>'
    ));
}
$today = Date('Y-m-d');
if($indate<$today OR $outdate<$today){
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Date is Before Today!</div>'
    ));
}         


$FileName = null;
if(!empty($_FILES['docfile']['name'])){

$valid_ext   = array('jpeg', 'jpg', 'png', 'pdf');
$mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
$maxsize     = 10 * 1024 * 1024;

$FileName    = FilterInput($_FILES['docfile']['name']);
$tmpName     = $_FILES['docfile']['tmp_name'];
$FileTyp     = $_FILES['docfile']['type'];
$FileSize    = $_FILES['docfile']['size'];
$MimeType    = mime_content_type($_FILES['docfile']['tmp_name']);

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

$path = "images/guestdoc/".$FileName;
if (!move_uploaded_file($_FILES["docfile"]["tmp_name"],$path)) {
  echo $response = json_encode(array(
        "status" => false,
        "msg"    => '<div class="alert alert-danger">Cant Upload File!</div>'
    ));
}
chmod($path,0644);
}
        
       $key     = Razorpaykey()['key'];
        $secret  = Razorpaykey()['key_secret'];
        $orderid = "#".time().substr(md5(microtime()), 0, 10);
        $api     = new Api($key,$secret);
        $order = $api->order->create(array(
          'receipt'         => $orderid,
          'amount'          => $totalsum*100, 
          'payment_capture' => 1,
          'currency' => 'INR'
              )
            );

                $_SESSION['bookin'] = array(
                    array(
                        'bookingID'  => $order['id'],
                        'category'   => $category,
                        'in_date'    => $indate,
                        'out_date'   => $outdate,
                        'adult'      => $adult,
                        'child'      => $child,
                        'name'       => $name,
                        'phone'      => $phone,
                        'email'      => $email,
                        'address'    => $address,
                        'city'       => $city,
                        'zipcode'    => $zipcode,
                        'country'    => $country,
                        'doc_file'   => $FileName,
                        'need'       => $rneed,
                        'amount'     => $totalsum*100

                    ),
                );
                      if(!empty($_SESSION['bookin'])){
                        echo $response = json_encode(array(
                            "status" => true,
                            "msg"    => "bookingprocess"
                        ));
                    }else{
                        echo $response = json_encode(array(
                            "status" => false,
                            "msg"    => "Something Wrong, try again!"
                        ));
                        die();
                    }
}


