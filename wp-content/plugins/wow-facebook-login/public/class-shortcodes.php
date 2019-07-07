<?php
	/**
		* Shortcodes
		*
		* @package     Social
		* @subpackage  Shortcodes
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	class Wow_Facebook_Login_Shortcodes { 
	
		private $arg;
		
		public function __construct($arg) {	
			$this->plugin_name      = $arg['plugin_name'];
			$this->plugin_menu      = $arg['plugin_menu'];
			$this->version          = $arg['version'];
			$this->pref             = $arg['pref'];			
			$this->slug             = $arg['slug'];
			$this->plugin_dir       = $arg['plugin_dir'];
			$this->plugin_url       = $arg['plugin_url'];
			$this->plugin_home_url  = $arg['plugin_home_url'];
					
			$this->option = get_option($this->pref);
			
			add_shortcode('Wow-Facebook-Login', array($this, 'shortcode') );	
		}	
		
		
		// Registration Shortcode		
		public function shortcode($atts) { 
			$param = $this->option;
			extract(shortcode_atts(array('redirect' => "", 'text' => ""), $atts));
			$login_button = !empty($param['fb_login_button']) ? $param['fb_login_button'] : 'LOGIN';
			$text = !empty($text) ? $text : $login_button;
			if(empty($redirect)){
				if(!empty($param['fb_redirect_reg'])){
					$redirect = $param['fb_redirect_reg'] == 'auto' ? get_permalink() : $param['fb_redirect_reg'];
				}
				else{
					$redirect = get_permalink();
				}
			}			
			$url = site_url() . '?loginFacebook=1';
			$shortcode = '<a href="'.$url.'&redirect='.$redirect.'&action=login" rel="nofollow" class="wow-fb-login">'.$text.'</a>';							
			ob_start();
			include( 'css/style.php' );
			$style=ob_get_contents();
			ob_end_clean();
			wp_enqueue_style( $this->slug.'-icon', $this->plugin_url . 'asset/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
			wp_enqueue_style( $this->slug.'-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
			wp_add_inline_style( $this->slug.'-style',$style );
			if( !is_user_logged_in() ) {
				return $shortcode;
			}
			else{
				return;
			}
		}		
	}
	
