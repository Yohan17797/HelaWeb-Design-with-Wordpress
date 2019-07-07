<?php
/*
 * Plugin Name: TotalPoll Pro
 * Plugin URI: http://totalpoll.com
 * Description: TotalPoll is a powerful WordPress plugin that lets you create and integrate polls easily. It provides several options and features to enable you have full control over the polls, and has been made very easier for you to use.
 * Version: 3.1.1
 * Author: MisqTech
 * Author URI: http://misqtech.com
 * Text Domain: totalpoll
 * Domain Path: languages
 * 
 * @package TotalPoll
 * @category Core
 * @author MisqTech
 * @version 3.0.0
 */

if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TotalPoll' ) ) :

	/**
	 * Core class.
	 *
	 * @author Misqtech
	 * @since  2.0.0
	 */
	class TotalPoll {

		/**
		 * @var object Singleton container.
		 * @since 2.0.0
		 */
		private static $instance = false;

		/**
		 * @var array Classes map.
		 * @since 3.0.0
		 */
		public static $classes = array(
			// ---------------------------------------------------------------------
			// Name                      | Class                | File
			// ---------------------------------------------------------------------
			'poll'             => array( 'class' => 'TP_Poll', 'file' => 'includes/classes/poll.php' ),
			'fields'           => array( 'class' => 'TP_Fields', 'file' => 'includes/classes/fields.php' ),
			'limitations'      => array( 'class' => 'TP_Limitations', 'file' => 'includes/classes/limitations.php' ),
			'meta-pageable'    => array( 'class' => 'TP_Meta_Pageable', 'file' => 'includes/classes/meta-pageable.php' ),
			'request'          => array( 'class' => 'TP_Request', 'file' => 'includes/classes/request.php' ),
			'field'            => array( 'class' => 'TP_Field', 'file' => 'includes/classes/field.php' ),
			'html'             => array( 'class' => 'TP_HTML', 'file' => 'includes/classes/html.php' ),
			'helpers'          => array( 'class' => 'TP_Helpers', 'file' => 'includes/classes/helpers.php' ),
			'validations'      => array( 'class' => 'TP_Validations', 'file' => 'includes/classes/validations.php' ),
			'extension'        => array( 'class' => 'TP_Extension', 'file' => 'includes/classes/extension.php' ),
			'template'         => array( 'class' => 'TP_Template', 'file' => 'includes/classes/template.php' ),
			'post-types'       => array( 'class' => 'TP_Post_Types', 'file' => 'includes/classes/post-types.php' ),
			// ---------------------------------------------------------------------
			// Admin
			// ---------------------------------------------------------------------
			'admin/bootstrap'  => array( 'class' => 'TP_Admin_Bootstrap', 'file' => 'includes/admin/bootstrap.php' ),
			'admin/ajax'       => array( 'class' => 'TP_Admin_Ajax', 'file' => 'includes/admin/ajax.php' ),
			'admin/editor'     => array( 'class' => 'TP_Admin_Editor', 'file' => 'includes/admin/editor.php' ),
			'admin/installer'  => array( 'class' => 'TP_Admin_Installer', 'file' => 'includes/admin/installer.php' ),
			'admin/extensions' => array( 'class' => 'TP_Admin_Extensions', 'file' => 'includes/admin/extensions.php' ),
			'admin/templates'  => array( 'class' => 'TP_Admin_Templates', 'file' => 'includes/admin/templates.php' ),
			'admin/statistics' => array( 'class' => 'TP_Admin_Statistics', 'file' => 'includes/admin/statistics.php' ),
			'admin/tools'      => array( 'class' => 'TP_Admin_Tools', 'file' => 'includes/admin/tools.php' ),
			'admin/download'   => array( 'class' => 'TP_Admin_Download', 'file' => 'includes/admin/download.php' ),
		);

		/**
		 * @var array Components cached instances.
		 * @since 3.0.0
		 */
		private static $components_instances = array();

		/**
		 * @var array Cached polls instances.
		 * @since 3.0.0
		 */
		private static $cached = array( 'poll' => array(), 'class' => array(), 'instance' => array() );

		/**
		 * Get and lookup component instances.
		 *
		 * @param null       $component Optional. Component to load.
		 * @param array|bool $args      Optional. Array of args, when set to false, returns class name.
		 *
		 * @since 3.0.0
		 *
		 * @return object|TotalPoll
		 */
		static function instance( $component = null, $args = array() ) {

			if ( self::$instance === false ):
				/**
				 * Bootstrap TotalPoll.
				 */
				self::$instance = new TotalPoll();
				self::$instance->constants();
				self::$instance->hooks();

				/**
				 * Admin
				 */
				if ( is_admin() === true && empty( $_REQUEST['totalpoll']['action'] ) ):
					self::instance( 'admin/bootstrap' );
				endif;

				/**
				 * TotalPoll init.
				 *
				 * @since  3.0.0
				 * @action totalpoll/actions/init
				 */
				do_action( 'totalpoll/actions/init' );
			endif;

			// Check whether component exists or not.
			if ( $component !== null && isset( self::$classes[ $component ] ) === true ):

				if ( isset( self::$components_instances[ $component ] ) === true && empty( $args ) === true ): // Load from cached instances.
					return self::$components_instances[ $component ];
				elseif ( $args === false ): // Load without initialization.
					require_once self::$classes[ $component ]['file'];

					return self::$components_instances[ $component ] = self::$classes[ $component ]['class'];
				else: // Load and initialize with arguments.
					require_once self::$classes[ $component ]['file'];
					$reflection = new ReflectionClass( self::$classes[ $component ]['class'] );

					return self::$components_instances[ $component ] = $reflection->newInstanceArgs( (array) $args );
				endif;

			endif;

			// Otherwise, just return the singleton instance.
			return self::$instance;
		}

		/**
		 * Define some useful constants.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function constants() {
			/**
			 * Directory separator.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			if ( defined( 'DS' ) === false ):
				define( 'DS', DIRECTORY_SEPARATOR );
			endif;

			/**
			 * TotalPoll text doamin
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_TD', 'totalpoll' );

			/**
			 * TotalPoll base directory path.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );

			/**
			 * TotalPoll base directory URL.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * TotalPoll current version
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_VERSION', '3.1.1' );

			/**
			 * TotalPoll store URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_WEBSITE', 'http://totalpoll.com' );

			/**
			 * TotalPoll API Endpoint
			 *
			 * @since 3.0.0
			 * @type string
			 */
			define( 'TP_API_ENDPOINT', 'http://store.misqtech.com/api/totalpoll/' );

			/**
			 * TotalPoll store URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_STORE', 'http://store.misqtech.com/products/product/totalpoll' );

			/**
			 * Support center URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_SUPPORT', 'http://support.misqtech.com/' );

			/**
			 * TotalPoll directory name.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );

			/**
			 * TotalPoll root file.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_ROOT', __FILE__ );
		}

		/**
		 * Setup hooks.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function hooks() {
			// Activation
			register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );

			// Deactivation
			register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

			// Widget
			add_action( 'widgets_init', array( self::$instance, 'widgets' ) );

			// Post-type
			self::instance( 'post-types' ); // Initialized with hooks

			// Text domain
			add_action( 'plugins_loaded', array( self::$instance, 'textdomain' ) );

			// Setup poll object
			add_action( 'wp', array( self::$instance, 'post' ) );

			// Requests
			if ( isset( $_REQUEST['totalpoll']['action'] ) === true ):
				// Capture actions
				add_action( 'wp', array( $this, 'request' ), 11 );
				add_action( 'wp_ajax_tp_action', array( $this, 'request' ) );
				add_action( 'wp_ajax_nopriv_tp_action', array( $this, 'request' ) );
			endif;

			// Register shortcode
			add_shortcode( 'total-poll', array( self::$instance, 'shortcode' ) );
			add_shortcode( 'totalpoll', array( self::$instance, 'shortcode' ) );
		}

		/**
		 * On activation hook.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function activation() {
			set_transient( 'totalpoll_redirect_to_about', 1, 10 );

			self::instance( 'post-types' )->poll();
			flush_rewrite_rules();
		}

		/**
		 * On deactivation hook.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function deactivation() {
			flush_rewrite_rules();
		}

		/**
		 * Load text domain.
		 *
		 * @since 3.0.0
		 * @return bool True on success, false on failure
		 */
		public function textdomain() {
			$locale          = get_locale();
			$locale_fallback = substr( $locale, 0, 2 );
			$mofile          = TP_TD . '-' . $locale . '.mo';
			$mofile_fallback = TP_TD . '-' . $locale_fallback . '.mo';

			$loaded = load_textdomain( TP_TD, TP_DIRNAME . "languages/{$mofile}" );
			if ( ! $loaded ):
				$loaded = load_textdomain( TP_TD, TP_PATH . "languages/{$mofile_fallback}" );
			endif;

			return $loaded;
		}

		/**
		 * Register widgets.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function widgets() {
			$base   = ( include_once TP_PATH . 'includes/widgets/base.php' );
			$latest = ( include_once TP_PATH . 'includes/widgets/latest.php' );
			$random = ( include_once TP_PATH . 'includes/widgets/random.php' );

			register_widget( $base );
			register_widget( $latest );
			register_widget( $random );
		}

		/**
		 * Process the request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function request() {
			// Init
			$request = self::instance( 'request' );

			/**
			 * Before processing a request.
			 *
			 * @since  3.0.0
			 * @action totalpoll/actions/request/{$action}
			 */
			do_action( "totalpoll/actions/request", $request );
			do_action( "totalpoll/actions/request/{$_REQUEST['totalpoll']['action']}" );

			// Ajax callback.
			if ( $request->is_ajax === true ):
				$request->ajax();
			endif;
		}

		/**
		 * Attach a hook when the poll is accessed via the permalink.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function post() {
			if ( is_single() === true && get_post_type() === 'poll' ):
				add_filter( 'the_content', array( self::$instance, 'single' ) );
			endif;
		}

		/**
		 * Post callback.
		 *
		 * @param string $content Post content.
		 *
		 * @since 3.0.0
		 * @return string Poll.
		 */
		public function single( $content ) {
			global $post;

			if ( defined( 'TP_ASYNC' ) && TP_ASYNC === true && ! empty( $post ) ):
				return self::async( $post->ID, $content );
			endif;

			return empty( $post ) ? '' : self::poll( $post->ID )->render() . $content;
		}

		/**
		 * Shortcode callback.
		 *
		 * @param array  $attributes Shortcode attributes.
		 * @param string $content    Shortcode content
		 *
		 * @return string Poll.
		 * @since 3.0.0
		 */
		public function shortcode( $attributes, $content = '' ) {
			if ( isset( $attributes['id'] ) === true ):
				$attributes['fragment'] = empty( $attributes['fragment'] ) ? 'vote' : $attributes['fragment'];

				if ( defined( 'TP_ASYNC' ) && TP_ASYNC === true ):
					return self::async( (int) $attributes['id'], $content, $attributes['fragment'] );
				endif;

				return self::poll( (int) $attributes['id'] )->render( $attributes['fragment'] );
			endif;

			return false;
		}

		/**
		 * JS Code for Async loading
		 *
		 * @param        $poll
		 * @param string $fragment
		 * @param string $content
		 *
		 * @return string Async JS
		 * @since 3.0.0
		 */
		private function async( $poll, $content = '', $fragment = 'vote' ) {
			self::poll( $poll )->render( $fragment );

			return sprintf(
				'<div id="%1$s"></div><script type="text/javascript">(window["TotalPollAsync"] = window["TotalPollAsync"] || []).push({id:"%2$d", container:"%1$s"});</script>%3$s',
				'totalpoll-async-' . uniqid(),
				$poll,
				$content
			);
		}

		/**
		 * Poll object lookup.
		 *
		 * @param TP_Poll|int $id Poll ID.
		 *
		 * @since 3.0.0
		 * @return TP_Poll Poll obj
		 */
		public static function poll( $id ) {
			if ( isset( self::$cached['poll'][ $id ] ) === false && is_int( $id ) === true ):

				if ( class_exists( self::$classes['poll']['class'] ) === false ):
					require_once self::$classes['poll']['file'];
				endif;

				$poll                        = self::$classes['poll']['class'];
				self::$cached['poll'][ $id ] = new $poll( $id );

			endif;

			return self::$cached['poll'][ $id ];
		}

		/**
		 * Module (template/extension) lookup.
		 *
		 * @param string            $type Module type
		 * @param string            $name Module name (directory name)
		 * @param bool|false|object $poll Poll object.
		 *
		 * @return bool|object|string Module object, extension class name or false on failure.
		 * @since 3.0.0
		 */
		public static function module( $type, $name, $poll = false ) {

			if ( $type === 'template' || $type === 'extension' ):
				self::instance( $type, false );
			endif;

			$filename = TP_PATH . "{$type}s/$name/{$type}.php";
			if ( ! file_exists( $filename ) ):
				$filename = WP_CONTENT_DIR . "/uploads/totalpoll/{$type}s/{$name}/{$type}.php";
			endif;


			if ( isset( self::$cached['class'][ $filename ] ) === false ):

				if ( file_exists( $filename ) ):
					$module_class                       = ( include_once $filename );
					self::$cached['class'][ $filename ] = $module_class === 1 ? false : $module_class;
				else:
					self::$cached['class'][ $filename ] = false;
				endif;

			endif;

			$module_class = self::$cached['class'][ $filename ];

			return $poll === false || empty( $module_class ) ? $module_class : new $module_class( $poll );
		}

	}

	// Here we go
	TotalPoll::instance();
endif;