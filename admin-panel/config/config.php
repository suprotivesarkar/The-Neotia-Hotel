<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Kolkata");
$ip       = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$nowTime  = Date('Y-m-d H:i:s');
$browser  = null;
$dbhost   = "localhost";
$dbname   = "theneotiahotel";
$username = "root";
$password = "";


function testdb_connect($dbhost, $dbname, $username, $password){
   $PDO = new PDO("mysql:host=$dbhost; dbname=$dbname", $username, $password);
   $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
   return $PDO;
}try{
   $PDO = testdb_connect($dbhost, $dbname, $username, $password);
}catch(PDOException $e){
   echo $e->getMessage();
}
?>