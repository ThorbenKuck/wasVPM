<?php 

require 'password.functions.php';

if(!PasswordCompat\binary\check()) {
	Main\top_level_debug("password.package", "(Warning)Das Passwort-Package ist nicht mit der Vorgegebenen PHP-Version Kompatibel!");
}




?>