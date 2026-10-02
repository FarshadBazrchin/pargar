(function ($) {
    "use strict";

    var load_html = false;
    var content_html = "";

    $('body').on('click','#btn-export-pdf',function () {
        if ($(this).attr('data-t') === 'browser' ){
            window.print()
        }else {
            if ($(this).attr('data-in') === '1' ) {
               load_html = true;
            }
            $('#bg-popup-export-pdf').addClass('active');
        }
    })

    $('body').on('click', '#bg-popup-export-pdf .popup-btn-close', function () {
        $('#bg-popup-export-pdf').removeClass('active');
    })
    $('body').on('click', '#bg-popup-export-pdf .popup-body .row-input .image-selected .item-selected', function () {
        $(this).parent().find('.item-selected').removeClass('active');
        $(this).addClass('active');
        $('#bg-popup-export-pdf #page_orientation').val( $(this).attr('data-value'))
    })
    $('body').on('input', '#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale-input', function () {
        $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .range-scale').val( $(this).val() )
    })
    $('body').on('click', '#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale .btns-scale .btn-minus', function () {
        let input = $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale-input')
        let range = $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .range-scale');
        let val= input.val();
        val = parseInt(val);
        val = val - 1;
        if (val < 1) {
            input.val(1)
            range.val(1)
        }else {
            input.val(val)
            range.val(val)
        }
    })
    $('body').on('click', '#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale .btns-scale .btn-plus', function () {
        let input = $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale-input')
        let range = $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .range-scale');
        let val= input.val();
        val = parseInt(val);
        val = val + 1;
        if (val > 100) {
            input.val(100)
            range.val(100)
        }else {
            input.val(val)
            range.val(val)
        }
    })
    $('body').on('input', '#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .range-scale', function () {
        let input = $('#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale-input')
        let val= $(this).val();
        val = parseInt(val);
        if (val > 100) {
            input.val(100)
        }else if (val < 1 ) {
            input.val(1)
        }else {
            input.val(val)
        }
    })

    $('body').on('input', '#bg-popup-export-pdf .popup-body .row-input .scale-input .input-scala .custom-scale-input', function() {
        let value = $(this).val();
        if (!/^\d+$/.test(value)) {
            let match = value.match(/\d+/);
            $(this).val(match ? match[0] : '');
        } else {
            value = parseInt(value, 10);
            if (value < 1) {
                $(this).val(1);
            } else if (value > 100) {
                $(this).val(100);
            }
        }
        $('#display-value').text($(this).val());
    });

    $('body').on('click', '#bg-popup-export-pdf .popup-body .btn-print-pdf', function () {

        let html = "";
        if ( load_html ){
            html = content_html;
        }
        let page_scale = $('#bg-popup-export-pdf #page_scale').val();
        let page_size = $('#bg-popup-export-pdf #page_size').val();
        let page_orientation = $('#bg-popup-export-pdf #page_orientation').val();
        let url = $('#bg-popup-export-pdf #url').val();
        let type_page_factor = $('#bg-popup-export-pdf #type_page_factor').val();
        let btn = $(this)
        if ( !btn.hasClass('loading') ) {
            btn.addClass('loading')
            let btn_text = btn.find('.text-a').text();
            btn.find('.text-a').text('کمی صبر کنید..')
            $.ajax({
                method: "POST",
                url: magic_factor_parameter_backend.ajax_url,
                data: {
                    action: "magic_factor_print_pdf",
                    nonce: magic_factor_parameter_backend.ajax_nonce_print_pdf,
                    page_scale: page_scale,
                    page_size: page_size,
                    page_orientation: page_orientation,
                    url: url,
                    type_page_factor: type_page_factor,
                    html: html,
                },
                xhrFields: {
                    responseType: "blob"
                },
                cache: false,
                success: function (response) {
                    btn.find('.text-a').text(btn_text)
                    btn.removeClass('loading')
                    let timestamp = Date.now();

                    var blob = new Blob([response], { type: "application/octet-stream" });
                    var url = window.URL.createObjectURL(blob);

                    var a = document.createElement("a");
                    a.href = url;
                    a.download = timestamp+".pdf";

                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                },
                error: function (e) {
                    btn.find('.text-a').text("خطا در دریافت. مجدد تست کنید.")
                    setTimeout(function () {
                        btn.find('.text-a').text(btn_text)
                    },3000)
                    btn.removeClass('loading')
                }
            });
        }
    })

    window.onload = function() {
        content_html = document.documentElement.outerHTML;
    };

})(jQuery);

