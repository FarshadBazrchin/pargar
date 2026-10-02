<div class="content-setting" data-tab="setting-invoice">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-1.svg') ?>"></span>
                <span class="title-section">تنظیمات فاکتور</span>
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
                <?php
                $value_template = pargar_get_setting('invoice_template_id' , true ,'');

                ?>
                <div class="item-box mbl">
                    <span class="title">قالب فاکتور</span>
                    <select class="value-data" name="invoice_template_id">
                        <?php foreach ($all_template as $key => $item) : ?>
                            <?php
                            $selected = "";
                            if ( $value_template == $key ) {
                                $selected = 'selected="selected"';
                            }
                            ?>
                            <option value="<?php echo $key ?>" <?php echo $selected?>><?php echo $item . "(".$key.")"; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="description">
                        قالب انتخابی شما به عنوان فاکتور و پیش فاکتور برای کاربران قابل مشاهده و دانلود خواهد بود.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('invoice_include_product_id', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="invoice_include_product_id">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">ستون آیدی محصول</span>
                    </label>
                </div>

                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('invoice_product_image', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="invoice_product_image">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title"> تصویر محصول</span>
                    </label>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('invoice_product_discount', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="invoice_product_discount">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">ستون تخفیف</span>
                    </label>
                </div>

                <?php
                $value_date_format = pargar_get_setting('invoice_order_code_type' , true ,'id');
                $all_date_format = array(
                    'id' => "ID" ,
                    'sku' => 'SKU' ,
                    'gtin' => 'GTIN' ,
                )
                ?>
                <div class="item-box mbl">
                    <span class="title">نوع ایدی</span>
                    <select class="value-data" name="invoice_order_code_type">
                        <?php foreach ($all_date_format as $key => $item) : ?>
                            <?php
                            $selected = "";
                            if ( $value_date_format == $key ) {
                                $selected = 'selected="selected"';
                            }
                            ?>
                            <option value="<?php echo $key ?>" <?php echo $selected?>><?php echo $item ; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="description">
                        نوع آیدی انتخاب شده در ستون آیدی محصول فاکتور نمایش داده خواهد شد.
                    </div>
                </div>
                <?php
                $value_method_address = pargar_get_setting('invoice_method_address' , true ,'');
                $all_method_address = array(
                    'billing' => esc_html__( 'آدرس صورت حساب' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'shipping' => esc_html__( 'آدرس حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME ) ,
                )
                ?>
                <div class="item-box mbl">
                    <span class="title">نوع آدرس</span>
                    <select class="value-data" name="invoice_method_address">
                        <?php foreach ($all_method_address as $key => $item) : ?>
                            <?php
                            $selected = "";
                            if ( $value_method_address == $key ) {
                                $selected = 'selected="selected"';
                            }
                            ?>
                            <option value="<?php echo $key ?>" <?php echo $selected?>><?php echo $item ; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="description">
                        نوع آدرس ها و اطلاعات چاپی در فاکتور را مشخص کنید که میخواهید آدرس حمل و نقل باشد یا صورت حساب.
                    </div>
                </div>

                <?php
                $value_date_format = pargar_get_setting('invoice_date_format' , true ,'');
                $all_date_format = array(
                    'from_wb' => esc_html__( 'خواندن از وردپرس' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'Y-m-d H:i' => 'Y-m-d H:i' ,
                    'Y-m-d' => 'Y-m-d' ,
                    'Y/m/d H:i' => 'Y/m/d H:i' ,
                    'Y/m/d' => 'Y/m/d' ,
                )
                ?>
                <div class="item-box mbl">
                    <span class="title">فرمت تاریخ</span>
                    <select class="value-data" name="invoice_date_format">
                        <?php foreach ($all_date_format as $key => $item) : ?>
                            <?php
                            $selected = "";
                            if ( $value_date_format == $key ) {
                                $selected = 'selected="selected"';
                            }
                            ?>
                            <option value="<?php echo $key ?>" <?php echo $selected?>><?php echo $item ; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="description">
                        نوع و فرمت چاپ تاریخ در فاکتور را انتخاب کنید.
                    </div>
                </div>
                <div class="item-box" >
                    <span class="title">پیش متن کد سفارش :</span>
                    <input class="value-data" type="text" name="pre_value_order_code" value="<?php echo pargar_get_setting('pre_value_order_code', true, "") ?>">
                    <div class="description">
                        عبارت وارد شده در این قسمت قبل از آیدی سفارش چاپ خواهد شد.
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
                        if (pargar_get_setting('invoice_water_mark', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="invoice_water_mark">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">نمایش واترمارک</span>
                    </label>
                </div>

                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('success_water_mark', true, '');
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
                        <input type="hidden" class="value-data" name="success_water_mark" value="<?php echo $attachment_id?>">
                        <span class="title">واتر مارک وضعیت موفق</span>
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
                        زمانی که وضعیت سفارش به موفق یا تکمیل شده تغییر پیدا کند، تصویر انتخابی در این قسمت به صورت واتر مارک در وسط صفحه نمایش داده میشود
                    </div>
                </div>
                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('restitution_water_mark', true, '');
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
                        <input type="hidden" class="value-data" name="restitution_water_mark" value="<?php echo $attachment_id?>">
                        <span class="title">واتر مارک وضعیت استرداد</span>
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
                        زمانی که وضعیت سفارش به مسترد شده تغییر کند، تصویر انتخابی در این قسمت به صورت واتر مارک در وسط صفحه نمایش داده میشود
                    </div>
                </div>
                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('canceled_water_mark', true, '');
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
                        <input type="hidden" class="value-data" name="canceled_water_mark" value="<?php echo $attachment_id?>">
                        <span class="title">واتر مارک وضعیت لفو شده</span>
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
                        زمانی که وضعیت سفارش به لغو شده تغییر کند، تصویر انتخابی در این قسمت به صورت واتر مارک در وسط صفحه نمایش داده میشود
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
                    $editor_id = 'seller_notice_text';
                    $editor_content = pargar_get_setting('seller_notice_text', true, '');
                    $settings = array(
                        'textarea_name' => 'seller_notice_text',
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
                    <textarea dir="ltr" class="value-data" name="invoice_custom_css"><?php echo pargar_get_setting('invoice_custom_css', true, '' )?></textarea>
                </div>
            </div>
        </div>
    </section>
</div>