<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	/**
		* Services
		*
		* @package     
		* @subpackage  Settings
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	include ('settings/'.$m_current.'.php');
	
?>

<div class="itembox">
	<div class="item-title">
		<h3>Facebook</h3>
	</div>
	<div class="wow-admin-col">
		<div class="wow-admin-col-6">
			Facebook App ID:<br/>
			<?php echo self::create_option($fb_appid);?>						
		</div>	
		<div class="wow-admin-col-6">
			Facebook App Secret:<br/>
			<?php echo self::create_option($fb_secret);?>						
		</div>			
	</div>	
	
	<div class="wow-admin-col">
				
	</div>
	
	
	<div class="wow-admin-col">
		<div class="wow-admin-col-4">
			New user prefix:<br/>
			<?php echo self::create_option($fb_user_prefix);?>
		</div>
		<div class="wow-admin-col-4">
			Redirect for Login and Register pages:<br/>
			<?php echo self::create_option($fb_redirect);?>
		</div>	
		<div class="wow-admin-col-4">
			Default redirect after login:<br/>
			<?php echo self::create_option($fb_redirect_reg);?>
		</div>	
	</div>
	
	<div class="wow-admin-col">
		<div class="wow-admin-col-4">
			Default button text:<br/>
			<?php echo self::create_option($fb_login_button);?>
		</div>
		<div class="wow-admin-col-4">
			Link account text:<br/>
			<?php echo self::create_option($fb_link_button);?>
		</div>
		<div class="wow-admin-col-4">
			Unlink account text:<br/>
			<?php echo self::create_option($fb_unlink_button);?>
		</div>
	</div>
	
	<div class="wow-admin-col wow-wrap">
		<div class="wow-admin-col-12">

			<input type="checkbox" disabled> <label for="wow_fb_user_notification">User notification</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a><br/>
			<small><em>Send email to user  with Username and pessword</em></small>				
		</div>
		<div class="wow-admin-col-12">
			<?php echo self::create_option($fb_admin_notification);?> <label for="wow_fb_admin_notification">Admin notification</label><br/>
			<small><em>Send email to admin about a new user</em></small>				
		</div>
		<div class="wow-admin-col-12">
			<?php echo self::create_option($fb_integrate_button);?> <label for="wow_fb_integrate_button">Integrate button in Login and Register pages</label>	
		</div>
		<div class="wow-admin-col-12">
			<input type="checkbox" disabled> <label for="wow_admin_bar">Hide admin bar</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a><br/>
			<small><em>Hide admin bar for user.</em></small>
			</div>
		
		<div class="wow-admin-col-12">
			<input type="checkbox" disabled> <label for="wow_fb_enable_woocommerce">Enable in Woocommerce</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
		</div>
		<div class="wow-admin-col-12">
			<input type="checkbox" disabled> <label for="wow_fb_enable_edd_checkout">Enable in EDD's login shortcode</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
		</div>
		<div class="wow-admin-col-12">
			<input type="checkbox" disabled> <label for="wow_fb_enable_edd_login_shortcode">Enablein EDD's register shortcode</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
		</div>
		<div class="wow-admin-col-12">
			<input type="checkbox" disabled> <label for="wow_fb_enable_edd_register_shortcode">Enable in EDD's checkout page</label> <a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
		</div>
	</div>
	
	<div class="wow-admin-col">
		<div class="wow-admin-col-12">
			<b>Note:</b> <p />
			You need to create a new facebook API Applitation to setup facebook login. Please follow the instructions to create new app.
			<br />
			<ul class="wow-futures">
				<li>Go to <a href='https://developers.facebook.com/apps' target='_blank'>https://developers.facebook.com/apps</a>.</li>
				<li>Click on 'Add a New App' button. A popup will open. Then choose website.</li>
				<li>Add the required informations and don't forget to make your app live. This is very important otherwise your app will not work for all users.</li>
				<li>Then Click the "Create App" button and follow the instructions, your new app will be created. </li>
				<li>Copy and Paste "App ID" and "App Secret" here.</li>
				<li>Click 'Add Product' and select 'Facebook login'.</li>
				<li>Enter site url in 'Valid OAuth redirect URIs'. Site url: <input type='text' value='<?php echo site_url(); ?>/index.php?loginFacebook=1' readonly='readonly' /></li>
			</ul>
		</div>
	</div>
	
	
</div>