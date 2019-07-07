<?php

/************************************************************************
		Add Plugin Menus
***************************************************************************/


//Voting Settings Menu Add


if( ! function_exists('it_epoll_settings_menu')){
		
		add_action('admin_menu', 'it_epoll_settings_menu');

		function it_epoll_settings_menu(){
			
			add_submenu_page('itwpMods_addons', __('Voting System'), __('Voting System'), 'manage_options', 'it_epoll_system', 'it_epoll_system');
		
		}

}

//Voting Settings Page Callabck


if( ! function_exists('it_epoll_system')){


		function it_epoll_system(){

			include_once('backend/it_epoll_setting.php');

		}

}?>