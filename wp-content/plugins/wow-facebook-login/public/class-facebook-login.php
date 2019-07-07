<?php
	/**
		* Interation Class
		*
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	class Wow_Facebook_Login_Integration {
		
		private $arg;
		
		function __construct( $arg ) {	
			$this->plugin_name      = $arg['plugin_name'];
			$this->plugin_menu      = $arg['plugin_menu'];
			$this->version          = $arg['version'];
			$this->pref             = $arg['pref'];			
			$this->slug             = $arg['slug'];
			$this->plugin_dir       = $arg['plugin_dir'];
			$this->plugin_url       = $arg['plugin_url'];
			$this->plugin_home_url  = $arg['plugin_home_url'];
			
			$this->option = get_option($this->pref);
			
			// Adding query vars for the WP parser
			add_filter('init', array($this, 'add_query_var') );
			// Compatibility for older versions
			add_action('parse_request', array($this, 'login_compat') );
			// For login page
			add_action('login_init', array($this, 'login') );
			
			
			add_action('parse_request', array($this, 'edit_profile_redirect') );
			
			add_action('login_form_login', array($this, 'wow_jquery') );
			add_action('login_form_register', array($this, 'wow_jquery') );	
			
			// Session notices used in the profile settings			
			add_action('admin_notices', array($this, 'admin_notice') );
			
			add_filter('bp_core_fetch_avatar', array($this, 'bp_insert_avatar'), 3, 5);
			add_filter('get_avatar', array($this, 'insert_avatar'), 5, 5);
			
			// Show button on login, registr pages
			if(!empty($this->option['fb_integrate_button'])){			
				add_action('login_form', array($this,'add_login_form') );
				add_action('register_form', array($this,'add_login_form') );
			}
			add_action('bp_sidebar_login_form', array($this,'add_login_form') );
			
			// Intagrate button to EDD & Woocommerce
			add_action( 'init', array($this, 'enable_login_sales' ) );
			
			// Connect Field in the Profile page
			add_action('profile_personal_options', array($this, 'connect_field') );
			
		}	
		
		function login() {			
			if (isset($_REQUEST['loginFacebook']) && $_REQUEST['loginFacebook'] == '1') {
				self::login_action();
			}
		}
		
		
		function login_compat() {			
			global $wp;
			if ($wp->request == 'loginFacebook' || isset($wp->query_vars['loginFacebook'])) {
				self::login_action();
			}
		}
		
		function login_action() {			
			include('facebook.php');		
		}
		
		function redirect() {			
			if (isset($_GET['redirect'])){
				$redirect = $_GET['redirect'];
			}
			else {				
				$redirect = get_transient( 'wow_facebook_login' );				
				if ($redirect === false){
					$redirect = site_url();				
				}
				else {				
					$redirect = $redirect;
				}
			}		
			
			$redirect = wp_sanitize_redirect($redirect);
			$redirect = wp_validate_redirect($redirect, site_url());
			header('LOCATION: ' . $redirect);
			delete_site_transient('wow_facebook_login');
			exit;
		}
		
		function callBackUrl() {
			$connection = !empty( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
			$url = $connection . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
			if( strpos( $url, '?' ) === false ) {
				$url.= '?';
			} 
			else {
				$url.= '&';
			}
			return $url;
		}
		
		
		
		function add_query_var() {
			global $wp;
			$wp->add_query_var('editProfileRedirect');
			$wp->add_query_var('loginFacebook');
			$wp->add_query_var('loginFacebookdoauth');
		}
		
		
		
		
		function wow_uniqid() {
			if (isset($_COOKIE['wow_uniqid'])) {
				if (get_site_transient('n_' . $_COOKIE['wow_uniqid']) !== false) {
					return $_COOKIE['wow_uniqid'];
				}
			}
			$_COOKIE['wow_uniqid'] = uniqid('wow_', true);
			setcookie('wow_uniqid', $_COOKIE['wow_uniqid'], time() + 3600, '/', '', false, true);
			set_site_transient('n_' . $_COOKIE['wow_uniqid'], 1, 3600);
			
			return $_COOKIE['wow_uniqid'];
		}
		
		
		function insert_avatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {
			
			$id = 0;
			if (is_numeric($id_or_email)) {
				$id = $id_or_email;
				} else if (is_string($id_or_email)) {
				$u  = get_user_by('email', $id_or_email);
				$id = $u->id;
				} else if (is_object($id_or_email)) {
				$id = $id_or_email->user_id;
			}
			if ($id == 0) return $avatar;
			$pic = get_user_meta($id, 'wow_profile_picture', true);
			if (!$pic || $pic == '') return $avatar;
			$avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
			
			return $avatar;
		}
		
		function bp_insert_avatar($avatar = '', $params, $id) {
			if (!is_numeric($id) || strpos($avatar, 'gravatar') === false) return $avatar;
			$pic = get_user_meta($id, 'wow_profile_picture', true);
			if (!$pic || $pic == '') return $avatar;
			$avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
			
			return $avatar;
		}
		
		
		
		function sign_button() {
			if(!empty($this->option['fb_redirect']) && $this->option['fb_redirect'] != 'auto'){
				return '<a href="' . esc_url(self::login_url() . '&redirect=' . $this->option['fb_redirect']).'" class="wow-fb-login" rel="nofollow">' . $this->option['fb_login_button'] . '</a><br />';
			}
			else {
				return '<a href="' . esc_url(self::login_url()) . '" class="wow-fb-login" rel="nofollow">' . $this->option['fb_login_button'] . '</a><br />';
			}
			
		}
		
		function link_button() {		
			return '<a href="' . esc_url(self::login_url() . '&redirect=' . self::curPageURL()) . '" class="wow-fb-login">' . $this->option['fb_link_button'] . '</a><br />';
		}
		
		function unlink_button() {		
			
			return '<a href="' . esc_url(self::login_url() . '&action=unlink&redirect=' . self::curPageURL()) . '" class="wow-fb-login">' . $this->option['fb_unlink_button'] . '</a><br />';
		}
		
		function curPageURL() {
			global $wp;		
			return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			// return home_url(add_query_arg(array(),$wp->request));
			// return admin_url('/profile.php');
		}
		
		function login_url() {			
			return site_url() . '?loginFacebook=1';
		}
		
		function edit_profile_redirect() {
			
			global $wp;
			if (isset($wp->query_vars['editProfileRedirect'])) {
				if (function_exists('bp_loggedin_user_domain')) {
					header('LOCATION: ' . bp_loggedin_user_domain() . 'profile/edit/group/1/');
					} else {
					header('LOCATION: ' . self_admin_url('profile.php'));
				}
				exit;
			}
		}
		
		
		
		function wow_jquery() {			
			wp_enqueue_script('jquery');
		}
		
		
		
		
		
		function admin_notice() {
			$user_info = wp_get_current_user();
			$notice    = get_site_transient($user_info->ID . '_wow_admin_notice');
			if ($notice !== false) {
				echo '<div class="updated">
				<p>' . $notice . '</p>
				</div>';
				delete_site_transient($user_info->ID . '_wow_admin_notice');
			}
		}
		
		
		
		function add_login_form() {
			
		?>
		<script type="text/javascript">
			if (jQuery.type(has_social_form) === "undefined") {
				var has_social_form = false;
				var socialLogins = null;
			}
			jQuery(document).ready(function () {
				(function ($) {
					if (!has_social_form) {
						has_social_form = true;
						var loginForm = $('#loginform,#registerform,#front-login-form,#setupform');
						socialLogins = $('<div class="newsociallogins" style="text-align: center;"><div style="clear:both;"></div></div>');
						if (loginForm.find('input').length > 0)
						loginForm.prepend("<h3 style='text-align:center;'><?php _e('OR'); ?></h3>");
						loginForm.prepend(socialLogins);
						socialLogins = loginForm.find('.newsociallogins');
					}
					if (!window.fb_added) {
						socialLogins.prepend('<?php echo addslashes(preg_replace('/^\s+|\n|\r|\s+$/m', '', self::sign_button())); ?>');
						window.fb_added = true;
					}
				}(jQuery));
			});
		</script>
		<?php
			$param = $this->option;
			ob_start();
			include( 'css/style.php' );
			$style = ob_get_contents();
			ob_end_clean();	
			wp_enqueue_style( $this->slug. '-icon', $this->plugin_url  . 'asset/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style( $this->slug.'-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
			wp_add_inline_style( $this->slug.'-style',$style );	
		}
		
		function get_user_access_token($id) {			
			return get_user_meta($id, 'wow_user_access_token', true);
		}
		
		
		// Is the current user connected the Facebook profile?
		function is_user_connected() {
			
			global $wpdb;
			$current_user = wp_get_current_user();
			$ID           = $wpdb->get_var($wpdb->prepare('
			SELECT identifier FROM ' . $wpdb->prefix . 'wow_social_users WHERE type = "fb" AND ID = "%d"
			', $current_user->ID));
			if ($ID === NULL) return false;
			
			return $ID;
		}
		
		
		function connect_field() {
			
			global $new_is_social_header;
			
			//if(new_fb_is_user_connected()) return;
			if ($new_is_social_header === NULL) {
			?>
			<h3>Social connect</h3>
			<?php
				$new_is_social_header = true;
			}
		?>
		<table class="form-table">
			<tbody>
				<tr>	
					<th> 
					</th>	
					<td>
						<?php
							if (self::is_user_connected()) {
								echo self::unlink_button();
								} else {
								echo self::link_button();
							}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
			$param = $this->option;
			ob_start();
			include( 'css/style.php' );
			$style = ob_get_contents();
			ob_end_clean();	
			wp_enqueue_style( $this->slug. '-icon', $this->plugin_url . 'asset/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style( $this->slug.'-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
			wp_add_inline_style( $this->slug.'-style',$style );		
			
		}
		
		
		
		function enable_login_sales(){
			$options = $this->option;
			if (!empty($options['admin_bar'])){
				show_admin_bar( false );
			}
			if (!empty($options['fb_enable_woocommerce'])){		
				add_action( 'woocommerce_login_form', 'login_for_sales' );
			}	
			if (!empty($options['fb_enable_edd_checkout'])){
				add_action('edd_purchase_form_before_register_login', 'login_for_sales', 10, 0);
			}
			if (!empty($options['fb_enable_edd_login_shortcode'])){
				add_action('edd_login_fields_after', 'login_for_sales', 10, 0);
			}
			if (!empty($options['fb_enable_edd_register_shortcode'])){
				add_action('edd_register_form_fields_after', 'login_for_sales', 10, 0);
			}
		}
		
		function login_for_sales() {
			if( !is_user_logged_in() ) {
				echo '<p>'.do_shortcode( "[Wow-Facebook-Login]" ).'</p>';
			}
		}
		
		
		
		
		
		
		
		
		
		
	}
?>