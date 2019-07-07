<?php
	/**
		* Plugin Name:       Wow Facebook Login
		* Plugin URI:        https://wordpress.org/plugins/wow-facebook-login/
		* Description:       Highly customizable Facebook auth button
		* Version:           2.1
		* Author:            Wow-Company
		* Author URI:        https://wow-estore.com
		* License:           GPL-2.0+
		* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
		
	*/	
	
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;	
	
	if( !class_exists( 'Wow_Company' )) {
		require_once plugin_dir_path( __FILE__ ) . 'asset/class-wow-company.php';				
	}
	
			
	// Uninstall plugin
	register_uninstall_hook( __FILE__, array( 'Wow_Facebook_Login', 'uninstall' ) );
	
	if( !class_exists( 'Wow_Facebook_Login' ) ) {
		final class Wow_Facebook_Login {
			
			private static $instance;
			
			const PREF = 'wow_fb_login';			
			
			public static function uninstall() {
				delete_option( self::PREF );					
			}
			
						
			public static function instance() {
				
				if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Wow_Facebook_Login ) ) {					
					$arg = array(
						'plugin_name'      => 'Wow Facebook Login',
						'plugin_menu'      => 'Facebook Login',
						'plugin_home_url'  => 'wow-facebook-login',
						'version'          => '2.1',
						'base_file'        => basename(__FILE__),
						'slug'             => dirname(plugin_basename(__FILE__)),
						'plugin_dir'       => plugin_dir_path( __FILE__ ),
						'plugin_url'       => plugin_dir_url( __FILE__ ),
						'pref'             => self::PREF,					
					);				
					self::$instance = new Wow_Facebook_Login;	
					
					register_activation_hook( __FILE__, array(self::$instance, 'plugin_activate' ) );				
					
					self::$instance->includes();
					self::$instance->adminlinks = new Wow_Facebook_Login_ADMIN_LINKS($arg);
					self::$instance->admin      = new Wow_Facebook_Login_ADMIN($arg);
					self::$instance->shortcodes = new Wow_Facebook_Login_Shortcodes($arg);
					self::$instance->social     = new Wow_Facebook_Login_Integration($arg);
					
				}
				return self::$instance;
			}
			
			public function __clone() {
				// Cloning instances of the class is forbidden.
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ems-integration' ), '1.0' );
			}
			
			public function __wakeup() {
				// Unserializing instances of the class is forbidden.
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ems-integration' ), '1.0' );
			}
			
			private function includes() {			
				require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-links.php';			
				require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin.php';
				require_once plugin_dir_path( __FILE__ ) . 'public/class-shortcodes.php';
				require_once plugin_dir_path( __FILE__ ) . 'public/class-facebook-login.php';				
			}
			
			// Activate & diactivate
			function plugin_activate() {
				require_once plugin_dir_path( __FILE__ ) . 'includes/activator.php';	
			}
			
		}
	}
	
	function wow_facebook_loginp() {
		return Wow_Facebook_Login::instance();
	}	
	// Get Running.
	wow_facebook_loginp();