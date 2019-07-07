<?php
/**
 * Batch Process for Posts
 *
 * @package Astra Portfolio
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Portfolio_Batch_Process_Posts' ) ) :

	/**
	 * Astra_Portfolio_Batch_Process_Posts
	 *
	 * @since 1.0.0
	 */
	class Astra_Portfolio_Batch_Process_Posts {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Portfolio downloaded
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object Class object.
		 */
		private static $current_portfolio_downloaded = false;

		/**
		 * Process Posts
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access public
		 */
		public $process_posts_single;

		/**
		 * Process Posts
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access public
		 */
		public $process_posts_images;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			require_once ASTRA_PORTFOLIO_DIR . 'classes/batch-processing/class-astra-portfolio-batch-process-posts-single-batch.php';
			$this->process_posts_single = new Astra_Portfolio_Batch_Process_Posts_Single_Batch();

			require_once ASTRA_PORTFOLIO_DIR . 'classes/batch-processing/class-astra-portfolio-batch-process-posts-images-batch.php';
			$this->process_posts_images = new Astra_Portfolio_Batch_Process_Posts_Images_Batch();

			add_action( 'admin_head', array( $this, 'is_process_complete' ) );
		}

		/**
		 * Check Is Batch Process Complete
		 *
		 * @return void
		 */
		function is_process_complete() {
			$process_complete = get_option( 'astra-portfolio-batch-process-all-complete', 'no' );

			if ( 'yes' === $process_complete ) {
				error_log( '========= {COMPLETE} Imported All Portfolios =========' );

				// Mapping.
				delete_option( 'astra-portfolio-batch-api-astra-portfolio-categories' );
				delete_option( 'astra-portfolio-batch-api-astra-portfolio-other-categories' );

				// Taxonomy magging option.
				delete_option( 'astra-portfolio-batch-astra-site-category' );
				delete_option( 'astra-portfolio-batch-astra-site-page-builder' );
				delete_option( 'astra-portfolio-batch-astra-portfolio-categories' );
				delete_option( 'astra-portfolio-batch-astra-portfolio-other-categories' );

				// Update the current status.
				update_option( 'astra-portfolio-batch-process', 'complete' );

				// Update all status.
				delete_option( 'astra-portfolio-batch-process-all-complete' );
			}
		}

		/**
		 * Import
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function import_all() {

			error_log( ' =========== Adding Portfolios to [Image & Portfolio] Queue ===========' );

			// Posts.
			$total_requests = $this->get_total_requests();
			if ( $total_requests ) {
				for ( $page = 1; $page <= $total_requests; $page++ ) {

					$response = Astra_Portfolio_API::get_instance()->get_sites( array( 'page' => $page ) );

					if ( $response['sites_count'] ) {

						$site = ( 1 == $page ) ? 1 : 101;

						foreach ( $response['sites'] as $key => $single_site ) {

							$remote_post_id = ( isset( $single_site['id'] ) ) ? absint( $single_site['id'] ) : '';

							// Debugging.
							$title = ( isset( $single_site['title'] ) ) ? $single_site['title'] : '';

							error_log( ' Added to Queue - {IMAGE} and {PORTFOLIO} to | Page ' . $page . ' site ' . $site . ' [' . $remote_post_id . '] ' . $title );
							$this->process_posts_images->push_to_queue( $single_site );
							$this->process_posts_single->push_to_queue( $single_site );

							$site++;
						}

						update_option( 'astra-portfolio-batch-process-remaining-portfolio-items', $site );
					}
				}
			}

			// Import Images dispatch queue.
			$this->process_posts_images->save()->dispatch();

			// Posts dispatch queue.
			$this->process_posts_single->save()->dispatch();
		}

		/**
		 * Get Total Requests
		 *
		 * @return integer
		 */
		function get_total_requests() {
			$response = Astra_Portfolio_API::get_instance()->get_sites();

			if ( isset( $response['x-wp-totalpages'] ) ) {
				return absint( $response['x-wp-totalpages'] );
			}

			$this->get_total_requests();
		}

		/**
		 * Import Single Image
		 *
		 * @param  array $single_site Single site API response.
		 * @return void
		 */
		public function import_single_image( $single_site = array() ) {

			$remote_post_id = ( isset( $single_site['id'] ) ) ? absint( $single_site['id'] ) : '';
			$title          = ( isset( $single_site['title'] ) ) ? $single_site['title'] : '';

			error_log( ' Importing {IMAGE} - For - [' . $remote_post_id . '] ' . $title );

			$featured_image_url = ( isset( $single_site['featured_image_url'] ) ) ? $single_site['featured_image_url'] : '';

			if ( ! empty( $featured_image_url ) ) {

				$image = array(
					'id'  => rand( 0000, 9999 ),
					'url' => $featured_image_url,
				);

				$downloaded_image = Astra_Portfolio_Import_Image::get_instance()->import( $image );
			}
		}

		/**
		 * Import Single Post
		 *
		 * @param  array $single_site Single site API response.
		 * @return void
		 */
		public function import_single_post( $single_site = array() ) {

			// Log the current item.
			$remaining_items = get_option( 'astra-portfolio-batch-process-remaining-portfolio-items', 0 );
			$remaining_items = absint( $remaining_items ) - 1;
			update_option( 'astra-portfolio-batch-process-remaining-portfolio-items', $remaining_items );

			if ( '1' == $remaining_items ) {
				update_option( 'astra-portfolio-batch-process-all-complete', 'yes' );
			}

			// Process item.
			$remote_post_id = ( isset( $single_site['id'] ) ) ? absint( $single_site['id'] ) : '';
			$title          = ( isset( $single_site['title'] ) ) ? $single_site['title'] : '';

			error_log( ' Importing {PORTFOLIO} - For - [' . $remote_post_id . '] ' . $title . ' | Remaining Items ' . $remaining_items );

			$remote_post_id = ( isset( $single_site['id'] ) ) ? absint( $single_site['id'] ) : '';

			// Exclude post.
			$exclude_ids = (array) get_option( 'astra_portfolio_batch_excluded_sites', array() );

			if ( ! in_array( $remote_post_id, $exclude_ids ) ) {

				// Add to excludes list.
				$exclude_ids[] = $remote_post_id;
				$exclude_ids   = array_unique( $exclude_ids ); // Remove duplicates.

				if ( update_option( 'astra_portfolio_batch_excluded_sites', $exclude_ids ) ) {
					$title              = ( isset( $single_site['title'] ) ) ? $single_site['title'] : '';
					$content            = ( isset( $single_site['content'] ) ) ? $single_site['content'] : '';
					$site_url           = ( isset( $single_site['astra_demo_url'] ) ) ? $single_site['astra_demo_url'] : '';
					$featured_image_url = ( isset( $single_site['featured_image_url'] ) ) ? $single_site['featured_image_url'] : '';

					$api_terms_site_category     = ( isset( $single_site['astra-site-category'] ) ) ? $single_site['astra-site-category'] : array();
					$api_terms_site_page_builder = ( isset( $single_site['astra-site-page-builder'] ) ) ? $single_site['astra-site-page-builder'] : array();

					$site_category     = Astra_Portfolio_Batch_Process_Terms::get_instance()->get_new_taxonomy_ids( 'astra-portfolio-categories', 'astra-site-category', $api_terms_site_category );
					$site_page_builder = Astra_Portfolio_Batch_Process_Terms::get_instance()->get_new_taxonomy_ids( 'astra-portfolio-other-categories', 'astra-site-page-builder', $api_terms_site_page_builder );

					// New post argument array.
					$args = array(
						'post_type'    => 'astra-portfolio',
						'post_status'  => 'draft',
						'post_title'   => $title,
						'post_content' => $content,
						'meta_input'   => array(
							'astra-remote-post-id' => $remote_post_id,
							'astra-site-url'       => $site_url,
							'astra-portfolio-type' => 'iframe',
						),
						'tax_input'    => array(
							'astra-portfolio-categories' => $site_category,
							'astra-portfolio-other-categories' => $site_page_builder,
						),
					);

					// Create new post and get new post ID.
					$post_id = wp_insert_post( $args );

					if ( $post_id && ! is_wp_error( $post_id ) ) {
						// Add post terms.
						wp_set_post_terms( $post_id, $site_category, 'astra-portfolio-categories' );
						wp_set_post_terms( $post_id, $site_page_builder, 'astra-portfolio-other-categories' );

						if ( ! empty( $featured_image_url ) ) {

							$image = array(
								'id'  => rand( 0000, 9999 ),
								'url' => $featured_image_url,
							);

							$downloaded_image = Astra_Portfolio_Import_Image::get_instance()->import( $image );

							// Is image downloaded.
							if ( $downloaded_image['id'] !== $image['id'] ) {
								// And finally assign featured image to post.
								set_post_thumbnail( $post_id, $downloaded_image['id'] );

								// Add portfolio image meta.
								update_post_meta( $post_id, 'astra-portfolio-image-id', $downloaded_image['id'] );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Portfolio_Batch_Process_Posts::get_instance();

endif;
