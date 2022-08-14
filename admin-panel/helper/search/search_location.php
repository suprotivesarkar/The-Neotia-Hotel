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
$startpoint= $_GET['term']; 
$stmt      = "SELECT *  FROM places_list WHERE place_name LIKE "."'%".$startpoint."%'  AND place_status<>2";
$stmt.=" ORDER BY RAND() LIMIT 10";
$stmt=$PDO->prepare($stmt);
$stmt->execute();
if($stmt->rowCount()>=0){ 
    while ($row = $stmt->fetch()) {
        $subar= array(
            "id"    => $row['place_id'],
            "value" => $row['place_name']
        );
        array_push($data,$subar);
    }
}
echo json_encode($data);