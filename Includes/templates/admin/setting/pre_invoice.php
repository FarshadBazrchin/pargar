<div class="content-setting" data-tab="setting-pre-invoice">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-1.svg') ?>"></span>
                <span class="title-section">تنظیمات پیش فاکتور</span>
            </div>
            <div class="btn-actions">
                <div class="btn-save-setting">
                    <span class="btn_text">ذخیره سازی تنظیمات</span>
                    <div>
                        <span class="loading-btn"></span>
                    </div>
                </div>
                <div class="btn-default-setting">بازگشت تمام تنظیمات به پیش فرض</div>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('pre_invoice_show_in_cart_status', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="pre_invoice_show_in_cart_status">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">نمایش پیش فاکتور در سبد خرید</span>
                    </label>
                    <div class="description">
                        با فعال سازی این گزینه دکمه چاپ پیش فاکتور در سبد خرید قابل مشاهده خواهد بود.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('pre_invoice_in_payment_page', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="pre_invoice_show_in_cart_checkout">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">صفحه پرداخت</span>
                    </label>
                    <div class="description">
                        با فعال سازی این گزینه دکمه چاپ پیش فاکتور در صفحه پرداخت (checkout) قابل مشاهده خواهد بود.
                    </div>
                </div>
                <div class="item-box">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('pre_invoice_show_payment_url', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="pre_invoice_show_payment_url">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">نمایش لینک پرداخت</span>
                    </label>
                    <div class="description">
                        با فعال سازی این گزینه لینک صفحه پرداخت (checkout) در پیش فاکتور برای کاربر چاپ خواهد شد.
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-2.svg') ?>"></span>
                <span class="title-section">واتر مارکت</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('pre_invoice_water_mark', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="pre_invoice_water_mark">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">واتر مارک پیش نمایش</span>
                    </label>
                </div>

                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('pre_invoice_water_mark', true, '');
                    $url_attachment = null;
                    $name_attachment = null;
                    $class = '';
                    if (!empty( $attachment_id ) ):
                        $class = "uploaded";
                        $file_path = get_attached_file($attachment_id);
                        $url_attachment = wp_get_attachment_url( $attachment_id );
                        $name_attachment = basename($file_path);
                    endif;
                    ?>
                    <div class="image-selected <?php echo $class ?>">
                        <input type="hidden" class="value-data" name="pre_invoice_water_mark" value="<?php echo $attachment_id?>">
                        <span class="title">واتر مارک</span>
                        <div class="controller-image">
                            <div class="preview-image">
                                <span class="mask-icon icon-image" style="<?php pargar_svg_style('image.svg')?>"></span>
                                <img class="image" src="<?php echo $url_attachment ?>">
                            </div>
                            <div class="info-image">
                                <span class="link-image"><?php echo $url_attachment ?></span>
                                <div class="action-select-image">
                                    <span class="title-image"><?php echo $name_attachment?></span>
                                    <div class="btn-image-select">
                                        <span>آپلود تصویر</span>
                                    </div>
                                    <div class="btn-image-delete">
                                        <span class="mask-icon btn-delete-icon" style="<?php pargar_svg_style('trash.svg')?>"></span>
                                        <span>حذف تصویر</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        زمانی که سفارش هنوز ثبت نشده و کاربر در حال مشاهده پیش فاکتور است، تصویر انتخابی در این قسمت به صورت واتر مارک در وسط صفحه نمایش داده میشود
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-3.svg') ?>"></span>
                <span class="title-section">متن پاورقی</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <?php
                    $editor_id = 'pre_invoice_footer_text';
                    $editor_content = pargar_get_setting('pre_invoice_footer_text', true, '');
                    $settings = array(
                        'textarea_name' => 'pre_invoice_footer_text',
                        'media_buttons' => true,
                        'teeny' => false,
                        'quicktags' => true,
                        'editor_class'  => 'value-data',
                    );

                    wp_editor($editor_content, $editor_id, $settings);
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-4.svg') ?>"></span>
                <span class="title-section">css سفارشی</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <textarea dir="ltr" class="value-data" name="pre_invoice_custom_css"><?php echo pargar_get_setting('pre_invoice_custom_css', true, '' )?></textarea>
                </div>
            </div>
        </div>
    </section>
</div>