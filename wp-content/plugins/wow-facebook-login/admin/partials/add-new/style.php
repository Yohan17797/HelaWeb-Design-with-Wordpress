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
		<h3>Style of button</h3>		
		<div class="wow-admin-col">
			<div class="wow-admin-col-4">
				Padding top (px):<br/>
				<?php echo self::create_option($fb_button_padding_top);?>
				
			</div>
			<div class="wow-admin-col-4">
				Padding right (px):<br/>
				<?php echo self::create_option($fb_button_padding_right);?>
			</div>
			<div class="wow-admin-col-4">
				Padding bottom (px):<br/>
				<?php echo self::create_option($fb_button_padding_bottom);?>
			</div>
			
		</div>
		<div class="wow-admin-col">
			<div class="wow-admin-col-4">
				Padding left (px):<br/>
				<?php echo self::create_option($fb_button_padding_left);?>
			</div>
			<div class="wow-admin-col-4">
				Border (px):<br/>
				<?php echo self::create_option($fb_button_border);?>
			</div>
			<div class="wow-admin-col-4">
				Border radius (px):<br/>
				<?php echo self::create_option($fb_button_border_radius);?>
			</div>
		</div>
		<div class="wow-admin-col">	
			
			<div class="wow-admin-col-4">
				Font size text (px):<br/>
				<?php echo self::create_option($fb_text_size);?>
			</div>
			<div class="wow-admin-col-4">
				Font size icon (px):<br/>
				<?php echo self::create_option($fb_text_icon);?>				
			</div>
			
			<div class="wow-admin-col-4">
				Display icon:<br/>
				<?php echo self::create_option($fb_icon_show);?>				
			</div>
		</div>
		<div class="wow-admin-col">	
			
			<div class="wow-admin-col-4">
				Margin between icon and text (px):<br/>
				<?php echo self::create_option($fb_margin_icon);?>				
			</div>
			
			<div class="wow-admin-col-4">
				Background:<br/>
				<?php echo self::create_option($fb_background);?>
				
			</div>
			<div class="wow-admin-col-4">
				Background hover:<br/>
				<?php echo self::create_option($fb_background_hover);?>
				
			</div>
		</div>
		<div class="wow-admin-col">
			
			<div class="wow-admin-col-4">
				Text color:<br/>
				<?php echo self::create_option($fb_color_text);?>
				
			</div>
			
			<div class="wow-admin-col-4">
				Icon color:<br/>
				<?php echo self::create_option($fb_color_icon);?>			
			</div>
			<div class="wow-admin-col-4">
				Border color:<br/>
				<?php echo self::create_option($fb_color_border);?>
				
			</div>
			
		</div>
	</div>	
</div>

