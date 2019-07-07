<?php
	/**
		* Facebook
		*
		* @package     
		* @subpackage  Integration
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	
	global $wp, $wpdb;
	if (isset($_GET['action']) && $_GET['action'] == 'unlink') {
		$user_info = wp_get_current_user();
		if ($user_info->ID) {
			$wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wow_social_users
			WHERE ID = %d
			AND type = \'fb\'', $user_info->ID));
			set_site_transient($user_info->ID . '_wow_admin_notice', 'Your Facebook profile is successfully unlinked from your account.', 3600);
		}
		self::redirect();
	}
	
	if (is_user_logged_in() && self::is_user_connected()) {
		self::redirect();
		exit;
	}
	
	if (!class_exists('Facebook')) {
		require(dirname(__FILE__) . '/Facebook/autoload.php');
	}
	
	$settings = $this->option;
	
	$callBackUrl = self::callBackUrl();		
	$callback = $callBackUrl . 'loginFacebook=1';
	
	$fb = new Facebook\Facebook(array(
	'app_id'                  => $settings['fb_appid'],
	'app_secret'              => $settings['fb_secret'],	
	// 'persistent_data_handler' => new Facebook\PersistentData\FacebookWordPressPersistentDataHandler(self::wow_uniqid())
	));
	
	if (isset($_REQUEST['code'])) {
		$helper = $fb->getRedirectLoginHelper();
		if (isset($_GET['state'])) {
			$helper->getPersistentDataHandler()->set('state', $_GET['state']);
		}
		
		$_SESSION['FBRLH_state'] = $_REQUEST['state'];
		
		try {
			$accessToken = $helper->getAccessToken($callback);
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		if (!isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
				} else {
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
			exit;
		}
		
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		
		if (!$accessToken->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
				} catch (Facebook\Exceptions\FacebookSDKException $e) {
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			}
		}
		
		$response     = $fb->get('/me?fields=id,name,email,first_name,last_name,link', $accessToken);
		$user_profile = $response->getGraphUser();
		
		$ID = $wpdb->get_var($wpdb->prepare('
		SELECT ID FROM ' . $wpdb->prefix . 'wow_social_users WHERE type = "fb" AND identifier = "%d"
		', $user_profile['id']));
		if (!get_user_by('id', $ID)) {
			$wpdb->query($wpdb->prepare('
			DELETE FROM ' . $wpdb->prefix . 'wow_social_users WHERE ID = "%d"
			', $ID));
			$ID = null;
		}
		if (!is_user_logged_in()) {
			if ($ID == NULL) { // Register
				
				if (!isset($user_profile['email']))
				$user_profile['email'] = $user_profile['id'] . '@facebook.com';
				$ID = email_exists($user_profile['email']);
				
				if ($ID === false) { // Real register					
					// require_once(ABSPATH . WPINC . '/registration.php');
					$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
					if (!isset($settings['fb_user_prefix'])) $settings['fb_user_prefix'] = 'wow_fb_';
					
					$username             = strtolower($user_profile['first_name'] . $user_profile['last_name']);
					$sanitized_user_login = sanitize_user($settings['fb_user_prefix'] . $username);
					if (!validate_username($sanitized_user_login)) {
						$sanitized_user_login = sanitize_user('facebook' . $user_profile['id']);
					}
					$defaul_user_name = $sanitized_user_login;
					$i                = 1;
					while (username_exists($sanitized_user_login)) {
						$sanitized_user_login = $defaul_user_name . $i;
						$i++;
					}
					$ID = wp_create_user($sanitized_user_login, $random_password, $user_profile['email']);
					if (!is_wp_error($ID)) {
						if(!empty($settings['fb_user_notification']) || !empty($settings['fb_admin_notification'])){
							if(!empty($settings['fb_user_notification']) && !empty($settings['fb_admin_notification'])){
								wp_new_user_notification($ID, null,'both');
							}
							elseif(!empty($settings['fb_user_notification'])){
								wp_new_user_notification($ID, null,'user');
							}
							elseif(!empty($settings['fb_admin_notification'])){
								wp_new_user_notification($ID, null,'admin');
							}							
						}
						
						$user_info = get_userdata($ID);
						wp_update_user(array(
						'ID'           => $ID,
						'display_name' => $user_profile['name'],
						'first_name'   => $user_profile['first_name'],
						'last_name'    => $user_profile['last_name'],
						'user_email' => $user_profile['email'],
						));
						
						} else {
						return;
					}
				}
				if ($ID) {
					$wpdb->insert($wpdb->prefix . 'wow_social_users', array(
					'ID' => $ID,
					'type' => 'fb',
					'identifier' => $user_profile['id'],
					'first_name' => $user_profile['first_name'],
					'last_name' => $user_profile['last_name'],
					'email' => $user_profile['email'],
					'link' => $user_profile['link'],
					) , array(
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
					));
				}
				
				
				
			}
			if ($ID) { // Login
				
				
				$secure_cookie = is_ssl();
				$secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
				global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie
				
				$auth_secure_cookie = $secure_cookie;
				wp_set_auth_cookie($ID, true, $secure_cookie);
				$user_info = get_userdata($ID);
				update_user_meta($ID, 'fb_profile_picture', 'https://graph.facebook.com/' . $user_profile['id'] . '/picture?type=large');
				
				$creds = array (
				'user_login' => $user_info->user_login,
				'user_password' => $user_info->user_pass,
				'remember' => true
				);
				
				wp_signon( $creds, $secure_cookie );
				
				// do_action('wp_login', $user_info->user_login, $user_info);
				
				update_user_meta($ID, 'fb_user_access_token', $accessToken);
				// do_action('nextend_fb_user_logged_in', $ID, $user_profile, $fb);
				
			}
			
			} else {
			
			$current_user = wp_get_current_user();
			if ($current_user->ID == $ID) {
				
				// It was a simple login
				
				} elseif ($ID === NULL) { // Let's connect the accout to the current user!
				
				$wpdb->insert($wpdb->prefix . 'wow_social_users', array(
				'ID'         => $current_user->ID,
				'type'       => 'fb',
				'identifier' => $user_profile['id'],
				'first_name' => $user_profile['first_name'],
				'last_name' => $user_profile['last_name'],
				'email' => $user_profile['email'],
				'link' => $user_profile['link'],
				), array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				));
				update_user_meta($current_user->ID, 'wow_user_access_token', (string) $accessToken);
				// do_action('nextend_fb_user_account_linked', $ID, $user_profile, $fb);
				$user_info = wp_get_current_user();
				set_site_transient($user_info->ID . '_wow_admin_notice', 'Your Facebook profile is successfully linked with your account. Now you can sign in with Facebook easily.', 3600);
				} else {
				$user_info = wp_get_current_user();
				set_site_transient($user_info->ID . '_wow_admin_notice', 'This Facebook profile is already linked with other account. Linking process failed!', 3600);
			}
		}
		
		self::redirect();
		} 
	else {		
		$encoded_url = isset( $_GET['redirect'] ) ? $_GET['redirect'] : '';		
		set_transient( 'wow_facebook_login', $encoded_url );		
		$helper = $fb->getRedirectLoginHelper();		
		$permissions = array(
		'email',
		'public_profile'
		);
		
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);		
		header('Location: ' . $loginUrl);
		exit;
	}
	
?>