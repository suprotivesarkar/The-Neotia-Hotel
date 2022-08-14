<?php 
require_once("config/config.php");
require_once("config/function.php");
if(empty($_SESSION['adminid'])){header("Location:./");}
// $MAX_IDLE_TIME = 60*60;
// if (!isset($_SESSION['timeout_idle'])) {
//     	$_SESSION['timeout_idle'] = time() + $MAX_IDLE_TIME;
// } else {
//     if ($_SESSION['timeout_idle'] < time()) {   
//         session_destroy();
//     } else {
//         $_SESSION['timeout_idle'] = time() + $MAX_IDLE_TIME;
//     }
// }
$uid = $_SESSION['adminid'];
$chk_auth = CheckExists("users_list","user_id = '$uid' AND user_canlogin=1 AND user_status = 1");
if (empty($chk_auth)) {
	header("Location:./");
	die();
}
$currole = $chk_auth->user_role_id_ref;

?>