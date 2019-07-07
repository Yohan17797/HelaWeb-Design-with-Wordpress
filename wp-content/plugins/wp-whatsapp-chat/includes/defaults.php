<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('QLWAPP_Options')) {

  class QLWAPP_Options {

    protected static $instance;
    public $defaults;

    function defaults() {
      $this->defaults = array(
          'license' => array(
              'market' => '',
              'key' => '',
              'email' => ''
          ),
          'scheme' => array(
              'brand' => '',
              'text' => '',
              'link' => '',
              'message' => '',
              'label' => '',
              'name' => '',
          ),
          'user' => array(
              'message' => esc_html__('Hello! I\'m testing the WhatsApp Chat plugin @https://quadlayers.com', 'wp-whatsapp-chat')
          ),
          'button' => array(
              'layout' => 'button',
              'position' => 'bottom-right',
              'text' => esc_html__('How can I help you?', 'wp-whatsapp-chat'),
              'icon' => 'qlwapp-whatsapp-icon',
              'phone' => '+441234567890',
              'developer' => 'no',
              'rounded' => 'yes'
          ),
          'box' => array(
              'enable' => 'no',
              'header' => '<h3>Hello!</h3><p>Click one of our representatives below to chat on WhatsApp or send us an email to <a href="mailto:hello@quadlayers.com">hello@quadlayers.com</a></p>',
              'footer' => '<p>Call us to <a href="tel://542215676835">+542215676835</a> from <em><time>0:00hs</time></em> a <em><time>24:00hs</time></em></p>'
          ),
          'chat' => array(
              'emoji' => 'no',
              'response' => esc_html__('Write a response', 'wp-whatsapp-chat'),
          ),
          'contacts' => array(
              0 => array(
                  'chat' => true,
                  'avatar' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000',
                  'phone' => '+441234567890',
                  'firstname' => 'John',
                  'lastname' => 'Doe',
                  'label' => esc_html__('Support', 'wp-whatsapp-chat'),
                  'message' => esc_html__('Hello! I\'m John from the support team.', 'wp-whatsapp-chat')
              ),
          ),
          'display' => array(
              'devices' => 'all',
              'target' => array(),
          ),
      );

      return $this->defaults;
    }

    function wac_options($options) {

      if ($phone = get_option('whatsapp_chat_page')) {
        $options['button']['phone'] = $phone;
      }

      if ($text = get_option('whatsapp_chat_button')) {
        $options['button']['text'] = $text;
      }
      if (get_option('whatsapp_chat_powered_by')) {
        $options['button']['developer'] = 'yes';
      }
      if (false !== get_option('whatsapp_chat_round')) {
        $options['button']['rounded'] = 'no';
      }
      if ($message = get_option('whatsapp_chat_msg')) {
        $options['user']['message'] = $message;
      }
      if ($mobile = get_option('whatsapp_chat_mobile')) {
        $options['display']['devices'] = 'mobile';
      }
      if (get_option('whatsapp_chat_hide_button')) {
        $options['display']['devices'] = 'hide';
      }
      if (get_option('whatsapp_chat_hide_post')) {
        $options['display']['post'] = array('none');
      }
      if (get_option('whatsapp_chat_hide_page')) {
        $options['display']['page'] = array('none');
      }
      if (get_option('whatsapp_chat_hide_product')) {
        $options['display']['product'] = array('none');
      }
      if (get_option('whatsapp_chat_hide_project')) {
        $options['display']['project'] = array('none');
      }

      if (false !== get_option('whatsapp_chat_down')) {
        $vposition = get_option('whatsapp_chat_down') ? 'bottom' : 'middle';
        $hposition = get_option('whatsapp_chat_left_side') ? 'left' : 'right';
        $options['button']['position'] = "{$vposition}-{$hposition}";
      }

      if (get_option('whatsapp_chat_dark')) {
        $options['scheme']['brand'] = '#075E54';
        $options['scheme']['text'] = '#ffffff';
      } elseif (get_option('whatsapp_chat_white')) {
        $options['scheme']['brand'] = '#ffffff';
        $options['scheme']['text'] = '#075E54';
      } elseif (false !== get_option('whatsapp_chat_white')) {
        $options['scheme']['brand'] = '#20B038';
        $options['scheme']['text'] = '#ffffff';
      }

      return $options;
    }

    function options() {

      global $qlwapp;

      $options = get_option(QLWAPP_DOMAIN);

      if (isset($options['box']['enable']) && $options['box']['enable'] == 1) {
        $options['box']['enable'] = 'yes';
      }

      if (isset($options['button']['rounded']) && $options['button']['rounded'] == 1) {
        $options['button']['rounded'] = 'yes';
      }

      if (isset($options['button']['developer']) && $options['button']['developer'] == 1) {
        $options['button']['developer'] = 'yes';
      }

      $qlwapp = $this->wp_parse_args($options, $this->defaults());
    }

    function wp_parse_args(&$a, $b) {
      $a = (array) $a;
      $b = (array) $b;
      $result = $b;
      foreach ($a as $k => &$v) {
        if (is_array($v) && isset($result[$k])) {
          $result[$k] = $this->wp_parse_args($v, $result[$k]);
        } else {
          $result[$k] = $v;
        }
      }
      return $result;
    }

    function init() {
      add_action('init', array($this, 'options'));
      add_filter('default_option_qlwapp', array($this, 'wac_options'), 10);
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

  QLWAPP_Options::instance();
}
