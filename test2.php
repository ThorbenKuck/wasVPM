<?php

$file = dirname(__FILE__) . "/logs/test.log";
// Öffnet die Datei, um den vorhandenen Inhalt zu laden
$current = file_get_contents($file);
// Fügt eine neue Person zur Datei hinzu
$current .= print_r($_GET, true) . PHP_EOL;
// Schreibt den Inhalt in die Datei zurück
file_put_contents($file, print_r($_GET, true));
echo "received request with get content:";
echo "<hr>";
var_dump($_GET);
echo "<hr>";