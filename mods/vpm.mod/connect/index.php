<?php 
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();
?>

#!/usr/bin/php -q
<br>
<?php

ini_set ( "display_errors", "1" );
error_reporting ( E_ALL | E_STRICT );

$counter = 0;

function get_crawler_prefix() : string {
	global $counter;
	return "crawler@[" . date ('H') . ":" . date ('i') . ":" . date ('s') . "," . date('u') . ":" . ++$counter . "]$ ";
}

function send_request(string $ip, array $get_conten = []) : string {
	if(strpos($ip, 'http://') === false) {
		$ip = 'http://' . $ip . "/?";
	} else {
		$ip .= "/?";
	}
	$vars = "";
	foreach($get_conten as $key => $value) {
		$vars .= "&" . $key . "=" . $value;
	}
	$ip .= $vars;

	echo "[Notice] sending http_request to " . $ip . "<br>";

	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $ip,
		CURLOPT_USERAGENT => 'request_for_monitor_state_change'
	]);
	// Send the request & save response to $resp
	$resp = curl_exec($curl);

	// Close request to clear up some resources
	curl_close($curl);

	return $resp;
}

//to edit the crontan, use this:
//sudo nano /etc/crontab
//do not use:
//crontab -e
//since you would have to deal with permissions!

echo get_crawler_prefix() . "connection-logger started .. " . "<br>";


echo get_crawler_prefix() . "loading configs .. " . "<br>";
$file_content = file_get_contents("connect_config.json");
$file_content = utf8_encode($file_content);
$json_data = json_decode($file_content, true);

print_r($json_data); echo "<br>";

echo get_crawler_prefix() . "done loading configs .. " . "<br>";

echo get_crawler_prefix() . "creating variables .. " . "<br>";

$main_class_path = $json_data['main_class'];
$root_path = $json_data['abspath'];
$vpm_mod_path = $json_data['vpm_mod_path'];
$vpmconnect_mod_path = $json_data['connect_path'];
$start_uhr = $json_data['startuhr'];
$end_uhr = $json_data['enduhr'];
$ip = $json_data['ip'];
$active = $json_data['active'];
$custom_log_path = "monitorzustand.".date("Y").date("m").date("d").".log";
$path_to_vpm_core = $json_data['core_path'];

echo get_crawler_prefix() . "done. Checking if crawler active .. " . "<br>";
if(!$active) {
	echo get_crawler_prefix() . "detected shutdown via config .. shutting down" . "<br>";
	die();
}
echo get_crawler_prefix() . "done .." . "<br>";


echo get_crawler_prefix() . "started service .. [OK]" . "<br>";

if(!file_exists($main_class_path)) {
	echo "[Warning]" . get_crawler_prefix() . "could not find the main Class!" . "<br>";
} else {
	echo get_crawler_prefix() . "found the main-class" . "<br>";
	require $main_class_path;
}
// require "/var/www/html/was/Main.class.php";
require $root_path . "socket/main.socket/index.php";
require $vpm_mod_path . "index.php";
require $root_path . "packages/vpm.package/index.php";

echo get_crawler_prefix() . "gathered requirements .. [OK]" . "<br>";

Main::asynch();
Main\init();

echo get_crawler_prefix() . "setting_infos .. [OK]" . "<br>";

\Main\set_info("root_path", $root_path);
\Main\set_info("mod_folder_root_path", $path_to_vpm_core);
print_r(\Main\info()); echo "<br>";

echo get_crawler_prefix() . "sorting core .. " . "<br>";
\Vpm\find_core_path($path_to_vpm_core);
Vpm\sort_core();
$core = \Vpm\core_array();

echo get_crawler_prefix() . "internals of the core: " . "<hr>";

var_dump($core);

echo "<hr>";
echo "<br>";
echo get_crawler_prefix() . "starting routine .. [OK]" . "<br>";
echo get_crawler_prefix() . "getting current stand .. " . "<br>";
$normal_day_stand = Vpm\get_normal_day_state();
$inserted_dates_states = \Vpm\get_insert_dates_state($core);
echo get_crawler_prefix() . "the normal stand is: ";  var_dump($normal_day_stand);  echo "<br>";
echo get_crawler_prefix() . "the inserted stand is: ";  var_dump($inserted_dates_states);  echo "<br>";

echo get_crawler_prefix() . "logging .. " . "<br>";
echo get_crawler_prefix() . "done .. " . "<br>";

$monitors_on = false;

if(is_int($inserted_dates_states)) {
	$hrstate = $core[$inserted_dates_states][5] == 2 ? "eingeschaltet" : "ausgeschaltet";
	\Main\log("Auf Grund von \"" . $core[$inserted_dates_states][0] . "\" sind die Monitore " . $hrstate, "crawler", $custom_log_path);
	$monitors_on = $hrstate == "eingeschaltet" ? true : false;
} else {
	if($normal_day_stand) {
		\Main\log("Die Monitore sind eingeschaltet", "crawler", $custom_log_path);
		$monitors_on = true;
	} else if(!$normal_day_stand) {
		\Main\log("Die Monitore sind ausgeschaltet", "crawler",$custom_log_path);
	}
}

echo get_crawler_prefix() . "sending http_get_request to " . $ip . " .. " . "<br>";

$response = send_request($ip, ["monitor" => $monitors_on ? "1111" : "0000", "crawler" => "true", "current_id" => $counter, "main_id" => get_crawler_prefix()]);
--$counter;

echo get_crawler_prefix() . "done .. " . "<br>";
echo get_crawler_prefix() . "response: " . "<br>";

echo "<hr>" . $response . "<hr>";

echo get_crawler_prefix() . "crawling finished!" . "<br>";
