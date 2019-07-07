<?php
/**
* Social Networks
*
* @package     
* @subpackage  Settings
* @copyright   Copyright (c) 2017, Dmytro Lobov
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

/* Facebook */


$fb_appid = array(
'id'   => 'fb_appid',
	'name' => 'fb_appid',	
	'type' => 'text',
	'val' => isset($param['fb_appid']) ? $param['fb_appid'] : '',	
);

$fb_secret = array(
	'id'   => 'fb_secret',
	'name' => 'fb_secret',	
	'type' => 'text',
	'val' => isset($param['fb_secret']) ? $param['fb_secret'] : '',	
);

$fb_user_prefix = array(
	'id'   => 'fb_user_prefix',
	'name' => 'fb_user_prefix',	
	'type' => 'text',
	'val' => isset($param['fb_user_prefix']) ? $param['fb_user_prefix'] : 'wow_fb_',	
);

$fb_redirect_reg = array(
	'id'   => 'fb_redirect_reg',
	'name' => 'fb_redirect_reg',	
	'type' => 'text',
	'val' => isset($param['fb_redirect_reg']) ? $param['fb_redirect_reg'] : 'auto',	
);

$fb_redirect = array(
	'id'   => 'fb_redirect',
	'name' => 'fb_redirect',	
	'type' => 'text',
	'val' => isset($param['fb_redirect']) ? $param['fb_redirect'] : 'auto',	
);

$fb_redirect_reg = array(
	'id'   => 'fb_redirect_reg',
	'name' => 'fb_redirect_reg',	
	'type' => 'text',
	'val' => isset($param['fb_redirect_reg']) ? $param['fb_redirect_reg'] : 'auto',	
);

$fb_login_button = array(
	'id'   => 'fb_login_button',
	'name' => 'fb_login_button',	
	'type' => 'text',
	'val' => isset($param['fb_login_button']) ? $param['fb_login_button'] : 'Login',	
);

$fb_link_button = array(
	'id'   => 'fb_link_button',
	'name' => 'fb_link_button',	
	'type' => 'text',
	'val' => isset($param['fb_link_button']) ? $param['fb_link_button'] : 'Link account to',	
);

$fb_unlink_button = array(
	'id'   => 'fb_unlink_button',
	'name' => 'fb_unlink_button',	
	'type' => 'text',
	'val' => isset($param['fb_unlink_button']) ? $param['fb_unlink_button'] : 'Unlink account',	
);
$fb_user_notification = array(
	'id'   => 'fb_user_notification',
	'name' => 'fb_user_notification',	
	'type' => 'checkbox',
	'val' => isset($param['fb_user_notification']) ? $param['fb_user_notification'] : 0,	
);

$fb_admin_notification = array(
	'id'   => 'fb_admin_notification',
	'name' => 'fb_admin_notification',	
	'type' => 'checkbox',
	'val' => isset($param['fb_admin_notification']) ? $param['fb_admin_notification'] : 0,	
);

$fb_integrate_button = array(
	'id'   => 'fb_integrate_button',
	'name' => 'fb_integrate_button',	
	'type' => 'checkbox',
	'val' => isset($param['fb_integrate_button']) ? $param['fb_integrate_button'] : 0,	
);




$admin_bar = array(
	'id'   => 'admin_bar',
	'name' => 'admin_bar',	
	'type' => 'checkbox',
	'val' => isset($param['admin_bar']) ? $param['admin_bar'] : 0,	
);

$fb_enable_woocommerce = array(
	'id'   => 'fb_enable_woocommerce',
	'name' => 'fb_enable_woocommerce',	
	'type' => 'checkbox',
	'val' => isset($param['fb_enable_woocommerce']) ? $param['fb_enable_woocommerce'] : 0,	
);

$fb_enable_edd_checkout = array(
	'id'   => 'fb_enable_edd_checkout',
	'name' => 'fb_enable_edd_checkout',	
	'type' => 'checkbox',
	'val' => isset($param['fb_enable_edd_checkout']) ? $param['fb_enable_edd_checkout'] : 0,	
);

$fb_enable_edd_login_shortcode = array(
	'id'   => 'fb_enable_edd_login_shortcode',
	'name' => 'fb_enable_edd_login_shortcode',	
	'type' => 'checkbox',
	'val' => isset($param['fb_enable_edd_login_shortcode']) ? $param['fb_enable_edd_login_shortcode'] : 0,	
);

$fb_enable_edd_register_shortcode = array(
	'id'   => 'fb_enable_edd_register_shortcode',
	'name' => 'fb_enable_edd_register_shortcode',	
	'type' => 'checkbox',
	'val' => isset($param['fb_enable_edd_register_shortcode']) ? $param['fb_enable_edd_register_shortcode'] : 0,	
);






?>