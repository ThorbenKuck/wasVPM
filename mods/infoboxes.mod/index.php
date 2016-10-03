<?php

Main\include_css ( "mods/infoboxes.mod/css/info.css" , false );

if(\Users\is_logdin()) {
	require 'infoboxen_logdin.php';
} else {
	require 'infoboxen_not_logdin.php';
}


Main\top_level_debug("infoboxes.mod", "Das ist ein Test, erstellt in dem Mod \"Infoboxes\"", true, "test");
Main\top_level_debug("infoboxes.mod", "Das ist ein Test, erstellt in dem Mod \"Infoboxes\"", true, "test");

?>