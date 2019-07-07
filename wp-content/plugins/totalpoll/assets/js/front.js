jQuery(function ($) {
    $('.totalpoll-poll-container').addClass('dom-ready');

    /**
     * AJAX
     */
    $(document).delegate('.totalpoll-poll-container [name^="totalpoll[action]"]', 'click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $container = $this.closest('.totalpoll-poll-container');

        var fields = $container.find('form').serializeArray();
        fields.push({name: $this.attr('name'), value: $this.val()});
        fields.push({name: 'action', value: TotalPoll.AJAX_ACTION || 'tp_action'});

        $container.fadeTo('slow', 0.5).css(
            {
                'pointer-events': 'none',
                'min-height': $container.outerHeight()
            }
        );

        $.ajax({
            url: TotalPoll.AJAX || this.action,
            type: 'POST',
            data: fields,
            success: function (content) {
                var $content = $(content).hide();
                var scrollToTop = $container.offset().top;
                $container.after($content).fadeOut(function () {
                    $content.fadeIn().addClass('dom-ready');
                    $(this).remove();
                    $(document.body).animate({scrollTop: scrollToTop - 64});

                    $(document).trigger('totalpoll.after.ajax', [
                        {
                            button: $this,
                            container: $content,
                        },
                        fields
                    ]);
                });
            }
        });

    });

    /**
     * Asynchronous loading
     */
    if (window['TotalPollAsync']) {
        $.each(TotalPollAsync, function (key, poll) {
            var $container = $('#' + poll.container);
            $container.load(
                TotalPoll.AJAX,
                {
                    action: TotalPoll.AJAX_ACTION || 'tp_action',
                    totalpoll: {
                        id: poll.id,
                        action: 'load'
                    }
                }
            );
        });
    }

    /**
     * Maximum selection
     */
    $(document).delegate('.totalpoll-poll-container input[name="totalpoll[choices][]"]', 'change', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $poll = $this.closest('.totalpoll-poll-container');
        var $checkboxes = $poll.find('input[name="totalpoll[choices][]"]');
        var $checked = $checkboxes.filter(':checked');
        var $unchecked = $checkboxes.not(':checked');
        var maxSelection = $poll.attr('data-max-selection');

        if (maxSelection > 1 && $checked.length >= maxSelection) {
            $unchecked.attr('disabled', '');
        } else {
            $checkboxes.removeAttr('disabled');
        }

        $checkboxes.each(function () {
            $(this).closest('[data-tp-choice]').toggleClass('checked', this.checked);
        });

    });


    /**
     * reCaptcha
     */
    window.reCaptchaReady = function () {
        $('[data-tp-captcha]').each(function () {
            grecaptcha.render(this, {
                sitekey: TotalPoll.settings.limitations.captcha.sitekey,
            });
        });
    };

    if (TotalPoll.settings.limitations.captcha.enabled) {
        $(document).on('totalpoll.after.ajax', function () {
            window.reCaptchaReady();
        });
    }

});