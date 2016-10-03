<?php

$file = dirname(__FILE__) . "/logs/test.log";
// Schreibt den Inhalt in die Datei zurück
file_put_contents($file, print_r($_GET, true));
echo "received request with get content:";
echo "<hr>";
var_dump($_GET);
echo "<hr>";
