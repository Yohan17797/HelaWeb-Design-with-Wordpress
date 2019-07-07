<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wow">
	<span class="wow-plugin-title"><?php echo $this->name; ?></span>  <span class="wow-plugin-version">(Make Your Website Legendary)</span> 
	
	
	
	<?php echo edd_add_ons_get_feed( ); ?>
	
	
	<?php
		function edd_add_ons_get_feed( ) {
			$cache = get_transient( 'wow_comapny_feed_plugins' );
			$cache = false;
			if ( false === $cache ) {
				$url = 'https://wow-estore.com/list-items/index.php';
				$feed = wp_remote_get( esc_url_raw( $url ), array( 'sslverify' => false ) );
				if ( ! is_wp_error( $feed ) ) {
					if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
						$cache = wp_remote_retrieve_body( $feed );
						set_transient( 'wow_comapny_feed_plugins', $cache, 3600 );				
					}
					} else {
					$cache = '<div class="error"><p>There was an error retrieving the extensions list from the server. Please try again later.</div>';
				}
			}
			return $cache;
		} ?>
		
</div>
