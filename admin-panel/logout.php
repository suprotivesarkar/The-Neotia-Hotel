<?php
session_start();
unset($_SESSION['islogin']);
unset($_SESSION['memberid']);
session_destroy();
header("Location:./");
exit();
?>