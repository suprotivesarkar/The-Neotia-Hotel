<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
    echo $response = json_encode(array(
            "status" =>false,
            "msg"    => "Unauthorized Access"
    ));
    die();
}   
$data=$subar=array();
$sterm = (!empty($_GET['term']))?CleanInput(FilterInput($_GET['term'])):null;  
$id    = (!empty($_REQUEST['id']))?(CleanInput(FilterInput($_REQUEST['id']))):null;  
$stmt  = "SELECT *  FROM product_list WHERE pro_name LIKE "."'%".$sterm."%'  AND pro_status<>2 AND pro_variant_id_ref IS NULL ";
if (!empty($id)) {
   $stmt.= " AND pro_id<>'$id'";
}
$stmt.=" ORDER BY RAND() LIMIT 10"; 
$stmt=$PDO->prepare($stmt);
$stmt->execute();
if($stmt->rowCount()>=0){ 
    while ($row = $stmt->fetch()) {
        $subar= array(
            "id"    => $row['pro_id'],
            "value" => $row['pro_name']
        );
        array_push($data,$subar);
    }
} 
echo json_encode($data);