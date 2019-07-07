<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');	
	$table = $wpdb->prefix . 'wow_social_users';	
	$sql = "CREATE TABLE " . $table." (
	ID int(11) NOT NULL AUTO_INCREMENT,
	type VARCHAR(20) NOT NULL, 
	identifier varchar(100) NOT NULL,
	first_name TEXT,
	last_name TEXT,	
	email TEXT,
	link TEXT,	
	UNIQUE KEY ID (ID)
	) DEFAULT CHARSET=utf8;";
	dbDelta($sql);
	