(function ($) {

  $(document).on('ready', function () {

    $('.qlwapp-select2').select2({allowClear: false, theme: 'default', minimumResultsForSearch: -1});

    $('.qlwapp-select2-ajax').each(function () {

      var $select = $(this),
              name = $(this).data('name');

      $select.select2({
        allowClear: true,
        ajax: {
          url: ajaxurl,
          dataType: 'json',
          delay: 5000,
          data: function (params) {
            return {
              q: params.term,
              name: name,
              per_page: 10,
              action: 'qlwapp_get_posts'
            };
          },
          processResults: function (response) {

            var options = [];

            if (response) {
              $.each(response, function (index, text) {
                options.push({id: text[0], text: text[1]});
              });
            }
            return {
              results: options
            };
          },
          cache: true
        },
        //minimumInputLength: 3
      });

    });

    $('.qlwapp-color-field').wpColorPicker();
  });


  $(document).on('click', '.upload_image_button', function (e) {
    e.preventDefault();

    var send_attachment_bkp = wp.media.editor.send.attachment,
            button = $(this);

    wp.media.editor.send.attachment = function (props, attachment) {
      $(button).parent().prev().attr('src', attachment.url);
      $(button).prev().val(attachment.url);
      wp.media.editor.send.attachment = send_attachment_bkp;
    }

    wp.media.editor.open(button);

    return false;
  });

  $(document).on('click', '.remove_image_button', function (e) {
    e.preventDefault();

    var src = $(this).parent().prev().attr('data-src');

    $(this).parent().prev().attr('src', src);

    $(this).prev().prev().val('');

    return false;
  });

})(jQuery);