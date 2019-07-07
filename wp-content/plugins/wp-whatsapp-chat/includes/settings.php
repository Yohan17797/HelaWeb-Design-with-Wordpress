<?php
if (!defined('ABSPATH'))
  exit;

if (!class_exists('QLWAPP_Settings')) {

  class QLWAPP_Settings extends QLWAPP_Options {

    protected static $instance;

    function ajax_dismiss_notice() {

      if ($notice_id = ( isset($_POST['notice_id']) ) ? sanitize_key($_POST['notice_id']) : '') {

        update_user_meta(get_current_user_id(), $notice_id, true);

        wp_send_json($notice_id);
      }

      wp_die();
    }

    function add_action_links($links) {

      $links[] = '<a target="_blank" href="' . QLWAPP_PURCHASE_URL . '">' . esc_html__('Premium', 'wp-whatsapp-chat') . '</a>';

      $links[] = '<a href="' . admin_url('admin.php?page=' . QLWAPP_DOMAIN) . '">' . esc_html__('Settings', 'wp-whatsapp-chat') . '</a>';

      return $links;
    }

    function ajax_get_posts() {

      $data = array(
          array('none', esc_html__('Exclude from all', 'wp-whatsapp-chat'))
      );

      $posts = get_posts(array(
          's' => $_GET['q'],
          'post_type' => $_GET['name'],
          'post_status' => 'publish',
          'ignore_sticky_posts' => 1,
          'posts_per_page' => 10
      ));

      foreach ($posts as $post) {
        $data[] = array($post->post_name, mb_substr($post->post_title, 0, 49));
      }

      wp_send_json($data);

      wp_die();
    }

    function add_menu() {
      add_menu_page(QLWAPP_PLUGIN_NAME, QLWAPP_PLUGIN_NAME, 'manage_options', QLWAPP_DOMAIN, array($this, 'settings_welcome'), 'dashicons-format-chat');
      add_submenu_page(QLWAPP_DOMAIN, __('Welcome', 'wp-whatsapp-chat'), esc_html__('Welcome', 'wp-whatsapp-chat'), 'manage_options', QLWAPP_DOMAIN, array($this, 'settings_welcome'));
      add_submenu_page(QLWAPP_DOMAIN, __('Button', 'wp-whatsapp-chat'), esc_html__('Button', 'wp-whatsapp-chat'), 'edit_posts', QLWAPP_DOMAIN . '_button', array($this, 'settings_button'));
      add_submenu_page(QLWAPP_DOMAIN, __('Box', 'wp-whatsapp-chat'), esc_html__('Box', 'wp-whatsapp-chat'), 'edit_posts', QLWAPP_DOMAIN . '_box', array($this, 'settings_box'));
      add_submenu_page(QLWAPP_DOMAIN, __('Display', 'wp-whatsapp-chat'), esc_html__('Display', 'wp-whatsapp-chat'), 'edit_posts', QLWAPP_DOMAIN . '_display', array($this, 'settings_display'));
      add_submenu_page(QLWAPP_DOMAIN, __('Colors', 'wp-whatsapp-chat'), esc_html__('Colors', 'wp-whatsapp-chat'), 'edit_posts', QLWAPP_DOMAIN . '_colors', array($this, 'settings_colors'));
      add_submenu_page(QLWAPP_DOMAIN, __('Premium', 'wp-whatsapp-chat'), sprintf('<i class="dashicons dashicons-awards"></i> %s', __('Premium', 'wp-whatsapp-chat')), 'edit_posts', QLWAPP_DOMAIN . '_premium', array($this, 'settings_premium'));
    }

    function settings_header() {

      global $submenu;

      if (isset($submenu[QLWAPP_DOMAIN])) {
        $welcome_menu_items = $submenu[QLWAPP_DOMAIN];
      }

      if (is_array($welcome_menu_items)) {
        ?>
        <div class="wrap about-wrap full-width-layout qlwrap">

          <h1><?php esc_html_e('WhatsApp Chat', 'wp-whatsapp-chat'); ?></h1>

          <p class="about-text"><?php esc_html_e('Thanks for using WhatsApp Chat! We will do our best to offer you the best and improved communication experience with your users.', 'wp-whatsapp-chat'); ?></p>

          <p class="about-text">
            <?php printf('<a href="%s" target="_blank">%s</a>', QLWAPP_DEMO_URL, esc_html__('Check out our demo', 'wp-whatsapp-chat')); ?></a>
          </p>

          <?php printf('<a href="%s" target="_blank"><div style="
               background: #006bff url(%s) no-repeat;
               background-position: top center;
               background-size: 130px 130px;
               color: #fff;
               font-size: 14px;
               text-align: center;
               font-weight: 600;
               margin: 5px 0 0;
               padding-top: 120px;
               height: 40px;
               display: inline-block;
               width: 140px;
               position: absolute;
               top: 0;
               right: 0;
               " class="ql-badge">%s</div></a>', 'https://quadlayers.com/?utm_source=qlwapp_admin', plugins_url('/assets/img/quadlayers.jpg', QLWAPP_PLUGIN_FILE), esc_html__('QuadLayers', 'wp-whatsapp-chat')); ?>

        </div>
        <div class="wrap about-wrap full-width-layout qlwrap">
          <h2 class="nav-tab-wrapper">
            <?php
            foreach ($welcome_menu_items as $welcome_menu_item) {
              if (strpos($welcome_menu_item[2], '.php') !== false)
                continue;
              ?>
              <a href="<?php echo admin_url('admin.php?page=' . esc_attr($welcome_menu_item[2])); ?>" class="nav-tab<?php echo (isset($_GET['page']) && $_GET['page'] == $welcome_menu_item[2]) ? ' nav-tab-active' : ''; ?>"><?php echo $welcome_menu_item[0]; ?></a>
              <?php
            }
            ?>
          </h2>
        </div>
        <?php
      }
    }

    function settings_sanitize($settings) {

      if (isset($settings['button']['layout'])) {
        $settings['button']['layout'] = sanitize_html_class($settings['button']['layout']);
      }
      if (isset($settings['button']['position'])) {
        $settings['button']['position'] = sanitize_html_class($settings['button']['position']);
      }
      if (isset($settings['button']['text'])) {
        $settings['button']['text'] = sanitize_text_field($settings['button']['text']);
      }
      if (isset($settings['button']['icon'])) {
        $settings['button']['icon'] = sanitize_html_class($settings['button']['icon']);
      }
      if (isset($settings['box']['header'])) {
        $settings['box']['header'] = wp_kses_post($settings['box']['header']);
      }
      if (isset($settings['box']['footer'])) {
        $settings['box']['footer'] = wp_kses_post($settings['box']['footer']);
      }
      if (isset($settings['contacts'])) {
        if (count($settings['contacts'])) {
          foreach ($settings['contacts'] as $id => $c) {
            $settings['contacts'][$id]['chat'] = (bool) $settings['contacts'][$id]['chat'];
            $settings['contacts'][$id]['avatar'] = sanitize_text_field($settings['contacts'][$id]['avatar']);
            $settings['contacts'][$id]['phone'] = sanitize_text_field($settings['contacts'][$id]['phone']);
            $settings['contacts'][$id]['firstname'] = sanitize_text_field($settings['contacts'][$id]['firstname']);
            $settings['contacts'][$id]['lastname'] = sanitize_text_field($settings['contacts'][$id]['lastname']);
            $settings['contacts'][$id]['label'] = sanitize_text_field($settings['contacts'][$id]['label']);
            $settings['contacts'][$id]['message'] = wp_kses_post($settings['contacts'][$id]['message']);
            //$settings['contacts'][$id]['message'] = stripslashes($settings['contacts'][$id]['message']);
          }
        }
      }

      return $settings;
    }

    function add_settings_register() {
      register_setting(sanitize_key(QLWAPP_DOMAIN . '-group'), sanitize_key(QLWAPP_DOMAIN), array($this, 'settings_sanitize'));
    }

    function settings_welcome() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/welcome.php'); ?>
      </div>
      <?php
    }

    function settings_button() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/button.php'); ?>
      </div>
      <?php
    }

    function settings_box() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/box.php'); ?>
      </div>
      <?php
    }

    function settings_display() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/display.php'); ?>
      </div>
      <?php
    }

    function settings_colors() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/colors.php'); ?>
      </div>
      <?php
    }

    function settings_premium() {

      global $qlwapp;
      ?>
      <?php $this->settings_header(); ?>
      <div class="wrap about-wrap full-width-layout qlwrap">
        <?php include_once('pages/premium.php'); ?>
      </div>
      <?php
    }

    function settings_contacts() {
      ?>
      <table class="form-table widefat" id="qlwapp-contact-form" data-editindex="-1">
        <?php $this->contact_table(); ?>
      </table>
      <?php
    }

    function contact_table($id = 0) {
      global $qlwapp;
      ?>
      <tr>
        <td>
          <div class="upload">
            <img id="cavatar-img" class="qlwapp-avatar" data-src="<?php echo stripslashes(esc_url($qlwapp['contacts'][$id]['avatar'])); ?>" src="<?php echo esc_url($qlwapp['contacts'][$id]['avatar']); ?>" />
            <div>
              <input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][avatar]'); ?>" id="cavatar" value="<?php echo esc_url($qlwapp['contacts'][$id]['avatar']); ?>" />
              <button type="button" class="upload_image_button button"><?php esc_html_e('Upload', 'wp-whatsapp-chat'); ?></button>
              <button type="button" class="remove_image_button button">&times;</button>
            </div>
          </div>
        </td>
        <td>
          <table>
            <tr>
              <td><b><?php esc_html_e('Firstname', 'wp-whatsapp-chat'); ?></b></td>
              <td><input type="text" id="cfirstname" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][firstname]'); ?>" placeholder="<?php echo esc_html($this->defaults['contacts'][0]['firstname']); ?>" value="<?php echo esc_html($qlwapp['contacts'][$id]['firstname']); ?>" /></td>
              <td><b><?php esc_html_e('Lastname', 'wp-whatsapp-chat'); ?></b></td>
              <td><input type="text" id="clastname" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][lastname]'); ?>" placeholder="<?php echo esc_html($this->defaults['contacts'][0]['lastname']); ?>" value="<?php echo esc_html($qlwapp['contacts'][$id]['lastname']); ?>" /></td>
            </tr>
            <tr>
              <td><b><?php esc_html_e('Phone', 'wp-whatsapp-chat'); ?></b></td><td><input type="text" id="cphone" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][phone]'); ?>" placeholder="<?php echo esc_html($this->defaults['contacts'][0]['phone']); ?>" value="<?php echo esc_html($qlwapp['contacts'][$id]['phone']); ?>" /></td>
              <td><b><?php esc_html_e('Label', 'wp-whatsapp-chat'); ?></b></td><td><input type="text" id="clabel" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][label]'); ?>" placeholder="<?php echo esc_html($this->defaults['contacts'][0]['label']); ?>" value="<?php echo esc_html($qlwapp['contacts'][$id]['label']); ?>" /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="qlwapp-premium-field">
        <td><b><?php esc_html_e('Chat', 'wp-whatsapp-chat'); ?></b></td>
        <td>
          <p>
            <label><input type="radio" class="cchat" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][chat]'); ?>" value="1" <?php echo checked(true, (bool) $qlwapp['contacts'][$id]['chat']); ?>/><?php esc_html_e('Enabled', 'wp-whatsapp-chat'); ?></label>
            <label><input type="radio" class="cchat" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][chat]'); ?>" value="0" <?php echo checked(false, (bool) $qlwapp['contacts'][$id]['chat']); ?>/><?php esc_html_e('Disabled', 'wp-whatsapp-chat'); ?></label>
          </p>
        </td>
      </tr>
      <tr class="qlwapp-premium-field">
        <td colspan="2"><b><?php esc_html_e('Message', 'wp-whatsapp-chat'); ?></b></td>
      </tr>
      <tr class="qlwapp-premium-field">
        <td colspan="2"><?php wp_editor($qlwapp['contacts'][$id]['message'], 'cmessage', array('tinymce' => false, 'editor_height' => 150, 'textarea_name' => esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][message]'))); ?></td>
      </tr>
      <?php
    }

    function add_print_media_templates() {
      ?>
      <script type="text/html" id='tmpl-qlwapp-modal-backdrop'>
        <div class="media-modal-backdrop">&nbsp;</div>
      </script>
      <script type="text/html" id='tmpl-qlwapp-modal-window'>
        <div id="qlwapp_modal" class="media-modal wp-core-ui">
          <button type="button" class="media-modal-close close">
            <span class="media-modal-icon">
              <span class="screen-reader-text"><?php esc_html_e('Close media panel'); ?></span>
            </span>
          </button>
          <div class="media-frame mode-select wp-core-ui hide-menu">
            <div class="media-frame-title">
              <h1><?php esc_html_e('WhatsApp Chat', 'wp-whatsapp-chat'); ?><span class="dashicons dashicons-arrow-down"></span></h1>
            </div>
            <div class="media-frame-router">
              <div class="media-router">
                <a href="#" class="media-menu-item active"><?php esc_html_e('Select icon', 'wp-whatsapp-chat'); ?></a>
              </div>
            </div>
            <div class="media-modal-content">
              <div class="media-frame mode-select wp-core-ui">
                <div class="media-frame-menu">
                  <div class="media-menu">
                    <a href="#" class="media-menu-item active"><?php esc_html_e('Featured Image'); ?></a>
                  </div>
                </div>
                <div class="media-frame-content" data-columns="10">
                  <div class="attachments-browser">
                    <input type="hidden" id="qlwapp_icon" value="{{ data.icon }}">
                    <ul tabindex="-1" class="attachments">
                      <?php foreach (explode(',', 'qlwf-chat,qlwf-chat1,qlwf-chat2,qlwf-comments,qlwf-chat3,qlwf-bubble1,qlwf-chat-alt-fill,qlwf-chat-alt-stroke,qlwf-comment-alt2-fill,qlwf-comment-alt2-stroke,qlwf-comment-fill,qlwf-comment-stroke,qlwf-comment,qlwf-comment-alt1-stroke,qlwf-chat4,qlwf-comments1,qlwf-chat5,qlwf-comment1,qlwf-bubble,qlwf-bubbles,qlwf-bubbles2,qlwf-bubble2,qlwf-bubbles3,qlwf-bubbles4,qlwf-whatsapp,qlwf-chat6,qlwf-mode_comment,qlwf-insert_comment,qlwf-chat_bubble_outline,qlwf-chat_bubble,qlwf-bubble_chart,qlwf-comment2,qlwf-chat7,qlwf-commenting-o,qlwf-commenting,qlwf-comments-o,qlwf-comment-o,qlwf-wechat,qlwf-comments2,qlwf-comment3,qlwf-chat8,qlwf-chat-bubble-dots,qlwf-bubbles1,qlwf-bubble3') as $id => $icon) : ?>
                        <li tabindex="0" role="checkbox" aria-label="<?php echo esc_attr($icon); ?>" aria-checked="false" data-id="<?php echo esc_attr($id); ?>" class="attachment save-ready icon _<?php echo esc_attr(str_replace(' ', '_', trim($icon))); ?>">
                          <div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
                            <div class="thumbnail">
                              <i class="<?php echo esc_attr($icon); ?>"></i>
                            </div>
                          </div>
                          <button type="button" class="check" tabindex="-1">
                            <span class="media-modal-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e('Deselect'); ?></span>
                          </button>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
                <div class="media-frame-toolbar">
                  <div class="media-toolbar">
                    <div class="media-toolbar-secondary"></div>
                    <div class="media-toolbar-primary search-form">
                      <button type="button" class="button media-button button-large button-primary media-button-select save"><?php esc_html_e('Save'); ?></button>
                      <button type="button" class="button media-button button-large button-secondary remove"><?php esc_html_e('Remove'); ?></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </script>
      <?php
    }

    function filter_pre_update_option($value, $old_value, $option) {

      global $qlwapp;

      if (isset($value['display'])) {
        unset($qlwapp['display']);
      }

      if (isset($value['contacts'])) {
        unset($qlwapp['contacts']);
      }

      return $this->wp_parse_args($value, $qlwapp);
    }

    function add_notices() {

      if (!get_transient('qlwapp-first-rating') && !get_user_meta(get_current_user_id(), 'qlwapp-user-rating', true)) {
        ?>
        <div id="qlwapp-admin-rating" class="qlwapp-notice notice is-dismissible" data-notice_id="qlwapp-user-rating">
          <div class="notice-container" style="padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: left; align-items: center;">
            <div class="notice-image">
              <img style="border-radius:50%;max-width: 90px;" src="<?php echo plugins_url('/assets/img/logo.jpg', QLWAPP_PLUGIN_FILE); ?>" alt="<?php echo esc_html(QLWAPP_PLUGIN_NAME); ?>>">
            </div>
            <div class="notice-content" style="margin-left: 15px;">
              <p>
                <?php printf(esc_html__('Hello! Thank you for choosing the %s plugin!', 'wp-whatsapp-chat'), QLWAPP_PLUGIN_NAME); ?>
                <br/>
                <?php esc_html_e('Could you please give it a 5-star rating on WordPress? We know its a big favor, but we\'ve worked very much and very hard to release this great product. Your feedback will boost our motivation and help us promote and continue to improve this product.', 'wp-whatsapp-chat'); ?>
              </p>
              <a href="<?php echo esc_url(QLWAPP_REVIEW_URL); ?>" class="button-primary" target="_blank">
                <?php esc_html_e('Yes, of course!', 'wp-whatsapp-chat'); ?>
              </a>
              <a href="<?php echo esc_url(QLWAPP_SUPPORT_URL); ?>" class="button-secondary" target="_blank">
                <?php esc_html_e('Report a bug', 'wp-whatsapp-chat'); ?>
              </a>
            </div>				
          </div>
        </div>
        <script>
          (function ($) {
            $('.qlwapp-notice').on('click', '.notice-dismiss', function (e) {
              e.preventDefault();
              var notice_id = $(e.delegateTarget).data('notice_id');
              $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                  notice_id: notice_id,
                  action: 'qlwapp_dismiss_notice',
                },
                success: function (response) {
                  console.log(response);
                },
              });
            });
          })(jQuery);
        </script>
        <?php
      }
    }

    function add_css() {
      ?>
      <style>
        @font-face {
          font-family: 'qlwf-whatsapp';
          src: url(data:application/x-font-woff;charset=utf-8;base64,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) format('woff');
          font-weight: normal;
          font-style: normal;
        }

        #toplevel_page_qlwapp .dashicons-before.dashicons-format-chat:before {
          font-family: 'qlwf-whatsapp' !important;
          speak: none;
          font-style: normal;
          font-weight: normal;
          font-variant: normal;
          text-transform: none;
          line-height: 1;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          content: "\e900";
          font-size: 17px;
          line-height: 20px;
        }
      </style>
      <?php
    }

    function add_js() {
      if (isset($_GET['page']) && strpos($_GET['page'], QLWAPP_DOMAIN) !== false) {
        wp_enqueue_style('qlwapp-admin', plugins_url('/assets/css/qlwapp-admin.min.css', QLWAPP_PLUGIN_FILE), array('wp-color-picker'), QLWAPP_PLUGIN_VERSION, 'all');
        wp_enqueue_media();
        wp_enqueue_script('qlwapp-select2', plugins_url('/assets/js/select2.min.js', QLWAPP_PLUGIN_FILE), array('jquery'), QLWAPP_PLUGIN_VERSION);
        wp_enqueue_script('qlwapp-admin', plugins_url('/assets/js/qlwapp-admin.min.js', QLWAPP_PLUGIN_FILE), array('jquery', 'wp-color-picker'), QLWAPP_PLUGIN_VERSION);
      }
    }

    function init() {
      add_action('wp_ajax_qlwapp_get_posts', array($this, 'ajax_get_posts'));
      add_action('wp_ajax_qlwapp_dismiss_notice', array($this, 'ajax_dismiss_notice'));
      add_action('admin_enqueue_scripts', array($this, 'add_js'));
      add_action('admin_notices', array($this, 'add_notices'));
      add_action('admin_head', array($this, 'add_css'));
      add_action('admin_menu', array($this, 'add_menu'));
      add_action('admin_init', array($this, 'add_settings_register'));
      add_action('print_media_templates', array($this, 'add_print_media_templates'));
      add_filter('pre_update_option_' . sanitize_key(QLWAPP_DOMAIN), array($this, 'filter_pre_update_option'), -1, 3);
      add_filter('plugin_action_links_' . plugin_basename(QLWAPP_PLUGIN_FILE), array($this, 'add_action_links'));
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->defaults();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  QLWAPP_Settings::instance();
}
