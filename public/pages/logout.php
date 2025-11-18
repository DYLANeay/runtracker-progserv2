<?php
session_start();

$_SESSION = array();


session_destroy();


header('Location:/public/pages/login.php'); 
exit();
?>