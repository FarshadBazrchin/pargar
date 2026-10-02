(function ($) {
    "use strict";
    var mediaUploaderObject;

    $('body').on('click', '#magic-factor.main-setting .menu-setting .list-items .item', function () {
        let tab = $(this).attr('data-tab');
        $('#magic-factor.main-setting .content-setting').removeClass('active');
        $('#magic-factor.main-setting .content-setting[data-tab="' + tab + '"]').addClass('active');
        $(this).parent().find('.item').removeClass('active');
        $(this).addClass("active")
    })

    $('body').on('change', '#magic-factor.main-setting input[type="checkbox"]', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.custom-checkbox').addClass('checked')
        } else {
            $(this).closest('.custom-checkbox').removeClass('checked')
        }
    })

    $('body').on('click', '#magic-factor.main-setting .content-setting section .section-head .btn-save-setting', function () {
        let item = $('#magic-factor.main-setting .content-setting .value-data');
        let data = {}
        let btn = $(this);
        let title_btn = $(this).find('.btn_text');
        if (!btn.hasClass('loading')) {
            btn.addClass('loading')
            let text_btn = title_btn.text();
            title_btn.text('لطفا کمی صبر کنید...');
            for (let i = 0; i < item.length; i++) {
                if ($(item[i]).attr('type') === "text" || $(item[i]).attr('type') === "number" || $(item[i]).attr('type') === "hidden") {
                    data[$(item[i]).attr('name')] = $(item[i]).val();
                } else if ($(item[i]).attr('type') === "checkbox") {
                    if ($(item[i]).is(':checked')) {
                        data[$(item[i]).attr('name')] = "on";
                    } else {
                        data[$(item[i]).attr('name')] = "off";
                    }
                } else if ($(item[i]).hasClass('group-custom-checkbox')) {
                    let list_checkbox = $(item[i]).find('.group-data');
                    let data_checkbox = {}
                    for (let j = 0; j < list_checkbox.length; j++) {
                        if ($(list_checkbox[j]).is(':checked')) {
                            data_checkbox[$(list_checkbox[j]).attr('name')] = "on";
                        } else {
                            data_checkbox[$(list_checkbox[j]).attr('name')] = "off";
                        }
                    }
                    data[$(item[i]).attr('data-name')] = data_checkbox;
                }else {
                    if ( $(item[i]).hasClass('wp-editor-area' ) ){
                        let editor = tinyMCE.get($(item[i]).attr('id'));
                        if (editor) {
                            data[$(item[i]).attr('name')] = editor.getContent();
                        }
                    }else {
                        data[$(item[i]).attr('name')] = $(item[i]).val();
                    }
                }
            }

            $.ajax({
                method: "POST",
                dataType: "JSON",
                url: magic_factor_parameter_backend.ajax_url,
                data: {
                    action: "magic_factor_save_setting",
                    setting: data,
                    nonce: magic_factor_parameter_backend.ajax_nonce_setting,
                },
                cache: false,
                success: function (response) {
                    if (response.success) {
                        title_btn.text('تنظیمات بروز شد');
                        setTimeout(function () {
                            title_btn.text(text_btn);
                        }, 2000)
                    } else {
                        title_btn.text('خطا در بروز رسانی');
                        setTimeout(function () {
                            title_btn.text(text_btn);
                        }, 2000)
                    }
                    btn.removeClass('loading');
                },
                error: function (e) {
                    btn.removeClass("loading");
                    title_btn.text('خطا در بروز رسانی');
                    setTimeout(function () {
                        title_btn.text(text_btn)
                    }, 2000)
                }
            });
        }
    })

    $('body').on('click', '#magic-factor.main-setting .content-setting section .section-head .btn-default-setting', function () {
        let btn = $(this);
        if (!btn.hasClass('loading')) {
            btn.addClass("loading");
            let text_btn = btn.text();
            btn.text('لطفا کمی صبر کنید...');
            $.ajax({
                method: "POST",
                dataType: "JSON",
                url: magic_factor_parameter_backend.ajax_url,
                data: {
                    action: "pargar_default_setting",
                    nonce: magic_factor_parameter_backend.ajax_nonce_setting,
                },
                cache: false,
                success: function (response) {
                    if (response.success) {
                        btn.text('تنظیمات بروز شد');
                        window.location.reload();
                        btn.removeClass("loading");
                    } else {
                        btn.text('خطا در بروز رسانی');
                        setTimeout(function () {
                            btn.text(text_btn);
                        }, 2000)
                        btn.removeClass("loading");
                    }
                },
                error: function (e) {
                    btn.removeClass("loading");
                    btn.text('خطا در بروز رسانی');
                    setTimeout(function () {
                        btn.text(text_btn)
                    }, 2000)
                }
            });
        }
    })

    $('body').on('click', '#magic-factor.main-setting .content-setting section .section-body .item-box .list-template .item-bottom .btn.import-template', function () {
        let file_url =  $(this).attr('data-demo')
        let btn = $(this);
        if ( ! btn.hasClass('loading') ) {
            btn.addClass('loading')
            let btn_text = btn.find('.txt').text();
            btn.find('.txt').text('کمی صبر کنید..')
            $.ajax({
                method: "POST",
                dataType: "JSON",
                url: magic_factor_parameter_backend.ajax_url,
                data: {
                    action: "pargar_import_demo_remote",
                    file_url: file_url,
                    nonce: magic_factor_parameter_backend.ajax_nonce_import_template_remote,
                },
                cache: false,
                success: function (response) {
                    if ( response.success ) {
                        btn.find('.txt').text('دمو اضافه شد')
                        btn.removeClass('loading')
                        setTimeout(function () {
                            btn.find('.txt').text(btn_text)
                        }, 3000);
                    }else {
                        btn.find('.txt').text('دمو اضافه نشد')
                        btn.removeClass('loading')
                        setTimeout(function () {
                            btn.find('.txt').text(btn_text)
                        },3000);
                    }
                },
                error: function (e) {
                    btn.find('.txt').text('دمو اضافه نشد')
                    btn.removeClass('loading')
                    setTimeout(function () {
                        btn.find('.txt').text(btn_text)
                    },3000);
                }
            });
        }
    })

    $('body').on('click', '#magic-factor .image-selected .btn-image-select', function () {
        let tag = $(this).closest('.image-selected');
        if (mediaUploaderObject) {
            mediaUploaderObject.open();
            return;
        }
        let type_list = ['image'];
        mediaUploaderObject = wp.media.frames.file_frame = wp.media({
            title: 'انتخاب تصویر', button: {
                text: 'درج تصویر'
            }, library: {
                type: type_list
            }, multiple: false
        });

        mediaUploaderObject.on('select', function () {
            let data = mediaUploaderObject.state().get('selection').first().toJSON();
            tag.find('.link-image').text(data.url)
            tag.find('.title-image').text(data.filename)
            tag.find('img').attr('src', data.url)
            tag.find('input').val(data.id)
            tag.addClass("uploaded")
            mediaUploaderObject.close();
            mediaUploaderObject = null;
        });
        mediaUploaderObject.open();
    })
    $('body').on('click', '#magic-factor .image-selected .btn-image-delete', function () {
        let tag = $(this).closest('.image-selected');
        tag.removeClass("uploaded");
        tag.find('.link-image').text("")
        tag.find('.title-image').text("")
        tag.find('img').attr('src', "")
        tag.find('input').val("")
    })
})(jQuery);

