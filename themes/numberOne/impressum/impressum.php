<?php 

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);
Main\return_new_dialogbox("", "200px", "200px" , "1000px", "1200px", "impressum/impressum.html", "");

?>
