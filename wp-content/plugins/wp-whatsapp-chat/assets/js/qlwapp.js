(function ($, window, document, undefined) {
  'use strict';

  var defaults = {};

  function Plugin(element, options) {
    this.$qlwapp = $(element);
    //this.settings = $.extend({}, defaults, options);

    this.init(this);
  }

  Plugin.prototype = {
    init: function (plugin) {

      var $qlwapp = this.$qlwapp;

      $qlwapp.on('qlwapp.init', function (e) {
        plugin.mobiledevice = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
      });

      $qlwapp.on('qlwapp.resize', function (e) {
        if ($(this).hasClass('qlwapp-show')) {
          $(this).trigger('qlwapp.toggle');
        }
      });

      $qlwapp.on('qlwapp.init', function (e) {

        if (!plugin.mobiledevice) {
          $qlwapp.addClass('desktop').removeClass('mobile');
        } else {
          $qlwapp.addClass('mobile').removeClass('desktop');
        }

        $qlwapp.addClass('qlwapp-js-ready');
      });


      // Ready!
      // -----------------------------------------------------------------------
      $qlwapp.addClass('qlwapp-js-ready').trigger('qlwapp.init');

      // Height
      // -----------------------------------------------------------------------

      $qlwapp.on('qlwapp.height', function (e) {

        var $container = $(e.delegateTarget),
                $body = $container.find('.qlwapp-body'),
                $carousel = $body.find('.qlwapp-carousel');

        var $header = $container.find('.qlwapp-header'),
                $footer = $container.find('.qlwapp-footer'),
                height = ($(window).innerHeight() - $header.outerHeight() - $footer.outerHeight());

        if (!plugin.mobiledevice) {
          height = ($(window).innerHeight() * 0.666 - $header.outerHeight() - $footer.outerHeight());
        }

        $carousel.css({'max-height': height + 'px'});

      });

      // Toggle
      // -----------------------------------------------------------------------

      $qlwapp.on('qlwapp.toggle', function (e) {

        var $container = $(e.delegateTarget),
                $box = $container.find('.qlwapp-box');

        $container.addClass('qlwapp-transition');

        $box.removeClass('response texting');

        setTimeout(function () {
          $container.toggleClass('qlwapp-show').trigger('qlwapp.height');
        }, 10);

        setTimeout(function () {
          $container.toggleClass('qlwapp-transition');
        }, 300);

      });

      // Click
      // -----------------------------------------------------------------------

      //console.log('toggle');

      $qlwapp.on('click', '[data-action=box], [data-action=close]', function (e) {
        e.preventDefault();

        $(e.delegateTarget).trigger('qlwapp.toggle');

      });

      // Whatsapp
      // -----------------------------------------------------------------------

      $qlwapp.on('click', '[data-action=open]', function (e) {

        var url = 'https://api.whatsapp.com/send';

        if (!plugin.mobiledevice) {
          url = 'https://web.whatsapp.com/send';
        }

        $(this).attr('href', url + '?phone=' + $(this).data('phone') + '&text=' + $(this).data('message'));
        
      });

      // Response
      // -----------------------------------------------------------------------

      $qlwapp.on('click', '[data-action=previous]', function (e) {
        e.preventDefault();

        var $container = $(e.delegateTarget),
                $box = $container.find('.qlwapp-box');//,
        //$user = $box.find('.qlwapp-user');

        $box.addClass('closing');

        setTimeout(function () {
          $box.removeClass('response').removeClass('closing');
          $box.removeClass('texting')
          //$user.empty();
        }, 300);

      });

      $qlwapp.on('click', '[data-action=chat]', function (e) {
        e.preventDefault();

        var $contact = $(this),
                $container = $(e.delegateTarget),
                $box = $container.find('.qlwapp-box'),
                avatar = $contact.find('.qlwapp-avatar img').attr('src'),
                name = $contact.find('.qlwapp-name').text(),
                label = $contact.find('.qlwapp-label').text(),
                //url = $contact.data('url'),
                message = $contact.data('message'),
                phone = $contact.data('phone');

        $box.addClass('response').addClass('opening');

        $container.trigger('qlwapp.height');

        setTimeout(function () {
          $box.removeClass('opening');
        }, 300);

        var $reply = $box.find('.qlwapp-reply'),
                $header = $box.find('.qlwapp-header'),
                $avatar = $header.find('.qlwapp-avatar img'),
                $number = $header.find('.qlwapp-number'),
                $name = $header.find('.qlwapp-name'),
                $label = $header.find('.qlwapp-label'),
                $message = $box.find('.qlwapp-message');


        $reply.data('phone', phone);//.data('message', message);
        $avatar.attr('src', avatar);
        $number.html(phone);
        $name.html(name);
        $label.html(label);
        $message.html(message);

      });

      // Response
      // -----------------------------------------------------------------------

      $qlwapp.on('click', 'textarea', function (e) {
        $qlwapp.off('qlwapp.resize');
      });

      $qlwapp.on('keypress', 'textarea', function (e) {
        if (e.keyCode == 13) {
          $qlwapp.find('.qlwapp-reply').trigger('click');
          setTimeout(function () {
            window.location = $qlwapp.find('.qlwapp-reply').attr('href');
          }, 100);
        }
      });

      $qlwapp.on('keyup', '[data-action=response]', function (e) {
        e.preventDefault();

        var $textarea = $(this).find('textarea'),
                $pre = $(this).find('pre'),
                $reply = $(this).find('.qlwapp-reply'),
                $container = $(e.delegateTarget),
                $box = $container.find('.qlwapp-box'),
                //$user = $box.find('.qlwapp-user'),
                $buttons = $box.find('.qlwapp-buttons');

        $pre.html($textarea.val());

        setTimeout(function () {
          $box.addClass('texting').css({'padding-bottom': $pre.outerHeight()});
          $buttons.addClass('active');
          var message = $textarea.val();
          $reply.data('message', message);

          if (message == '') {
            $box.removeClass('texting');
            $buttons.removeClass('active');
          }

        }, 300);

      });

      $qlwapp.trigger('qlwapp.init');
    },
  };

  $.fn.qlwapp = function (options) {

    var args = arguments;

    if (options === undefined || typeof options === 'object') {
      return this.each(function () {
        if (!$.data(this, 'plugin_qlwapp')) {
          $.data(this, 'plugin_qlwapp', new Plugin(this, options));
        }
      });
    } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
      // Cache the method call to make it possible to return a value
      var returns;
      this.each(function () {
        var instance = $.data(this, 'plugin_qlwapp');
        // Tests that there's already a plugin-instance and checks that the requested public method exists
        if (instance instanceof Plugin && typeof instance[options] === 'function') {
          // Call the method of our plugin instance, and pass it the supplied arguments.
          returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
        }
        // Allow instances to be destroyed via the 'destroy' method
        if (options === 'destroy') {
          //console.log('destroy');
          $.data(this, 'plugin_qlwapp', null);
        }
      });
      // If the earlier cached method gives a value back return the value, otherwise return this to preserve chainability.
      return returns !== undefined ? returns : this;
    }
  }

  function qlwapp_init() {
    $('div#qlwapp').qlwapp();
  }

  qlwapp_init();

  $(window).on('load', function () {
    qlwapp_init();
    //$('div#qlwapp').trigger('qlwapp.toggle');
  });

  $(window).on('click', function (e) {
    if (!$(e.target).closest('#qlwapp.qlwapp-show').length) {
      $('div#qlwapp.qlwapp-show').trigger('qlwapp.toggle');
    }
  });

  $(window).on('resize', function (e) {
    $('div#qlwapp').trigger('qlwapp.resize');
    $('div#qlwapp').trigger('qlwapp.init');
  });

})(jQuery, window, document);