<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
    echo $response = json_encode(array(
            "status" =>false,
            "msg"    => "Unauthorized Access"
    ));
    die();
} 
$operation =trim($_REQUEST['operation']);
if (empty($operation)){
    echo $response = json_encode(array(
            "status" => false,
            "msg"    => "Something Wrong"
    ));
    die();
}
if ($operation=="fetcModel") {
    $bid=(!empty($_POST['bid']))?FilterInput($_POST['bid']):null;
    $stmt = $PDO->prepare("SELECT * FROM filter_models WHERE model_brand_id_ref='$bid' AND model_status=1 ORDER BY model_name ASC");
    $stmt->execute(); 
    if($stmt->rowCount()>0){
            echo  '<option value=""> --Please Select-- </option>';
          while ($row=$stmt->fetch()){ ?>
            <option value="<?php echo $row['model_id']; ?>"><?php echo $row['model_name']; ?></option>
          <?php } 
    }else {
        echo  '<option value=""> --No Data Found-- </option>';
    }
}