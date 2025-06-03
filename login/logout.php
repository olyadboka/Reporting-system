<?php
session_start();

session_regenerate_id(true);

$_SESSION = array();

session_destroy();

header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0"); 

header("Location: ./login.php");
exit();