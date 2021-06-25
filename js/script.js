(function ($) {
    'use strict'

    $(document).ready(function () {
        svg4everybody({})


        $('.header__burger').on('click', function() {
            $(this).toggleClass('active');
            $('.header__menu').toggleClass('active');
            $('.header__subfon').toggleClass('active');
            $('body').toggleClass('lock');
        })
    })
})(jQuery)

;(function($) {
    var request;
    $('.send-ajax').on('submit', function(e) {
        e.preventDefault()
        e.stopPropagation()

        if (request) {
            request.abort();
        }

        var $form = $(this);
        var $inputs = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        $inputs.prop("disabled", true);

        request = $.ajax({
            url: "/smtp/send.php",
            type: "post",
            data: serializedData
        });

        request.done(function (response, textStatus, jqXHR){
            // alert(response.data);
            $(this).trigger('formSendSuccess', response);
        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            $(this).trigger('formSendFailed', errorThrown);
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
        });

        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });
    });
})(jQuery);