<?php

Main\top_level_debug('vpm.mod', ["(Title)vpm-mod stacktrace", "(StackStart)instantiation"], false, "instantiation");

Main\top_level_debug('vpm.mod', "loading vpm-config ... ", false, "instantiation");

require dirname(__FILE__) . "/config.php";

\Main\set_new_setting("Test", true);

if (isset(Main\info()['root_path']) && isset(Main\info()['path_to_vpm_mod'])) {
	Main\top_level_debug('vpm.mod', "[OK]", "instantiation");
	Main\top_level_debug('vpm.mod', "creating dependencies ... ", "instantiation");
	$to_json = [
			"connect_path" => Main\info()['path_to_vpm_mod'] . "/connect/",
			"abspath" => Main\info()['root_path'],
			"main_class" => Main\info()['root_path'] . "Main.class.php",
			"vpm_mod_path" => Main\info()['path_to_vpm_mod'],
			"startuhr" => Main\info()['start_time'],
			"enduhr" => Main\info()['end_time'],
			"ip" => Main\info()['connection_ip'],
			"core_path" => \Main\info()['path_to_vpm_core'],
			"active" => true
	];
	$json_string = json_encode ( $to_json );
	$config_path = Main\info()['vpm_connect'] . "connect_config.json";
	if ($handle = fopen ( $config_path, "w" )) {
		fwrite ( $handle, $json_string );
		fclose ( $handle );
		Main\top_level_debug('vpm.mod', "[OK]", false, "instantiation");
	} else {
		Main\top_level_debug("vpm.mod", "(Error)Die Datei: \"" . $config_path . "\" konnte nicht bearbeitet werden!", false, "instantiation");
	}
} else {
	Main\top_level_debug("vpm.mod", "(Fatal-Error)the config has not been loaded correctly ... This mod cannot be used!", false, "instantiation");
}
\Main\top_level_debug("vpm.mod", "(StackEnd)", false, "instantiation");