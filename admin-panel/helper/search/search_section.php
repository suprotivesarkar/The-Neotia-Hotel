<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
    echo $response = json_encode(array(
            "status" =>false,
            "msg"    => "Unauthorized Access"
    ));
    die();
} 
//$searchtxt = (!empty($_REQUEST['id']))?(strval($_REQUEST['id'])):null; 
$searchtxt = (!empty($_REQUEST['q']))?(strval($_REQUEST['q'])):null; 
$data=array();
$sql="SELECT * FROM section_list WHERE section_name LIKE '".$searchtxt."%' AND section_status<>2 LIMIT 10";
$runqu = $PDO->prepare($sql);
$runqu->execute();
if($runqu->rowCount()>0){
    while($rows=$runqu->fetch()){ 
        $data[] = array(
	        'id'   => $rows['section_id'],
	        'name' => $rows['section_name']
        );
    }
   echo json_encode($data);
} 
?>