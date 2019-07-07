<?php
/**
 * Plugin Name:       WhatsApp Chat
 * Plugin URI:        https://quadlayers.com/portfolio/wordpress-whatsapp-chat/
 * Description:       WhatsApp Chat allows your visitors to contact you or your team through WhatsApp chat with a single click.
 * Version:           4.2.9
 * Author:            WhatsApp Chat
 * Author URI:        https://quadlayers.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-whatsapp-chat
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('QLWAPP_PLUGIN_NAME')) {
  define('QLWAPP_PLUGIN_NAME', 'WhatsApp Chat');
}
if (!defined('QLWAPP_PLUGIN_VERSION')) {
  define('QLWAPP_PLUGIN_VERSION', '4.2.9');
}
if (!defined('QLWAPP_PLUGIN_FILE')) {
  define('QLWAPP_PLUGIN_FILE', __FILE__);
}
if (!defined('QLWAPP_PLUGIN_DIR')) {
  define('QLWAPP_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR);
}
if (!defined('QLWAPP_DOMAIN')) {
  define('QLWAPP_DOMAIN', 'qlwapp');
}
if (!defined('QLWAPP_WORDPRESS_URL')) {
  define('QLWAPP_WORDPRESS_URL', 'https://wordpress.org/plugins/wp-whatsapp-chat/');
}
if (!defined('QLWAPP_REVIEW_URL')) {
  define('QLWAPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-whatsapp-chat/reviews/?filter=5#new-post');
}
if (!defined('QLWAPP_DEMO_URL')) {
  define('QLWAPP_DEMO_URL', 'https://quadlayers.com/portfolio/wordpress-whatsapp-chat/?utm_source=qlwapp_admin');
}
if (!defined('QLWAPP_PURCHASE_URL')) {
  define('QLWAPP_PURCHASE_URL', QLWAPP_DEMO_URL);
}
if (!defined('QLWAPP_SUPPORT_URL')) {
  define('QLWAPP_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlwapp_admin');
}
if (!defined('QLWAPP_GROUP_URL')) {
  define('QLWAPP_GROUP_URL', 'https://www.facebook.com/groups/quadlayers');
}

if (!class_exists('QLWAPP')) {

  class QLWAPP {

    protected static $instance;

    function includes() {
      include_once('includes/defaults.php');
      include_once('includes/frontend.php');
      include_once('includes/settings.php');
    }

    function languages() {
      load_plugin_textdomain('wp-whatsapp-chat', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    function add_premium_js() {
      if (!class_exists('QLWAPP_PRO')) {
        ?>
        <style>
          .qlwapp-premium-field {
            opacity: 0.5; 
            pointer-events: none;
          }
        </style>
        <?php

      }
    }

    function init() {
      add_action('admin_footer', array($this, 'add_premium_js'));
    }

    public static function do_activation() {
      set_transient('qlwapp-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->languages();
        self::$instance->includes();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  add_action('plugins_loaded', array('QLWAPP', 'instance'));

  register_activation_hook(QLWAPP_PLUGIN_FILE, array('QLWAPP', 'do_activation'));
}
