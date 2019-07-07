<?php
/**
 * Astra Portfolio
 *
 * @package Astra Portfolio
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Portfolio_Page' ) ) :

	/**
	 * Astra_Portfolio_Page
	 *
	 * @since 1.0.0
	 */
	class Astra_Portfolio_Page {

		/**
		 * View all actions
		 *
		 * @since 1.0.0
		 * @var array $view_actions
		 */
		static public $view_actions = array();

		/**
		 * Menu page title
		 *
		 * @since 1.0.0
		 * @var array $menu_page_title
		 */
		static public $menu_page_title = 'Settings';

		/**
		 * Plugin slug
		 *
		 * @since 1.0.0
		 * @var array $plugin_slug
		 */
		static public $plugin_slug = 'astra-portfolio';

		/**
		 * Default Menu position
		 *
		 * @since 1.0.0
		 * @var array $default_menu_position
		 */
		static public $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @since 1.0.0
		 * @var array $parent_page_slug
		 */
		static public $parent_page_slug = 'settings';

		/**
		 * Current Slug
		 *
		 * @since 1.0.0
		 * @var array $current_slug
		 */
		static public $current_slug = 'settings';

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
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
		 */
		function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 102 );
			add_action( 'plugin_action_links_' . ASTRA_PORTFOLIO_BASE, array( $this, 'action_links' ) );
			add_filter( 'admin_url', array( $this, 'admin_url' ), 10, 3 );
		}

		/**
		 * Filters the admin area URL.
		 *
		 * @since 1.0.2
		 *
		 * @param string   $url     The complete admin area URL including scheme and path.
		 * @param string   $path    Path relative to the admin area URL. Blank string if no path is specified.
		 * @param int|null $blog_id Site ID, or null for the current site.
		 */
		function admin_url( $url, $path, $blog_id ) {

			if ( 'post-new.php?post_type=astra-portfolio' !== $path ) {
				return $url;
			}

			$url  = get_site_url( $blog_id, 'wp-admin/', 'admin' );
			$path = 'edit.php?post_type=astra-portfolio&page=astra-portfolio-add-new';

			if ( $path && is_string( $path ) ) {
				$url .= ltrim( $path, '/' );
			}

			return $url;
		}

		/**
		 * Admin settings init
		 */
		static public function init_admin_settings() {

			self::$menu_page_title = __( 'Settings', 'astra-portfolio' );

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {

				// Let extensions hook into saving.
				do_action( 'astra_portfolio_settings_scripts' );

				self::save_settings();
			}

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );
			add_action( 'astra_portfolio_menu_settings_action', __CLASS__ . '::settings_page' );
			add_action( 'init', __CLASS__ . '::process_form', 11 );
			add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );

			// Current user can edit?
			if ( current_user_can( 'edit_posts' ) ) {
				add_action( 'admin_menu', __CLASS__ . '::register' );
				add_filter( 'submenu_file', __CLASS__ . '::submenu_file', 999, 2 );
			}
		}

		/**
		 * Sets the active menu item for the builder admin submenu.
		 *
		 * @since 1.0.2
		 *
		 * @param string $submenu_file  Submenu file.
		 * @param string $parent_file   Parent file.
		 * @return string               Submenu file.
		 */
		static public function submenu_file( $submenu_file, $parent_file ) {
			global $pagenow;

			$screen = get_current_screen();

			if ( isset( $_GET['page'] ) && 'astra-portfolio-add-new' == $_GET['page'] ) {
				$submenu_file = 'astra-portfolio-add-new';
			} elseif ( 'post.php' == $pagenow && 'astra-portfolio' == $screen->post_type ) {
				$submenu_file = 'edit.php?post_type=astra-portfolio';
			} elseif ( 'edit-tags.php' == $pagenow && 'astra-portfolio-tags' == $screen->taxonomy ) {
				$submenu_file = 'edit-tags.php?taxonomy=astra-portfolio-tags&post_type=astra-portfolio';
			} elseif ( 'edit-tags.php' == $pagenow && 'astra-portfolio-categories' == $screen->taxonomy ) {
				$submenu_file = 'edit-tags.php?taxonomy=astra-portfolio-categories&post_type=astra-portfolio';
			} elseif ( 'edit-tags.php' == $pagenow && 'astra-portfolio-other-categories' == $screen->taxonomy ) {
				$submenu_file = 'edit-tags.php?taxonomy=astra-portfolio-other-categories&post_type=astra-portfolio';
			}

			return $submenu_file;
		}

		/**
		 * Registers the add new portfolio form admin menu for adding portfolios.
		 *
		 * @since 1.0.2
		 *
		 * @return void
		 */
		static public function register() {
			global $submenu, $_registered_pages;

			$parent        = 'edit.php?post_type=astra-portfolio';
			$tags_url      = 'edit-tags.php?taxonomy=astra-portfolio-tags&post_type=astra-portfolio';
			$cat_url       = 'edit-tags.php?taxonomy=astra-portfolio-categories&post_type=astra-portfolio';
			$other_cat_url = 'edit-tags.php?taxonomy=astra-portfolio-other-categories&post_type=astra-portfolio';
			$add_new_hook  = 'astra-portfolio_page_astra-portfolio-add-new';

			$submenu[ $parent ]     = array();
			$submenu[ $parent ][10] = array( __( 'All Portfolios', 'astra-portfolio' ), 'edit_posts', $parent );
			$submenu[ $parent ][20] = array( __( 'Add New', 'astra-portfolio' ), 'edit_posts', 'astra-portfolio-add-new', '' );
			$submenu[ $parent ][30] = array( __( 'Categories', 'astra-portfolio' ), 'manage_categories', $cat_url );
			$submenu[ $parent ][40] = array( __( 'Other Categories', 'astra-portfolio' ), 'manage_categories', $other_cat_url );
			$submenu[ $parent ][50] = array( __( 'Tags', 'astra-portfolio' ), 'manage_categories', $tags_url );

			add_action( $add_new_hook, __CLASS__ . '::add_new_page' );
			$_registered_pages[ $add_new_hook ] = true;
		}

		/**
		 * Add new page
		 *
		 * @since 1.0.2
		 */
		public static function add_new_page() {
			$types = self::get_portfolio_types();

			require_once ASTRA_PORTFOLIO_DIR . 'includes/add-new-form.php';
		}

		/**
		 * Create the portfolio from add new portfolio form.
		 *
		 * @since 1.0.2
		 *
		 * @return void
		 */
		static public function process_form() {
			$page = isset( $_GET['page'] ) ? $_GET['page'] : null;

			if ( 'astra-portfolio-add-new' != $page ) {
				return;
			}

			if ( ! isset( $_POST['astra-portfolio-add-template'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['astra-portfolio-add-template'], 'astra-portfolio-add-template-nonce' ) ) {
				return;
			}

			$title = sanitize_text_field( $_POST['astra-portfolio-template']['title'] );
			$type  = sanitize_text_field( $_POST['astra-portfolio-template']['type'] );

			// Insert portfolio.
			$post_id = wp_insert_post(
				array(
					'post_title'     => $title,
					'post_type'      => 'astra-portfolio',
					'post_status'    => 'draft',
					'ping_status'    => 'closed',
					'comment_status' => 'closed',
					'meta_input'     => array(
						'astra-portfolio-type' => $type,
					),
				)
			);

			// Redirect to the new portfolio.
			wp_redirect( admin_url( '/post.php?post=' . $post_id . '&action=edit' ) );

			exit;
		}

		/**
		 * Get portfolio type
		 *
		 * @since 1.0.2
		 *
		 * @return array Portfolio types.
		 */
		static public function get_portfolio_types() {

			$all_types = apply_filters(
				'astra_portfolio_add_new_types',
				array(
					array(
						'key'   => 'iframe',
						'label' => __( 'Website', 'astra-portfolio' ),
					),
					array(
						'key'   => 'image',
						'label' => __( 'Image', 'astra-portfolio' ),
					),
					array(
						'key'   => 'video',
						'label' => __( 'Video', 'astra-portfolio' ),
					),
					array(
						'key'   => 'page',
						'label' => __( 'Single Page', 'astra-portfolio' ),
					),
				)
			);

			return $all_types;
		}

		/**
		 * View actions
		 */
		static public function get_view_actions() {

			if ( empty( self::$view_actions ) ) {

				$actions            = array();
				self::$view_actions = apply_filters( 'astra_portfolio_menu_options', $actions );
			}

			return self::$view_actions;
		}

		/**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Make sure we have a valid nonce.
			if ( isset( $_REQUEST['astra-portfolio-import'] ) && wp_verify_nonce( $_REQUEST['astra-portfolio-import'], 'astra-portfolio-importing' ) ) {

				// Stored Settings.
				$stored_data = Astra_Portfolio_Helper::get_page_settings();

				// New settings.
				$new_data = array(
					'par-page'                 => ( isset( $_REQUEST['par-page'] ) ) ? absint( $_REQUEST['par-page'] ) : '',
					'show-portfolio-on'        => ( isset( $_REQUEST['show-portfolio-on'] ) ) ? sanitize_text_field( $_REQUEST['show-portfolio-on'] ) : 'scroll',
					'no-of-columns'            => ( isset( $_REQUEST['no-of-columns'] ) ) ? absint( $_REQUEST['no-of-columns'] ) : '',
					'rewrite'                  => ( isset( $_REQUEST['rewrite'] ) ) ? sanitize_title( $_REQUEST['rewrite'] ) : '',
					'rewrite-tags'             => ( isset( $_REQUEST['rewrite-tags'] ) ) ? sanitize_title( $_REQUEST['rewrite-tags'] ) : '',
					'rewrite-categories'       => ( isset( $_REQUEST['rewrite-categories'] ) ) ? sanitize_title( $_REQUEST['rewrite-categories'] ) : '',
					'rewrite-other-categories' => ( isset( $_REQUEST['rewrite-other-categories'] ) ) ? sanitize_title( $_REQUEST['rewrite-other-categories'] ) : '',
					'other-categories'         => ( isset( $_REQUEST['other-categories'] ) ) ? sanitize_text_field( $_REQUEST['other-categories'] ) : '',
					'categories'               => ( isset( $_REQUEST['categories'] ) ) ? sanitize_text_field( $_REQUEST['categories'] ) : '',
					'show-search'              => ( isset( $_REQUEST['show-search'] ) ) ? sanitize_text_field( $_REQUEST['show-search'] ) : '',
					'no-more-sites-message'    => ( isset( $_REQUEST['no-more-sites-message'] ) ) ? stripcslashes( $_REQUEST['no-more-sites-message'] ) : '',
					'preview-bar-loc'          => ( isset( $_REQUEST['preview-bar-loc'] ) ) ? sanitize_text_field( $_REQUEST['preview-bar-loc'] ) : '',
					'responsive-button'        => ( isset( $_REQUEST['responsive-button'] ) ) ? sanitize_text_field( $_REQUEST['responsive-button'] ) : '',
				);

				// Merge settings.
				$data = wp_parse_args( $new_data, $stored_data );

				// Update settings.
				update_option( 'astra-portfolio-settings', $data );

				// Rewrite permalinks if new rewrite string found.
				if (
					( $stored_data['rewrite'] !== $new_data['rewrite'] ) ||
					( $stored_data['rewrite-tags'] !== $new_data['rewrite-tags'] ) ||
					( $stored_data['rewrite-categories'] !== $new_data['rewrite-categories'] ) ||
					( $stored_data['rewrite-other-categories'] !== $new_data['rewrite-other-categories'] )
				) {
					flush_rewrite_rules();
				}
			}

			// Let extensions hook into saving.
			do_action( 'astra_portfolio_settings_save' );
		}

		/**
		 * Enqueues the needed CSS/JS for Backend.
		 *
		 * @param  string $hook Current hook.
		 *
		 * @since 1.0.0
		 */
		static public function admin_scripts( $hook = '' ) {

			if ( 'astra-portfolio_page_astra-portfolio' === $hook ) {
				wp_register_script( 'astra-portfolio-api', ASTRA_PORTFOLIO_URI . 'assets/js/' . Astra_Portfolio::get_instance()->get_assets_js_path( 'astra-portfolio-api' ), array( 'jquery' ), ASTRA_PORTFOLIO_VER, true );
				wp_enqueue_style( 'astra-portfolio-admin-page', ASTRA_PORTFOLIO_URI . 'assets/css/' . Astra_Portfolio::get_instance()->get_assets_css_path( 'admin-page' ), null, ASTRA_PORTFOLIO_VER, 'all' );
			}

			if ( 'astra-portfolio_page_astra-portfolio-add-new' === $hook ) {
				wp_enqueue_style( 'astra-portfolio-add-new-form', ASTRA_PORTFOLIO_URI . 'assets/css/' . Astra_Portfolio::get_instance()->get_assets_css_path( 'add-new-form' ), null, ASTRA_PORTFOLIO_VER, 'all' );
			}
		}

		/**
		 * Init Nav Menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.0
		 */
		static public function init_nav_menu( $action = '' ) {

			if ( '' !== $action ) {
				self::render_tab_menu( $action );
			}
		}

		/**
		 * Render tab menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.0
		 */
		static public function render_tab_menu( $action = '' ) {
			?>
			<div id="astra-portfolio-menu-page" class="wrap">
				<h1 class='screen-reader-text'> <?php echo esc_html( self::$menu_page_title ); ?> </h1>
				<?php self::render( $action ); ?>
			</div>
			<?php
		}

		/**
		 * Prints HTML content for tabs
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.0
		 */
		static public function render( $action ) {

			?>
			<div class="nav-tab-wrapper">
				<span class='astra-portfolio-menu-page-title'> <?php echo esc_html( self::$menu_page_title ); ?> </span>

				<?php
				$view_actions = self::get_view_actions();

				foreach ( $view_actions as $slug => $data ) {

					if ( ! $data['show'] ) {
						continue;
					}

					$url = self::get_page_url( $slug );

					if ( $slug == self::$parent_page_slug ) {
						update_option( 'astra_parent_page_url', $url );
					}

					$active = ( $slug == $action ) ? 'nav-tab-active' : '';
					?>
						<a class='nav-tab <?php echo esc_attr( $active ); ?>' href='<?php echo esc_url( $url ); ?>'> <?php echo esc_html( $data['label'] ); ?> </a>
				<?php } ?>
			</div><!-- .nav-tab-wrapper -->

			<?php
			// Settings update message.
			if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
				?>
					<div id="message" class="notice notice-success is-dismissive"><p> <?php esc_html_e( 'Settings saved successfully.', 'astra-portfolio' ); ?> </p></div>
				<?php
			}

		}

		/**
		 * Get and return page URL
		 *
		 * @param string $menu_slug Menu name.
		 * @since 1.0.0
		 * @return  string page url
		 */
		static public function get_page_url( $menu_slug ) {

			$parent_page = self::$default_menu_position;

			if ( strpos( $parent_page, '?' ) !== false ) {
				$query_var = '&page=' . self::$plugin_slug;
			} else {
				$query_var = '?page=' . self::$plugin_slug;
			}

			$parent_page_url = admin_url( $parent_page . $query_var );

			$url = $parent_page_url . '&action=' . $menu_slug;

			return esc_url( $url );
		}

		/**
		 * Add main menu
		 *
		 * @since 1.0.0
		 */
		static public function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			add_submenu_page( 'edit.php?post_type=astra-portfolio', $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0.0
		 */
		static public function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;

			$active_tab   = str_replace( '_', '-', $current_slug );
			$current_slug = str_replace( '-', '_', $current_slug );

			?>
			<div class="astra-portfolio-menu-page-wrapper">
				<?php self::init_nav_menu( $active_tab ); ?>
				<?php do_action( 'astra_portfolio_menu_' . esc_attr( $current_slug ) . '_action' ); ?>
			</div>
			<?php
		}

		/**
		 * Include settings_page page
		 *
		 * @since 1.0.0
		 */
		static public function settings_page() {

			$data = Astra_Portfolio_Helper::get_page_settings();

			$status = get_option( 'astra-portfolio-batch-process' );

			require_once ASTRA_PORTFOLIO_DIR . 'includes/settings-page.php';
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array
		 */
		function action_links( $links ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'edit.php?post_type=astra-portfolio&page=astra-portfolio' ) . '" aria-label="' . esc_attr__( 'Settings', 'astra-portfolio' ) . '">' . esc_html__( 'Settings', 'astra-portfolio' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Default portfolio type
		 *
		 * @since 1.3.0
		 *
		 * @return mixed
		 */
		public static function get_default_portfolio_type() {

			$default_type = apply_filters( 'astra_portfolio_default_portfolio_type', '' );

			$types = self::get_portfolio_types();

			foreach ( $types as $key => $type ) {
				if ( $type['key'] === $default_type ) {
					return $default_type;
				}
			}

			return '';
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Portfolio_Page::get_instance();

endif;
