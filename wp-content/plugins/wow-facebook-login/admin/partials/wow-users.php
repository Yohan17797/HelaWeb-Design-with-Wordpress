<?php if ( ! defined( 'ABSPATH' ) ) exit;
	include( 'users/class-users-table.php' );	
	$customers_table = new Wow_Social_Users($this->slug);
	$customers_table->prepare_items();
?>	 
<div class="wrap">
	<h2>Users</h2>
	
	<form method="post" class="ua-usaers-table">		
		<?php			
			$customers_table->search_box( __( 'Search User', 'users-activity' ), 'users-activity' );
			$customers_table->display();
		?>		
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />	
		<?php wp_nonce_field('ua_export_action','ua_export_field'); ?>
	</form>
</div>
