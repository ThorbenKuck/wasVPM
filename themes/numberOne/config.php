<?php

if(!defined('AUTOLOAD'))  {
// This will be runned once per site-load
	define ( "AUTOLOAD", realpath(\Main\info()['active_theme_path'] . "/autoload.php"));
	\Main\set_new_info('autoload_root_path', AUTOLOAD);
}
if(!defined('THEME_NAME'))  {
// Optionale Informationen zum theme
	define ( "THEME_NAME", "Standard" );
	\Main\set_new_info('active_theme_name', THEME_NAME);
}

if(!defined('THEME_AUTHOR'))  {
	define ( "THEME_AUTHOR", "Thorben" );
	\Main\set_new_info('active_theme_author', THEME_AUTHOR);
}

if(!defined('THEME_VERSION'))  {
	define ( "THEME_VERSION", "0.1" );
	\Main\set_new_info('active_theme_version', THEME_VERSION);
}

if(!defined('THEME_TYPE'))  {
	define ( "THEME_TYPE", "alpha" );
	\Main\set_new_info('active_theme_type', THEME_TYPE);
}

if(!defined('CSS_ACTIVE_THEME')) {
	define("CSS_ACTIVE_THEME", dirname(__FILE__) . "/css");
	\Main\set_new_info('css_of_active_theme', CSS_ACTIVE_THEME);
}