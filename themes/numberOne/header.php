<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<link rel="icon" type="image/x-icon"
	href="themes/numberOne/resources/kgslogo300_klein.ico" sizes="32x32">
	<script src="themes/numberOne/javascript/draggable.js" ></script>
</head>


<div class='header'>Monitorsteuerung der KGS Rastede</div>
<?php 

if (file_exists ( AUTOLOAD )) {
	require AUTOLOAD;
	$_SESSION ['autoload'] = AUTOLOAD;
} else {
	die ( "autoload.php wurde nicht gefunden!" . "<br>" . "Dies deutet auf einen Fehler der Configuration hin!" );
}

Main\include_css ( "css/index.css" );
Main\include_css ( "css/dialogbox.css" );
Main\include_css ( "css/error.css" );
Main\include_css ( "css/warning.css" );
Main\include_css ( "css/header.css" );
Main\include_css ( "css/navigation.css" );
Main\include_css ( "css/delete.css" );

?>
<div id="body_table">
<?php
// 	require 'required/index_table.php';
	Main\include_other_php ( "required/index_table.php" );
?>
</div>

<?php
// Main\include_javascript ( "javascript/draggable.js" );
?>
<script>
	$(document).ready(function(){
		var body_content = document.getElementById("body_content");
		var nav_info = document.getElementById("nav-info");
		$(body_content).load(window.location.href + first_path);
		$(nav_info).html("<b>Home</b>");
	});
</script>
