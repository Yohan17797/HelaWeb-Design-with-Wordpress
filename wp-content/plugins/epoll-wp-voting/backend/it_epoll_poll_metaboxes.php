<?php
/**
 * Adds a box to the main column on the Poll edit screens.
 */
function it_epoll_metaboxes() {

		add_meta_box(
			'it_epoll_',
			__( 'Add Poll Options', 'it_epoll' ),
			'it_epoll_metabox_forms',
			'it_epoll_poll',
			'normal',
			'high'
		);
}

add_action( 'add_meta_boxes', 'it_epoll_metaboxes' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function it_epoll_metabox_forms( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'it_epoll_metabox_id', 'it_epoll_metabox_id_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$it_epoll_poll_status = get_post_meta( $post->ID, 'it_epoll_poll_status', true );
	if(get_post_meta( $post->ID, 'it_epoll_poll_option', true )){
		$it_epoll_poll_option = get_post_meta( $post->ID, 'it_epoll_poll_option', true );	
	}
	$it_epoll_poll_option_img = get_post_meta( $post->ID, 'it_epoll_poll_option_img', true );
	$it_epoll_poll_option_cover_img = get_post_meta( $post->ID, 'it_epoll_poll_option_cover_img', true );
	$it_epoll_poll_option_id = get_post_meta( $post->ID, 'it_epoll_poll_option_id', true );
	$it_epoll_poll_style = get_post_meta( $post->ID, 'it_epoll_poll_style', true );
	$it_epoll_poll_ui = get_post_meta( $post->ID, 'it_epoll_poll_ui', true );
	$it_epoll_poll_vote_total_count = (int)get_post_meta($post->ID, 'it_epoll_vote_total_count',true);
	$it_epoll_poll_container_color_primary = get_post_meta( $post->ID, 'it_epoll_poll_container_color_primary', true );

	?>
	<?php if(($post->post_type == 'it_epoll_poll') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
		<div class="it_epoll_short_code">
			<?php _e('Shortcode for this poll is : <code>[IT_EPOLL id="'.$post->ID.'"][/IT_EPOLL]</code> (Insert it anywhere in your post/page and show your poll)','it_epoll');?>
		</div>
	<?php }?>
	<table class="form-table it_epoll_meta_table">
		<tr>
		<td><?php _e('Poll Status','it_epoll');?></td>
		<td>
			<select class="widefat" id="it_epoll_poll_status" name="it_epoll_poll_status" value="" required>
				<option value="live" <?php if($it_epoll_poll_status == 'live') echo esc_attr('selected','it_epoll');?>>Live</option>
				<option value="end" <?php if($it_epoll_poll_status == 'end') echo esc_attr('selected','it_epoll');?>>End</option>
			</select>
		</td>
		<td><?php _e('Enable OTP Voting','it_epoll');?>
			<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>
		<td>	
			<select class="widefat" id="it_epoll_poll_ui" name="it_epoll_poll_ui"  disabled="">
				<option>No</option>
				<option>Yes</option>
			</select>
		</td>
		</tr>
		<tr>
		<td><?php _e('Poll Style','it_epoll');?></td>
		<td>

			<select class="widefat" id="it_epoll_poll_style" name="it_epoll_poll_style" value="" required/>
				<option value="grid" <?php if($it_epoll_poll_style == 'grid') echo esc_attr('selected','it_epoll');?>>Grid</option>
				<option value="list" <?php if($it_epoll_poll_style == 'list') echo esc_attr('selected','it_epoll');?>>List</option>
			</select>
		</td>
		<td><?php _e('Multi Voting ','it_epoll');?>
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>
			<td>
			<select name="it_epoll_multivoting" class="widefat" disabled="">
				<option>No</option>
				<option>Yes</option>
			</select>
		</td>
		</tr>
		<tr>
			<td><?php _e('Container Gradient','it_epoll');?></td>
			<td colspan="1">
				<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_container_color_primary" value=""/>
			</td>
			<td><?php _e('Secondary Color','it_epoll');?>	<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>
			<td colspan="1">
				<div style="display: inline-block !important;">
					<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_container_color_secondary" disabled>
				</div>
			
			</td>
		</tr>
		<tr>
		<td><?php _e('Button & Progress Bar Style','it_epoll');?></td>
		<td colspan="3">
			<table class="widefat">
				<tr>
					<td colspan="4" class="it_epoll_pro_alert_col"><a target="_blank" href="https://infotheme.net/product/epoll-pro/">Get Pro Version to Active This Feature&nbsp;&nbsp;
						<span class="it_epolladmin_pro_badge"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></a>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">
						<input type="radio" name="it_epoll_poll_btn_style" id="it_epoll_poll_btn_style" value="orange" checked/><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/peach.png"><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/peach-bar.png">
					</td>
					<td style="text-align: center;">
						<input type="radio" name="it_epoll_poll_btn_style" id="it_epoll_poll_btn_style" value="orange" disabled/><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/purple.png"><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/purple-bar.png">
					</td>
					<td style="text-align: center;">
						<input type="radio" name="it_epoll_poll_btn_style" id="it_epoll_poll_btn_style" value="orange" disabled/><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/aqua.png"><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/aqua-bar.png">
					</td>
					<td style="text-align: center;">
						<input type="radio" name="it_epoll_poll_btn_style" id="it_epoll_poll_btn_style" value="orange" disabled/><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/blue.png"><br>
						<img src="<?php echo plugins_url('epoll-wp-voting');?>/assets/imgs/buttons/blue-bar.png">
					</td>
				</tr>
				
			</table>
		</td>
	</table>
	
	<table class="form-table" id="it_epoll_append_option_filed">
	<?php if(!empty($it_epoll_poll_option)):
	$i=0;
	foreach($it_epoll_poll_option as $it_epoll_poll_opt):
			$pollKEYIt = (float)$it_epoll_poll_option_id[$i];
			$it_epoll_poll_vote_count = (int)get_post_meta($post->ID, 'it_epoll_vote_count_'.$pollKEYIt,true);
			
			if(!$it_epoll_poll_vote_count){
				$it_epoll_poll_vote_count = 0;
			}
	?>
	<tr class="it_epoll_append_option_filed_tr">
		<td>
			<table class="form-table">
				<tr>
					<td><?php _e('Option Name','it_epoll');?></td>
					<td>
						<input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" value="<?php echo esc_attr($it_epoll_poll_opt,'it_epoll');?>" required/>
					</td>
				</tr>
				<tr>
					<td><?php _e('Option Image','it_epoll');?></td>
					<td><input type="url" class="widefat" id="it_epoll_poll_option_img" name="it_epoll_poll_option_img[]" value="<?php if(!empty($it_epoll_poll_option_img)){ echo esc_attr($it_epoll_poll_option_img[$i],'it_epoll');}?>"/>
						<input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>"/>
					</td>
					<td>
						<input type="button" class="button" id="it_epoll_poll_option_btn" name="it_epoll_poll_option_btn" value="<?php _e('Upload','it_epoll');?>">
					</td>
				</tr>
				<tr>
					<td><?php _e('Option Cover Image','it_epoll');?></td>
					<td><input type="url" class="widefat" id="it_epoll_poll_option_cover_img" name="it_epoll_poll_option_cover_img[]" value="<?php if(!empty($it_epoll_poll_option_cover_img)){ echo esc_attr($it_epoll_poll_option_cover_img[$i],'it_epoll');}?>"/>
					</td>
					<td>
						<input type="button" class="button" id="it_epoll_poll_option_ci_btn" name="it_epoll_poll_option_ci_btn" value="<?php _e('Upload','it_epoll');?>">
					</td>
				</tr>
				<tr>
					<td><?php _e('Edit Vote Count / Result','it_epoll');?> 
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>
					<td><input type="number" class="widefat" id="it_epoll_indi_vote" name="it_epoll_indi_vote[]" value="<?php echo $it_epoll_poll_vote_count;?>" disabled=""/>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove This Option">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
	$i++;
	endforeach;
	endif; ?>
	</table>
	
	<table class="form-table">
		<tr>
			<td><button type="button" name="" class="button it_epoll_add_option_btn" id=""><i class="dashicons-before dashicons-plus-alt"></i> <?php _e('Add Option','it_epoll');?></button></td>
		</tr>
	</table>
	
	<table class="form-table">
		<tr>
			<td class="it_epoll_short_code"><?php _e('Developed & Designed By <a href="http://www.infotheme.in">InfoTheme Inc.</a> | For Customization <a href="https://infotheme.in/#contact">Hire Us Today</a> | <a href="http://infotheme.in/products/plugins/epoll-wp-voting-system/#forum">Support / Live Chat</a> | <a href="http://infotheme.in/products/plugins/epoll-wp-voting-system/#docs">Documentation</a>','it_epoll');?></td>
		</tr>
	</table>
	
	<?php
	}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function it_epoll_save_options( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['it_epoll_metabox_id_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['it_epoll_metabox_id_nonce'], 'it_epoll_metabox_id' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'it_epoll_poll' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	
	// Sanitize user input & Update the meta field in the database.
	
	//Updating Poll status
	if(isset($_POST['it_epoll_poll_status'])){
		$it_epoll_poll_status =  sanitize_text_field($_POST['it_epoll_poll_status']);
		update_post_meta( $post_id, 'it_epoll_poll_status', $it_epoll_poll_status );
	}

	//Updating Poll UI
	if(isset($_POST['it_epoll_poll_ui'])){
		$it_epoll_poll_ui =  sanitize_text_field($_POST['it_epoll_poll_ui']);
		update_post_meta( $post_id, 'it_epoll_poll_ui', $it_epoll_poll_ui );
	}

	//Updating Poll Style
	if(isset($_POST['it_epoll_poll_style'])){
		$it_epoll_poll_style =  sanitize_text_field($_POST['it_epoll_poll_style']);
		update_post_meta( $post_id, 'it_epoll_poll_style', $it_epoll_poll_style );
	}


	//Updating Poll Container Primary Color
	if(isset($_POST['it_epoll_poll_container_color_primary'])){
		$it_epoll_poll_ui =  sanitize_text_field($_POST['it_epoll_poll_container_color_primary']);
		update_post_meta( $post_id, 'it_epoll_poll_container_color_primary', $it_epoll_poll_ui );
	}

	//Updating Poll Container Secondary Color
	
	
	//Update Poll Options Name
	if(isset($_POST['it_epoll_poll_option'])){
		$it_epoll_poll_options = $_POST['it_epoll_poll_option'];
		$it_epoll_poll_option = array();
		foreach($it_epoll_poll_options as $it_epoll_poll_opt_key){
			if($it_epoll_poll_opt_key){
			array_push($it_epoll_poll_option,sanitize_text_field($it_epoll_poll_opt_key));
			}
		}
		update_post_meta( $post_id, 'it_epoll_poll_option', $it_epoll_poll_option );
	}else{
		update_post_meta( $post_id, 'it_epoll_poll_option', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_img', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_cover_img', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_id', array());
	}
	
	//Update Poll Options Image
	if(isset($_POST['it_epoll_poll_option_img'])){
		$it_epoll_poll_option_imgs = $_POST['it_epoll_poll_option_img'];
		$it_epoll_poll_option_img = array();
		foreach($it_epoll_poll_option_imgs as $it_epoll_poll_option_img_key){
			if($it_epoll_poll_option_img_key){
			array_push($it_epoll_poll_option_img,sanitize_text_field($it_epoll_poll_option_img_key));
			}
		}
		update_post_meta( $post_id, 'it_epoll_poll_option_img', $it_epoll_poll_option_img );
	}

	//Update Poll Options Cover
	if(isset($_POST['it_epoll_poll_option_cover_img'])){
		$it_epoll_poll_option_cover_imgs = $_POST['it_epoll_poll_option_cover_img'];
		$it_epoll_poll_option_cover_img = array();
		foreach($it_epoll_poll_option_cover_imgs as $it_epoll_poll_option_c_img_key){
			if($it_epoll_poll_option_c_img_key){
			array_push($it_epoll_poll_option_cover_img,sanitize_text_field($it_epoll_poll_option_c_img_key));
			}
		}
		update_post_meta( $post_id, 'it_epoll_poll_option_cover_img', $it_epoll_poll_option_cover_img );
	}
	
	//Update Poll Options Id
	if(isset($_POST['it_epoll_poll_option_id'])){
		$it_epoll_poll_option_ids = $_POST['it_epoll_poll_option_id'];
		$it_epoll_poll_option_id = array();
		foreach($it_epoll_poll_option_ids as $it_epoll_poll_option_id_key){
			if($it_epoll_poll_option_id_key){
			array_push($it_epoll_poll_option_id,sanitize_text_field($it_epoll_poll_option_id_key));
			}
		}
		update_post_meta( $post_id, 'it_epoll_poll_option_id', $it_epoll_poll_option_id );
	}
}
add_action( 'save_post', 'it_epoll_save_options' );