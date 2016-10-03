<?php
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if (isset($_SESSION['System']) && !class_exists('Main')) {
	require_once $_SESSION['System']['info']['root_path'] . 'Main.class.php';
}

Main::asynch();

if (!Main\integrated () || !function_exists('\Vpm\sort_core')) {
	die ( "Es gab einen Unbekannten Fehler, beim Einbinden der Funktionen" );
}

\Vpm\sort_core ();
// Main\return_new_error ( "Halli Hallo" );
// Main\return_new_warning ( "Halli Hallo nochmal!" );
// Main\return_new_dialogbox("", "500px", "500px", "1000px", "1000px", "required/newdate.php", "");