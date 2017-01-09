<?php
if(!defined('DB_HOST')) {
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'vpm');
}

if(!defined('PURGE_HASHES_QUERY')) {
	define('GET_NEEDED_LOGIN_INFORMATIONS', "SELECT `passhash`, `permissions` FROM `users` WHERE username=?");
	define('PURGE_HASHES_QUERY', "DELETE FROM `Logins`");
	define('GET_PASSHAS_FOR_USERNAME', "SELECT passhash FROM `users` WHERE username=?");
	define('GET_HASHES_FOR_USERNAME', "SELECT `hash` FROM `Logins` WHERE `id`=(SELECT `id` FROM `users` WHERE username=?)");
	define('GET_USERNAME_FOR_HASH', "SELECT `username` FROM `users` WHERE `id`=(SELECT `id` FROM `Login` WHERE `hash`=?)");
	define('DELETE_HASH_FOR_USER', "DELETE FROM `Logins` WHERE `id`=(SELECT `id` FROM `users` WHERE `UserName`=?)");
	define('LOGIN_QUERY', "INSERT INTO `Logins` (`id`, `hash`) VALUES ((SELECT `id` FROM `users` WHERE `username`=?), ?)");
	define('GET_PERMISSION_FOR_USERNAME', "SELECT `permissions` FROM `users` WHERE `username`=?");
}