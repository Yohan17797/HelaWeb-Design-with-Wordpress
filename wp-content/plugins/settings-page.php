<?php
/**
 * Astra Demo View.
 *
 * @package Astra Portfolio
 */

defined( 'ABSPATH' ) or exit;

?>

<div class="wrap">

	<form id="astra-portfolio-settings" enctype="multipart/form-data" method="post">

		<table class="form-table">
			<?php
				$disabled = ( ! ( 'in-process' !== $status && 'complete' !== $status ) ? 'is-disabled' : '' );
				$_nonce   = wp_create_nonce( 'astra-portfolio-batch-process' );
			?>
				<tr>
					<th scope="row"><?php _e( 'Import Starter Sites', 'astra-portfolio' ); ?></th>
					<td>
						<fieldset>
							<a href="<?php echo esc_url( admin_url() . 'edit.php?post_type=astra-portfolio&page=astra-portfolio&_nonce=' . $_nonce ); ?>"  class="button astra-portfolio <?php echo $disabled; ?>"><?php _e( 'Import', 'astra-portfolio' ); ?></a>
							<p class="description">
								<?php
								/* translators: %1$s is link to reset-import all the demos. */
								printf( __( 'Click on the button above to import all Astra Starter Sites as portfolio items. Imported items will be saved as drafts and need to be published.<br/>Previously Imported/deleted demos will not be re-imported. <a href="%1$s">Click Here</a> to force fetch all the demos once again.', 'astra-portfolio' ), esc_url( admin_url() . 'edit.php?post_type=astra-portfolio&page=astra-portfolio&force-fetch-all-sites&_nonce=' . $_nonce ) );
								?>
							</p>
						</fieldset>
					</td>
				</tr>
			<tr>
				<th scope="row"><?php _e( 'Shortcode', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<input type="text" onfocus="this.select();" readonly="readonly" class="regular-text astra-portfolio-shortcode-text" value="[wp_portfolio]" />
						<p class="description"><?php _e( 'Paste the shortcode on page where you need to display all portfolio items.', 'astra-portfolio' ); ?></p>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Display', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<input type="checkbox" name="categories" value="1" <?php checked( $data['categories'], 1 ); ?> /> <?php _e( 'Enable sorting by categories.', 'astra-portfolio' ); ?>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="checkbox" name="other-categories" value="1" <?php checked( $data['other-categories'], 1 ); ?> /> <?php _e( 'Enable sorting by other categories.', 'astra-portfolio' ); ?>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="checkbox" name="show-search" value="1" <?php checked( $data['show-search'], 1 ); ?> /> <?php _e( 'Display sites search box.', 'astra-portfolio' ); ?>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="checkbox" name="responsive-button" value="1" <?php checked( $data['responsive-button'], 1 ); ?> /> <?php _e( 'Display responsive buttons.', 'astra-portfolio' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Columns', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<input type="number" name="no-of-columns" min="1" max="4" value="<?php echo esc_attr( $data['no-of-columns'] ); ?>" />
							<p class="description"><?php _e( 'Number of items per row. Supports maximum 4 items.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Items Per Page', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<input type="number" name="par-page" min="1" max="100" value="<?php echo esc_attr( $data['par-page'] ); ?>" />
							<p class="description"><?php _e( 'Select the number of items that should load per request.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Show Portfolio On', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<select name="show-portfolio-on">
								<option value="scroll" <?php selected( $data['show-portfolio-on'], 'scroll' ); ?> /><?php _e( 'Scroll', 'astra-portfolio' ); ?></option>
								<option value="click" <?php selected( $data['show-portfolio-on'], 'click' ); ?> /><?php _e( 'Click', 'astra-portfolio' ); ?></option>
							</select>
							<p class="description"><?php _e( 'Select the action on which you wish to display more portfolio items on page.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Rewrite Slug', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<input type="text" name="rewrite" value="<?php echo esc_attr( $data['rewrite'] ); ?>" class="regular-text" />
							<p class="description"><?php _e( 'Rewrite portfolio url slug.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="text" name="rewrite-tags" value="<?php echo esc_attr( $data['rewrite-tags'] ); ?>" class="regular-text" />
							<p class="description"><?php _e( 'Rewrite portfolio tags url slug.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="text" name="rewrite-categories" value="<?php echo esc_attr( $data['rewrite-categories'] ); ?>" class="regular-text" />
							<p class="description"><?php _e( 'Rewrite portfolio categories url slug.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
					<fieldset>
						<label>
							<input type="text" name="rewrite-other-categories" value="<?php echo esc_attr( $data['rewrite-other-categories'] ); ?>" class="regular-text" />
							<p class="description"><?php _e( 'Rewrite portfolio other categories url slug.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Preview Bar', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<select name="preview-bar-loc">
								<option value="top" <?php selected( $data['preview-bar-loc'], 'top' ); ?> /><?php _e( 'Top', 'astra-portfolio' ); ?></option>
								<option value="bottom" <?php selected( $data['preview-bar-loc'], 'bottom' ); ?> /><?php _e( 'Bottom', 'astra-portfolio' ); ?></option>
							</select>
							<p class="description"><?php _e( 'Set portfolio preview bar location.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Call to Action', 'astra-portfolio' ); ?></th>
				<td>
					<fieldset>
						<label>
							<textarea rows="8" cols="80" class="large-text code" name="no-more-sites-message"><?php echo $data['no-more-sites-message']; ?></textarea>
							<p class="description"><?php _e( 'This Call to Action will be displayed at the end of all portfolio items. Shortcode / HTML is supported.', 'astra-portfolio' ); ?></p>
						</label>
					</fieldset>
				</td>
			</tr>
		</table>

		<input type="hidden" name="message" value="saved" />
		<?php wp_nonce_field( 'astra-portfolio-importing', 'astra-portfolio-import' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
