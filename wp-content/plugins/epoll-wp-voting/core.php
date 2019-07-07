<?php
//Global File Of WP Voting System By INFOTHEME INC.
//DEVELOPER: TEAM INFOTHEME
//PRO LICENSE REQUIRED TO RUN THIS TOOL
/***********************************************
ADDING MAIN MENU OF INFOTHEM PRODUCTS
************************************************/
// Add to admin_menu function




if( ! function_exists('itwpMods_ind_wp_addons')){

		add_action('admin_menu', 'itwpMods_ind_wp_addons');

		function itwpMods_ind_wp_addons() {
		
			add_menu_page(__('InfothemeWP'), __('InfothemeWP'), 'manage_options', 'itwpMods_addons', 'itwpMods_addons', '
		dashicons-info', 2); 
		
		}

}


if( ! function_exists('itwpMods_General_addSubMenu')){
		
		add_action('admin_menu', 'itwpMods_General_addSubMenu');

		function itwpMods_General_addSubMenu(){
			
			add_submenu_page('itwpMods_addons', __('INFOTHEME ADDONS'), __('Add Ons'), 'manage_options', 'itwpMods_addons', 'itwpMods_addons');
		
		}

}

//initializing page content of addons

if( ! function_exists('itwpMods_addons')){

	function itwpMods_addons(){
		include_once('core/wp_addons.php');
	}

}

//Initializing this plugins conf
include_once('module_config.php');