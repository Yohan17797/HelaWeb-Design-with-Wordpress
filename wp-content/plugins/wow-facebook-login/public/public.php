<?php if ( ! defined( 'ABSPATH' ) ) exit;
	function wow_add_fb_login_form() {
	?>
	<script type="text/javascript">
		if(jQuery.type(has_social_form) === "undefined"){
			var has_social_form = false;
			var socialLogins = null;
		}
		jQuery(document).ready(function(){
			(function($) {
				if(!has_social_form){
					has_social_form = true;
					var loginForm = $('#loginform,#registerform,#front-login-form,#setupform');
					socialLogins = $('<div class="newsociallogins" style="text-align: center;"><div style="clear:both;"></div></div>');
					if(loginForm.find('input').length > 0)
					loginForm.prepend("<h3 style='text-align:center;'><?php _e('OR'); ?></h3>");
					loginForm.prepend(socialLogins);
					socialLogins = loginForm.find('.newsociallogins');
				}
				if(!window.fb_added){
					socialLogins.prepend('<?php echo addslashes(preg_replace('/^\s+|\n|\r|\s+$/m', '', wow_fb_sign_button())); ?>');
					window.fb_added = true;
				}
			}(jQuery));
		});
	</script>
	<?php
		global $wow_fb_settings;
		ob_start();
		include( 'css/style.php' );
		$wow_fb_style=ob_get_contents();
		ob_end_clean();	
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.min.css');
		wp_enqueue_style( 'wow-fb-login', plugin_dir_url( __FILE__ ) . 'css/style.css');
		wp_add_inline_style( 'wow-fb-login',$wow_fb_style );		
	}
	add_action('login_form', 'wow_add_fb_login_form');
	add_action('register_form', 'wow_add_fb_login_form');
	add_action('bp_sidebar_login_form', 'wow_add_fb_login_form');
	function wow_fb_jquery() {
		wp_enqueue_script('jquery');
	}
	add_action('login_form_login', 'wow_fb_jquery');
	add_action('login_form_register', 'wow_fb_jquery');
	function show_wow_fb_login_button($atts) {
		global $wow_fb_settings;
		extract(shortcode_atts(array('redirect' => ""), $atts));
		if ( !is_user_logged_in() ) {
			if ($redirect == ''){
				$goto = get_permalink();
			}
			else {
				$goto = $redirect;
			}
			$fblogin = '<a href="' . wow_fb_login_url() .'&redirect_to=' .$goto.'&action=login" rel="nofollow" class="wow-fb-login">' . $wow_fb_settings['fb_login_button'] . '</a>';		
			ob_start();
			include( 'css/style.php' );
			$wow_fb_style=ob_get_contents();
			ob_end_clean();	
			wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style( 'wow-fb-login', plugin_dir_url( __FILE__ ) . 'css/style.css');
			wp_add_inline_style( 'wow-fb-login',$wow_fb_style );			
			return $fblogin;
		}
		
	}
add_shortcode('Wow-Facebook-Login', 'show_wow_fb_login_button');