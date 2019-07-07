<?php
if (!class_exists('QLWAPP_Frontend')) {

  class QLWAPP_Frontend {

    protected static $instance;

    function add_js() {
      wp_enqueue_style(QLWAPP_DOMAIN, plugins_url('/assets/css/qlwapp.min.css', QLWAPP_PLUGIN_FILE), null, QLWAPP_PLUGIN_VERSION, 'all');
      wp_enqueue_script(QLWAPP_DOMAIN, plugins_url('/assets/js/qlwapp.min.js', QLWAPP_PLUGIN_FILE), array('jquery'), QLWAPP_PLUGIN_VERSION, true);
    }

    function add_box() {

      global $qlwapp;

      if ($display = apply_filters('qlwapp_box_display', '__return_true')) {

        if (is_file($file = apply_filters('qlwapp_box_template', QLWAPP_PLUGIN_DIR . 'template/box.php'))) {
          include_once $file;
        }
      }
    }

    function add_frontend_css() {
      global $qlwapp;
      ?>
      <style>
        :root { 
          <?php
          foreach ($qlwapp['scheme'] as $key => $value) {
            if ($value != '') {
              printf('--%s-scheme-%s:%s;', QLWAPP_DOMAIN, $key, $value);
            }
          }
          ?>
        }
        <?php if ($qlwapp['scheme']['brand']): ?>
          #qlwapp .qlwapp-toggle,
          #qlwapp .qlwapp-box .qlwapp-header,
          #qlwapp .qlwapp-box .qlwapp-user,
          #qlwapp .qlwapp-box .qlwapp-user:before {
            background-color: var(--qlwapp-scheme-brand);  
          }
        <?php endif; ?>
        <?php if ($qlwapp['scheme']['text']): ?>
          #qlwapp .qlwapp-toggle,
          #qlwapp .qlwapp-toggle .qlwapp-icon,
          #qlwapp .qlwapp-toggle .qlwapp-text,
          #qlwapp .qlwapp-box .qlwapp-header,
          #qlwapp .qlwapp-box .qlwapp-user {
            color: var(--qlwapp-scheme-text);
          }
        <?php endif; ?>
      </style>
      <?php
    }

    function box_display($display) {

      global $qlwapp, $wp_query;

      if (is_customize_preview()) {
        return true;
      }

      if (count($qlwapp['display']['target'])) {

        if (is_front_page() || is_home() || is_search() || is_404()) {
          $display = false;
        }

        if (is_front_page() && in_array('home', $qlwapp['display']['target'])) {
          return true;
        }

        if (is_home() && in_array('blog', $qlwapp['display']['target'])) {
          return true;
        }

        if (is_search() && in_array('search', $qlwapp['display']['target'])) {
          return true;
        }

        if (is_404() && in_array('error', $qlwapp['display']['target'])) {
          return true;
        }
      }

      if (is_archive() && isset($wp_query->queried_object->taxonomy)) {

        if (isset($qlwapp['display'][$wp_query->queried_object->taxonomy]) && count($qlwapp['display'][$wp_query->queried_object->taxonomy])) {

          $display = false;

          if (in_array($wp_query->queried_object->slug, $qlwapp['display'][$wp_query->queried_object->taxonomy])) {
            return true;
          }
        }
      }

      return $display;
    }

    function do_shortcode($atts, $content = null) {

      global $qlwapp;

      extract(shortcode_atts($qlwapp['button'], $atts));

      ob_start();
      ?>
      <div id="qlwapp" class="qlwapp-button qlwapp-js-ready">
        <a class="qlwapp-toggle" data-action="open" data-phone="<?php echo esc_attr($phone); ?>" href="#" target="_blank">
          <?php if ($icon): ?>
            <i class="qlwapp-icon <?php echo esc_attr($icon); ?>"></i>
          <?php endif; ?>
          <?php if ($content): ?>
            <span class="qlwapp-text"><?php echo esc_html($content); ?></span>
          <?php endif; ?>
        </a>
      </div>
      <?php
      return ob_get_clean();
    }

    function init() {
      add_action('wp_enqueue_scripts', array($this, 'add_js'));
      add_action('wp_head', array($this, 'add_frontend_css'), 200);
      add_action('wp_footer', array($this, 'add_box'));
      add_filter('qlwapp_box_display', array($this, 'box_display'));
      add_shortcode('whatsapp', array($this, 'do_shortcode'));
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  QLWAPP_Frontend::instance();
}
