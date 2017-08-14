<?php
	if(isset($_SERVER['SHELL']) && empty($_SERVER['PHP_SELF'])){
		set_time_limit(0);
		$path_arr = explode("/",$_SERVER['argv'][0]);
		unset($path_arr[count($path_arr)-1]);
		$plugin_path = implode("/",$path_arr);
		unset($path_arr[count($path_arr)-1]);
		unset($path_arr[count($path_arr)-1]);
		unset($path_arr[count($path_arr)-1]);
		$path = implode("/",$path_arr);


		require_once($path.'/wp-config.php');
		require_once($path.'/wp-includes/wp-db.php');
		global $wpdb;
		require_once($plugin_path.'/includes/ean-functions.php');
		sendNewsletter();
	}
?>