<?php
$root = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
require_once("admin-panel/config/config.php");
require_once("admin-panel/config/function.php");
// $socialdet = SocialInfo();
// $baseurl="http://localhost/hotelmanagement/";
?>