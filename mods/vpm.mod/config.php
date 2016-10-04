<?php 
if (! defined ( 'PATH_TO_VPM_MOD' )) {
	/**
	 * The absolute path to the vpm-mod
	 */
	define ( 'PATH_TO_VPM_MOD', dirname ( __FILE__ ) . '/' );
	\Main\set_new_info('path_to_vpm_mod', PATH_TO_VPM_MOD);
}

if (! defined ( 'PATH_TO_VPM_CORE' )) {
	/**
	 * The absolute path to the vpm-core
	 */
	define ( 'PATH_TO_VPM_CORE', dirname ( __FILE__ ) . '/core/core.csv' );
	\Main\set_new_info('path_to_vpm_core', PATH_TO_VPM_CORE);
}

if (! defined ( 'VPM_FREE_DAYS' )) {
	/**
	 * The absolute path to the vpm-free-day-file
	 */
	define ( 'VPM_FREE_DAYS', dirname ( __FILE__ ) . '/ferientage/' );
	\Main\set_new_info('vpm_free_days', VPM_FREE_DAYS);
}

if (! defined ( 'VPM_CONNECT' )) {
	/**
	 * The absolute path to the vpm-connect folder
	 */
	define ( 'VPM_CONNECT', dirname ( __FILE__ ) . '/connect/' );
	\Main\set_new_info('vpm_connect', VPM_CONNECT);
}

// Zur Logik der Berechnung

if (! defined ( 'STARTUHR' )) {
	define ( 'STARTUHR', 715 );
	\Main\set_new_info('start_time', STARTUHR);
}

if (! defined ( 'ENDUHR' )) {
	define ( 'ENDUHR', 1615 );
	\Main\set_new_info('end_time', ENDUHR);
}

if (! defined ( 'CONNECTION_IP' )) {
	define ( 'CONNECTION_IP', "127.0.0.1/was/test2.php" );
	\Main\set_new_info('connection_ip', CONNECTION_IP);
}