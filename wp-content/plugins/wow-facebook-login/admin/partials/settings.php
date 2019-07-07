<?php if ( ! defined( 'ABSPATH' ) ) exit;		
	$tab_menu = array(
		'network'          => array('Settings', 'fa-facebook'),
		'style'            => array('Style', 'fa-paint-brush'),
		'emails'           => array('Emails', 'fa-envelope'),
		'services'         => array('Services', 'fa-envelope-o'),
	);		
	$param = get_option($this->pref);
?>
<form action="" method="post" name="<?php echo $this->pref;?>" id="<?php echo $this->pref;?>">
	<div class="wowcolom">
		<div id="wow-leftcol">			
			<div class="menu-box wow-admin">
				<ul class="menu-nav">
					<?php 						
						$m_current = (isset($_GET['menu'])) ? sanitize_text_field($_GET['menu']) : 'network';
						foreach ($tab_menu as $menu => $val){
							$m_class = ( $menu == $m_current ) ? 'active' : '';							
							echo '<li><a class="'.$m_class.'" href="?page='.$this->slug.'&tab='.$current.'&menu='.$menu.'"><i class="fa '.$val[1].'"></i> '.$val[0].'</a></li>';
						}						
					?>
				</ul>
				<div class="menu-panels">					
					<?php include_once ('add-new/'.$m_current.'.php'); ?>					
				</div>
			</div>			
		</div>
		<div id="wow-rightcol">
			<div class="wowbox">
				<h3>Publish</h3>
				<div class="wow-admin" style="display: block;">
					<div class="wow-admin-col">						
						<div class="wow-admin-col-12 right">						
							<input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit">
						</div>
					</div>
				</div>
			</div>
			
			<div class="wowbox">
				<h3>Shortcode</h3>
				<div class="wow-admin" style="display: block;">
					<div class="wow-admin-col">						
						<div class="wow-admin-col-12">						
						You can use shortcode for the display of the Login button in the contents of a posts and pages.<p />
						<center><b>[Wow-Facebook-Login]</b></center><p />	
						</div>
					</div>
				</div>
			</div>
			
			<div class="wowbox">
				<center><img src="<?php echo plugin_dir_url( __FILE__ ); ?>thankyou.png" alt=""  /></center>
				<hr/>				
				<div class="wow-admin wow-plugins">
					<p>We will be very grateful if you <a href="https://wordpress.org/plugins/wow-facebook-login/" target="_blank"><b>leave a review about the plugin</b></a>.</p>
					<p>If you have suggestions on how to improve the plugin or create a new plugin, write to us via the <a href="admin.php?page=<?php echo $pluginname;?>&tool=support" target="_blank"><b>support form</b></a></p>					
					<p>We really appreciate your reviews and suggestions for improving the plugin.</p>
					<p>					
					<b><em>Thank you for choosing the plugin from Wow-Company! </em></b></p>
					<em><b>Best Regards</b>,<br/>						
						<a href="https://wow-estore.com/" target="_blank">Wow-Company Team</a><br/>
						Dmytro Lobov<br/>
						<a href="mailto:support@wow-company.com">support@wow-company.com</a>
					</em>
					
				</div>
			</div>
			
		</div>
	</div>			
	<?php wp_nonce_field('wow_'.$this->pref.'_update','wow_'.$this->pref.'_nonce_field'); ?>
</form>	