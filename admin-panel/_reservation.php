<?php 
include '_auth.php'; 
$operation =trim($_REQUEST['operation']);
if (empty($operation)){
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "Something Wrong"
    ));
    die();
}
if ($operation=="fetcrooms") {
    // $stmt = "SELECT * FROM roomnum WHERE num_room_id_ref ='$bid' AND num_id NOT IN (
        
    //     SELECT reservation.res_num_id_ref FROM reservation WHERE 
       
    //     (reservation.res_out >'$indate' AND reservation.res_out <='$outdate') OR 
    //     (reservation.res_in >='$indate' AND reservation.res_in <'$outdate')
    // )";
    $bid=(!empty($_POST['cat']))?FilterInput($_POST['cat']):null;
    $indate=(!empty($_POST['indate']))?FilterInput($_POST['indate']):null;
    $outdate=(!empty($_POST['outdate']))?FilterInput($_POST['outdate']):null;
   if(empty($indate)){
    echo "Date Range is wrong!";
    die();
   }
   if(empty($bid)){
    echo '<div class="col-12"><div class="alert alert-danger"><strong>Select Room Category!</strong></div></div>';
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
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date Range is wrong!</strong></div></div>';
         die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date is before Today!</strong></div></div>';
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

    $stmt = "SELECT * FROM room_list WHERE room_roomcat_id_ref ='$bid' AND room_status = 1 AND room_id NOT IN (
        
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
    if($stmt->rowCount()>0){ 
        $k=1;
        while ($row=$stmt->fetch()){ ?>
            <div class="custom-control custom-checkbox mb-30 form-group col-sm-2">
            <input type="checkbox" class="custom-control-input listcheckbox" name="roomids[]" value="<?php echo $row['room_id']; ?>" id="catid<?php echo $k; ?>">
            <label class="custom-control-label" for="catid<?php echo $k; ?>">Room <?php echo $row['room_no']; ?></label>
            </div>
            <?php 
            $k++; 
        }
        echo '<div class=" col-12"><a href="javascript:void(0);" id="addtoroom" class="button x-small pull-right"><i class="fa fa-plus"> Add Rooms</i></a></h5></div>';
    }else{
        echo  '<div class="col-12"><div class="alert alert-danger"><strong>Sorry No Rooms Available!</strong></div></div>';
    }



}








elseif ($operation == 'addtoroom') {
// unset($_SESSION['remember']);
// die();
$indate=(!empty($_POST['indate']))?FilterInput($_POST['indate']):null;
$outdate=(!empty($_POST['outdate']))?FilterInput($_POST['outdate']):null;
$arr  = (!empty($_POST['arr']))?$_POST['arr']:NULL; 
$eqty  = (!empty($_POST['eqty']))?FilterInput($_POST['eqty']):0; 

    if (empty($arr)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Select Rooms"
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
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date Range is wrong!</strong></div></div>';
         die();
    }
    $today = Date('Y-m-d');
    if($indate<$today OR $outdate<$today){
        echo '<div class="col-12"><div class="alert alert-danger"><strong>Date is before Today!</strong></div></div>';
         die();
    }


// $listarr = explode(",",$arr);
foreach ($arr as $eachlist) {
    $reg_list = CheckExists("room_list","room_id  = '$eachlist' AND room_status<>2");
    if (!empty($reg_list)) {
        
            if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
                $_SESSION['remember'] = array(
                    array(
                        'room_id'   => $eachlist,
                        'in_date'   => $indate,
                        'out_date'   => $outdate,
                        'extrabedqty' => $eqty,
                        'time' => Date('Y-m-d H:i:s')
                    ),
                );
            }else{
                $is_exists = 1;
                foreach ($_SESSION['remember'] as $key => $val) {
                    if ($val['room_id'] == $eachlist) {
                        $is_exists = 2;
                    
                        $_SESSION['remember'][$key]["in_date"] = $indate;
                        $_SESSION['remember'][$key]["out_date"] = $outdate;
                        $_SESSION['remember'][$key]["extrabedqty"] = $eqty;
                        $_SESSION['remember'][$key]["time"] = Date('Y-m-d H:i:s');
                    }else{
                        $is_exists = 1;
                    }
                    if ($is_exists==2) {break;}
                }
                if ($is_exists==1) {
                    $new = array(
                            'in_date'   => $indate,
                            'out_date'   => $outdate,
                            'room_id'   => $eachlist,
                            'extrabedqty'  => $eqty,
                            'time' => Date('Y-m-d H:i:s')
                        );
                    array_push($_SESSION['remember'], $new);
                }
            }


    }
}

  // print_r($_SESSION['remember']);
    echo $response = json_encode(array(
            "status" => true,
            "msg"    => "Success"
    ));
    die();
}







elseif ($operation=="fetchrooms") {
    

   if(isset($_SESSION['remember']) AND (!empty($_SESSION['remember'])) ) {  ?>
        <div class="card card-statistics">
        <div class="card-title eachhigh">
        <h5>Seleted Rooms:</h5>
        </div>
        <div class="card-body">
        <div class="table-responsive">          
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
        <th>#</th>
        <th>Room Type</th>
        <th>Room No.</th>
        <th>Room Price</th>
        <th>Days</th>
        <th>Extra Bed</th>
        <th>Total</th>
        <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
    <?php
        $sum = 0;
        $i=1;
        foreach ($_SESSION['remember'] as $key => $val) {
            $curid   = $val['room_id'];
            $indate   = $val['in_date'];
            $outdate   = $val['out_date'];
            $diff = abs(strtotime($outdate) - strtotime($indate));
            $days = ($diff/(60*60*24));

            $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
            $profind->execute(); 
            $chk_pro=$profind->fetch();

            if (!empty($chk_pro)) {   
            $sum = $sum + ($chk_pro['roomcat_price']*$days) + ($val['extrabedqty']*$chk_pro['roomcat_extrabed']);
            ?>      
<tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $chk_pro['roomcat_name']; ?></td>
        <td><?php  echo $chk_pro['room_no']; ?></td>
        <td>₹ <?php  echo $chk_pro['roomcat_price']; ?></td>
        <td><?php  echo $days; ?></td>
        <td>
     <div class="counter-add-item"> <a class="decrease-btn qtyupdatebtn" data-id="<?= $chk_pro['room_id'];  ?>" data-action="1" href="javascript:;">-</a> 
    <input type="text" value="<?php echo  $val['extrabedqty']; ?>"> <a class="increase-btn qtyupdatebtn" data-id="<?= $chk_pro['room_id'];  ?>" data-action="2" href="javascript:;">+</a>
    </div>
    <p>Extrabed: (₹ <?php echo $chk_pro['roomcat_extrabed']; ?>) x (<?php echo $val['extrabedqty']; ?>) = <?php echo $chk_pro['roomcat_extrabed']*$val['extrabedqty']; ?> </p>
    </td>
            <td>₹ <?php echo ($chk_pro['roomcat_price']*$days)+($chk_pro['roomcat_extrabed']*$val['extrabedqty']); ?></td>
            <td><a class="item-delete delbtn" href="javascript:;" data-id='<?php echo $curid; ?>' title="Remove From Table"><i class="fa fa-trash-o"></i></a></td>
      </tr>

            <?php
            }
        } ?>
<tr>
<td colspan="5"></td>

<td class="grandtotal" style="background: #45671b; color: white; font-size: 16px;">Total = <span id="totalsum">₹ <?php echo $sum; ?></span></td>
<td colspan="2"></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

<?php

    }
    else{

        echo '';
    }



}

elseif ($operation=="deleteroom") {
    $id = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
    if(empty($id)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    if(!filter_var($id, FILTER_VALIDATE_INT)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }


        if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
            echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
            ));
            die();
        }else{
            foreach ($_SESSION['remember'] as $key => $val) {
                if ($val['room_id'] == $id) {
                    unset($_SESSION['remember'][$key]);
                    echo $response = json_encode(array(
                        "status" => true,
                        "msg"    => "Success"
                    ));
                    die();
                }
            }
        }


    echo $response = json_encode(array(
                                "status" => false,
                                "msg"    => "Failed"
                            ));

}

       elseif ($operation=="quantityupdate") {
    $id     = (!empty($_POST['id']))?FilterInput($_POST['id']):null;
    $action = (!empty($_POST['action']))?FilterInput($_POST['action']):null;
    if(empty($id) OR empty($action)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    if(!filter_var($id, FILTER_VALIDATE_INT)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }
    $actionarr = array(1,2);
    if (!in_array($action, $actionarr)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
        ));
        die();
    }


        if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
            echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Not Found"
            ));
            die();
        }else{
            foreach ($_SESSION['remember'] as $key => $val) {
                if ($val['room_id'] == $id) {

                    if ($action==1) {
                        $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"] - 1;
                    }else{
                        $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"] + 1;
                    }
                    if ($_SESSION['remember'][$key]["extrabedqty"]<0) {
                        $_SESSION['remember'][$key]["extrabedqty"] = 0;
                    }
                    $_SESSION['remember'][$key]["extrabedqty"] = $_SESSION['remember'][$key]["extrabedqty"];
                    $_SESSION['remember'][$key]["time"] = Date('Y-m-d H:i:s');
                    echo $response = json_encode(array(
                        "status" => true,
                        "msg"    => "Success"
                    ));
                    die();
                }
            }
        }

}

       

elseif ($operation=="fetchtotal") {

    if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
            echo "0";
        }else{

            $sum=0;
        foreach ($_SESSION['remember'] as $key => $val) {
            $curid   = $val['room_id'];
            $indate   = $val['in_date'];
            $outdate   = $val['out_date'];
            $diff = abs(strtotime($outdate) - strtotime($indate));
            $days = ($diff/(60*60*24));
            $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
            $profind->execute(); 
            $chk_pro=$profind->fetch();

            if (!empty($chk_pro)) {   
            $sum = $sum + ($chk_pro['roomcat_price']*$days) + ($val['extrabedqty']*$chk_pro['roomcat_extrabed']);      
            }
        } 
            echo $sum;

     }
  }

elseif ($operation=="addreserv") {
    if(!isset($_SESSION['remember']) OR (empty($_SESSION['remember']))) {
             echo $response = json_encode(array(
            "status" => false,
            "msg"    => "No Rooms Added!"
        ));
        die();
    }

     $sum=0;
        foreach ($_SESSION['remember'] as $key => $val) {
            $curid   = $val['room_id'];
            $indate   = $val['in_date'];
            $outdate   = $val['out_date'];
            $diff = abs(strtotime($outdate) - strtotime($indate));
            $days = ($diff/(60*60*24));
            $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
            $profind->execute(); 
            $chk_pro=$profind->fetch();

            if (!empty($chk_pro)) {   
            $sum = $sum + ($chk_pro['roomcat_price']*$days) + ($val['extrabedqty']*$chk_pro['roomcat_extrabed']);      
            }
        } 


    $name         = (!empty($_POST['name']))?FilterInput($_POST['name']):null; 
    $phone        = (!empty($_POST['phone']))?FilterInput($_POST['phone']):null;  
    $email        = (!empty($_POST['emails']))?FilterInput($_POST['emails']):null;  
    $address      = (!empty($_POST['address']))?FilterInput($_POST['address']):null; 
    $city         = (!empty($_POST['city']))?$_POST['city']:null; 
    $zipcode      = (!empty($_POST['zipcode']))?FilterInput($_POST['zipcode']):null;
    $country      = (!empty($_POST['country']))?FilterInput($_POST['country']):null;
    $adult        = (!empty($_POST['adult']))?FilterInput($_POST['adult']):null;
    $child        = (!empty($_POST['child']))?$_POST['child']:0;
    $chkintime    = (!empty($_POST['chkintime']))?$_POST['chkintime']:NULL;
    $notes        = (!empty($_POST['notes']))?$_POST['notes']:NULL;
    $status       = (!empty($_POST['status']))?$_POST['status']:0;

    if(empty($name) OR empty($phone)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter Name and Phone No."
        ));
        die();
    } 

    if(empty($adult)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter No. of Adults"
        ));
        die();
    } 

    if(empty($address)) {
        echo $response = json_encode(array(
                "status" => false,
                "msg"    => "Enter Address"
        ));
        die();
    } 

        if(!empty($_FILES['docfile']['name'])){

        $valid_ext   = array('jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx');
        $mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $maxsize     = 10 * 1024 * 1024;

        $FileName    = FilterInput($_FILES['docfile']['name']);
        $tmpName     = $_FILES['docfile']['tmp_name'];
        $FileTyp     = $_FILES['docfile']['type'];
        $FileSize    = $_FILES['docfile']['size'];
        $MimeType    = mime_content_type($_FILES['docfile']['tmp_name']);

        $ext      = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
        $FileName = basename(strtolower($FileName),".".$ext);
        $FileName = FileName($name).'_'.time().rand(10000,999999999).'.'.$ext;

        if(!in_array($ext, $valid_ext)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Extention Not Allowed"
        ));
        die();
        }
        if($FileSize>$maxsize){
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"Max file Size: 10MB"
        ));
        die();
        }
        if(!in_array($FileTyp, $mime_filter)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Not Supported"
        ));
        die();
        }
        if(!in_array($MimeType, $mime_filter)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"File Not Supported"
        ));
        die();
        }

        $path = "../images/guestdoc/".$FileName;
        if (!move_uploaded_file($_FILES["docfile"]["tmp_name"],$path)) {
        echo $response = json_encode(array(
        "status" =>false, 
        "msg"  =>"Cant Upload File"
        ));
        die();
        }
        chmod($path,0644);
}
 $memid = $_SESSION['adminid'];

    $resid = NULL;
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
            res_g_note             = :res_g_note,
            res_g_totalamt         = :res_g_totalamt,
            res_g_intime           = :res_g_intime,
            res_by                 = :res_by,
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
            $insert->bindParam(':res_g_totalamt',$sum);
            $insert->bindParam(':res_g_intime',$chkintime);
            $insert->bindParam(':res_g_note',$notes);
            $insert->bindParam(':res_by',$memid);
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

            if(empty($resid)){
                  echo $response = json_encode(array(
                    "status" =>false,
                    "msg"    =>"Something Wrong"
                ));
                die();
            }
            $chkin         = (!empty($_POST['chkin']))?FilterInput($_POST['chkin']):null; 
            $chkout        = (!empty($_POST['chkout']))?FilterInput($_POST['chkout']):null;  
               if(empty($chkout) OR ($chkin==$chkout)){
                $chkout = date("Y-m-d",strtotime($chkin. ' + 1 days'));
               }

                if (!empty($chkin)) {
                  $chkin  = date("Y-m-d",strtotime($chkin));
                }
                if (!empty($chkout)) {
                  $chkout  = date("Y-m-d",strtotime($chkout));
                }
                if($chkin>$chkout){
                    echo 'Date Range is wrong!';
                     die();
                }
                $today = Date('Y-m-d');
                if($chkin<$today OR $chkout<$today){
                    echo 'Date is before Today!';
                     die();
                }
             foreach ($_SESSION['remember'] as $key => $val) {
                $curid   = $val['room_id'];
                $indate   = $val['in_date'];
                $outdate   = $val['out_date'];
                $profind = $PDO->prepare("SELECT * FROM room_list INNER JOIN room_category ON room_list.room_roomcat_id_ref=room_category.roomcat_id WHERE room_list.room_id ='$curid' AND room_category.roomcat_status=1 AND room_list.room_status=1");
            $profind->execute(); 
            $chk_pro=$profind->fetch();
                if (!empty($chk_pro)) {

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
            $insert->bindParam(':resrooms_room_id_ref',$curid);
            $insert->bindParam(':resrooms_roomtype',$chk_pro['roomcat_name']);
            $insert->bindParam(':resrooms_room_no',$chk_pro['room_no']);
            $insert->bindParam(':resrooms_roomprice',$chk_pro['roomcat_price']);
            $insert->bindParam(':resrooms_exbed_qty',$val['extrabedqty']);
            $insert->bindParam(':resrooms_extrabed_price',$chk_pro['roomcat_extrabed']);
            $insert->bindParam(':resrooms_in',$indate);
            $insert->bindParam(':resrooms_out',$outdate);
            $insert->execute();
                
            }
            }
            if(empty($resid)){
                echo $response = json_encode(array(
                "status" =>false,
                "msg"    =>"Something Wrong"
                ));
             die();
            }

            $advance = (!empty($_POST['advance']))?FilterInput($_POST['advance']):0;
            if($advance >= 0) {
            
            $sql = "INSERT INTO reservation_pay SET
            pay_res_id_ref         = :pay_res_id_ref,
            pay_by                 =:pay_by,
            pay_create_at         = :pay_create_at,
            pay_update_at         = :pay_update_at,
            pay_amount           = :pay_amount";
            $insert = $PDO->prepare($sql);
            $insert->bindParam(':pay_res_id_ref',$resid);
            $insert->bindParam(':pay_by',$memid);
            $insert->bindParam(':pay_create_at',$nowTime);
            $insert->bindParam(':pay_update_at',$nowTime);
            $insert->bindParam(':pay_amount',$advance);
            $insert->execute();
            if($insert->rowCount() > 0){
                echo $response = json_encode(array(
                    "status" =>true,
                    "msg"    =>"Successfully Added"
                ));
                unset($_SESSION['remember']);
                die();
            }else {
                echo $response = json_encode(array(
                    "status" =>false,
                    "msg"    =>"Something Wrong"
                ));
                die();
            }
        }else{
                echo $response = json_encode(array(
                "status" =>false,
                "msg"    =>"Enter Correct Advance Value"
                ));
                 die();  
        }

}
