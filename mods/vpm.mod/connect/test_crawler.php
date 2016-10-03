<?php

echo "test-crawler started .. " . PHP_EOL;

require "/var/www/html/was/Main.class.php";
require "/var/www/html/was/socket/main.socket/index.php";
require "/var/www/html/was/mods/vpm.mod/index.php";
require "/var/www/html/was/packages/vpm.package/index.php";

echo "gathered requirements .. " . PHP_EOL;

Main::asynch();

echo "started service .. " . PHP_EOL;

echo "starting routine .. " . PHP_EOL;

while(true) {
	echo "setting_infos .. " . PHP_EOL;

	\Main\set_info("root_path", "/var/www/html/was/");

	var_dump(\Main\info());

	echo "waiting 60 seconds .. " . PHP_EOL;
	sleep(60);
	echo "sorting core .. " . PHP_EOL;
	Vpm\sort_core();
	echo "getting current stand .. " . PHP_EOL;
	$stand = Vpm\current_state(true);
	echo "the stand is: ";  var_dump($stand);  echo PHP_EOL;

	echo "logging .. " . PHP_EOL;
	if($stand) {
		\Main\log("Die Monitore sind eingeschaltet", "modern", "monitorzustand.".date("Y").date("m").date("d").".log");
	} else {
		\Main\log("Die Monitore sind ausgeschaltet", "modern","monitorzustand.".date("Y").date("m").date("d").".log");
	}
	echo "done .. restarting .. " . PHP_EOL;
}
