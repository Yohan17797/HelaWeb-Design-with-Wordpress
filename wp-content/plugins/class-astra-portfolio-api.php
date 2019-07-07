<?php
/**
 * Astra Portfolio API
 *
 * @package Astra Portfolio
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Portfolio_API' ) ) :

	/**
	 * Astra_Portfolio_API
	 *
	 * @since 1.0.0
	 */
	class Astra_Portfolio_API {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since 1.0.0
		 */
		private static $instance;

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
		}

		/**
		 * Setter for $api_url
		 *
		 * @since  1.0.0
		 */
		public static function get_api_endpoint() {
			return 'https://websitedemos.net/wp-json/wp/v2/';
		}

		/**
		 * Setter for $api_url
		 *
		 * @since  1.0.0
		 */
		public static function get_sites_api_url() {
			return apply_filters( 'astra_portfolio_api_url', self::get_api_endpoint() . 'astra-sites/' );
		}

		/**
		 * Get single demo.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $site_id API URL of a demo.
		 * @return array    $astra_demo_data demo data for the demo.
		 */
		public static function get_site( $site_id ) {

			// default values.
			$remote_args = array();
			$defaults    = array(
				'id'                         => '',
				'astra-site-widgets-data'    => '',
				'astra-site-customizer-data' => '',
				'astra-site-options-data'    => '',
				'astra-site-wxr-path'        => '',
				'astra-enabled-extensions'   => '',
				'astra-custom-404'           => '',
				'required-plugins'           => '',
			);

			$api_args = apply_filters(
				'astra_portfolio_api_args',
				array(
					'timeout' => 15,
				)
			);

			// Use this for premium demos.
			$request_params = apply_filters(
				'astra_portfolio_api_params',
				array(
					'purchase_key' => '',
					'site_url'     => '',
				)
			);

			$demo_api_uri = add_query_arg( $request_params, self::get_sites_api_url() . $site_id );

			// API Call.
			$response                = wp_remote_get( $demo_api_uri, $api_args );
			$remote_args['response'] = $response;

			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {

				$result = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( ! isset( $result['code'] ) ) {
					$remote_args['id']                         = $result['id'];
					$remote_args['astra-site-widgets-data']    = json_decode( $result['astra-site-widgets-data'] );
					$remote_args['astra-site-customizer-data'] = $result['astra-site-customizer-data'];
					$remote_args['astra-site-options-data']    = $result['astra-site-options-data'];
					$remote_args['astra-site-wxr-path']        = $result['astra-site-wxr-path'];
					$remote_args['astra-enabled-extensions']   = $result['astra-enabled-extensions'];
					$remote_args['astra-custom-404']           = $result['astra-custom-404'];
					$remote_args['required-plugins']           = $result['required-plugins'];
				}
			}

			// Merge remote demo and defaults.
			return wp_parse_args( $remote_args, $defaults );
		}

		/**
		 * Get Astra portfolios.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $args For selecting the demos (Search terms, pagination etc).
		 * @return array        Astra Portfolio list.
		 */
		public static function get_sites( $args = array() ) {

			$defaults = array(
				'page'         => '1',
				'per_page'     => '100',

				// Use this for premium demos.
				'purchase_key' => '',
				'site_url'     => '',
			);

			$request_params = apply_filters( 'astra_portfolio_api_params', wp_parse_args( $args, $defaults ) );

			$url = add_query_arg( $request_params, self::get_sites_api_url() );

			$astra_demos = array(
				'sites'        => array(),
				'sites_count'  => 0,
				'api_response' => '',
			);

			$api_args = apply_filters(
				'astra_portfolio_api_args',
				array(
					'timeout' => 15,
				)
			);

			$response                    = wp_remote_get( $url, $api_args );
			$astra_demos['api_response'] = $response;

			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {

				$astra_demos['sites_count']     = wp_remote_retrieve_header( $response, 'x-wp-total' );
				$astra_demos['x-wp-total']      = wp_remote_retrieve_header( $response, 'x-wp-total' );
				$astra_demos['x-wp-totalpages'] = wp_remote_retrieve_header( $response, 'x-wp-totalpages' );

				$result = json_decode( wp_remote_retrieve_body( $response ), true );

				// If is array then proceed
				// Else skip it.
				if ( is_array( $result ) ) {

					foreach ( $result as $key => $demo ) {

						if ( ! isset( $demo['id'] ) ) {
							continue;
						}

						$astra_demos['sites'][ $key ]['id']                      = isset( $demo['id'] ) ? esc_attr( $demo['id'] ) : '';
						$astra_demos['sites'][ $key ]['slug']                    = isset( $demo['slug'] ) ? esc_attr( $demo['slug'] ) : '';
						$astra_demos['sites'][ $key ]['astra_demo_type']         = isset( $demo['astra-site-type'] ) ? sanitize_key( $demo['astra-site-type'] ) : '';
						$astra_demos['sites'][ $key ]['title']                   = isset( $demo['title']['rendered'] ) ? esc_attr( $demo['title']['rendered'] ) : '';
						$astra_demos['sites'][ $key ]['featured_image_url']      = isset( $demo['featured-image-url'] ) ? esc_url( $demo['featured-image-url'] ) : '';
						$astra_demos['sites'][ $key ]['demo_api']                = isset( $demo['_links']['self'][0]['href'] ) ? esc_url( $demo['_links']['self'][0]['href'] ) : self::get_sites_api_url( new stdClass() ) . $demo['id'];
						$astra_demos['sites'][ $key ]['astra-site-category']     = isset( $demo['astra-site-category'] ) ? (array) $demo['astra-site-category'] : '';
						$astra_demos['sites'][ $key ]['astra-site-page-builder'] = isset( $demo['astra-site-page-builder'] ) ? (array) $demo['astra-site-page-builder'] : '';

						$site_url = '';
						if ( isset( $demo['astra-site-url'] ) ) {
							$site_url = set_url_scheme( '' . esc_url( $demo['astra-site-url'] ), 'https' );
						}
						$astra_demos['sites'][ $key ]['astra_demo_url'] = $site_url;
					}

					// Free up memory by un setting variables that are not required.
					unset( $result );
					unset( $response );
				}
			}

			return $astra_demos;

		}

		/**
		 * Get Astra Portfolio Categories.
		 *
		 * @since 1.0.0
		 *
		 * @param array $category_slug For selecting the demos (Search terms, pagination etc).
		 * @since array     Category list.
		 */
		public static function get_categories( $category_slug = '' ) {

			if ( empty( $category_slug ) ) {
				return null;
			}

			$url = self::get_api_endpoint() . $category_slug;

			$api_args = apply_filters(
				'astra_portfolio_api_args',
				array(
					'timeout' => 15,
				)
			);

			$response = wp_remote_get( $url, $api_args );

			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
				return json_decode( wp_remote_retrieve_body( $response ), true );
			}

			return $response;

		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Portfolio_API::get_instance();

endif;
