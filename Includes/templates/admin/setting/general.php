<div class="content-setting active" data-tab="setting-general">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('settings.svg') ?>"></span>
                <span class="title-section">تنظیمات عمومی</span>
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
                        if (pargar_get_setting('include_invoice_link_in_email', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="include_invoice_link_in_email">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">ضمیمه کردن لینک فاکتور در ایمیل سفارش مشتری</span>
                    </label>

                    <div class="description">
                        با فعال سازی این گزینه، لینک سفارش در ایمیل ارسالی برای مشتری قرار داده خواهد شد.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('print_invoice_for_user_panel', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="print_invoice_for_user_panel">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">نمایش چاپ فاکتور در پنل کاربری</span>
                    </label>
                    <div class="description">
                        این گزینه به کاربران این امکان را میدهد که در پنل کاربری خود نیز بتوانند فاکتور های سفارشات خود را مشاهده کنند.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('screenshot_invoice_png', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="screenshot_invoice_png">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">فعال کردن امکان خروجی گرفتن تصویری (PNG)</span>
                    </label>
                </div>
                <div class="items-box">
                    <?php
                    $value_template = pargar_get_setting('output_type_pdf' , true ,'browser');

                    ?>
                    <div class="item-box mbl">
                        <span class="title">خروجی PDF</span>
                        <select class="value-data" name="output_type_pdf">
                            <?php
                            $list_export_pdf = array(
                                    'browser' => "خروجی با استفاده از مروگر",
                                    /*'api' => "خروجی با استفاده از پرگار",*/
                            );
                            foreach ($list_export_pdf as $key => $item) : ?>
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
                            انتخاب کنید که عملیات پردازش و تبدیل فاکتور به PDF از چه طریقی صورت گیرد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('dokan.svg') ?>"></span>
                <span class="title-section">تنظیمات دکان</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('dokan_show_vendor_btn_invoice', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="dokan_show_vendor_btn_invoice">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">فعال سازی صدور فاکتور برای فروشندگان</span>
                    </label>
                    <div class="description">
                        اگر می خواهید دکمه های چاپ  مرتبط  با فاکتور در داشبورد فروشندگان نمایش داده شود این گزینه را فعال کنید.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label class="checkbox">
                        <?php
                        $class = "";
                        $checked = '';
                        if (pargar_get_setting('dokan_show_vendor_info', true, 'off') == "on"):
                            $class = "checked";
                            $checked = 'checked="checked"';
                        endif;
                        ?>
                        <span class="custom-checkbox <?php echo $class ?>">
                            <input class="value-data"
                                   type="checkbox" <?php echo $checked ?> name="dokan_show_vendor_info">
                            <span class="circle-checkbox"></span>
                        </span>
                        <span class="title">نمایش اطلاعات فروشنده</span>
                    </label>
                    <div class="description">
                        اگر از افزونه دکان در فروشگاه خود استفاده میکنید، با فعال کردن این گزینه در زمان چاپ فاکتور و پیش فاکتور، اطلاعات فروشنده (نام فروشگاه، تلفن تماس، ادرس و ...) در فاکتور نمایش داده میشود.
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('store.svg') ?>"></span>
                <span class="title-section">فعال سازی صدور فاکتور</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl value-data group-custom-checkbox" data-name="invoice_issuance_modes">
                    <div class="description" style="margin-top: 0">با فعال سازی هریک از گزینه های زیر در جدول سفارشات ووکامرس، ادمین میتواند به هریک از بخش های زیر در سفارشات دسترسی داشته باشد.</div>

                    <?php
                    $default = [
                            'packing_invoice' => "off",
                            'pre_invoice' => "off",
                            'product_label_invoice' => "off",
                            'address_label_invoice' => "on",
                    ];
                    $setting = pargar_get_setting('invoice_issuance_modes', true, $default);
                    ?>

                    <div class="item-group ">
                        <label class="checkbox">
                            <?php
                            $class = "";
                            $checked = '';
                            if ( isset( $setting['packing_invoice'] ) && $setting['packing_invoice'] == "on"):
                                $class = "checked";
                                $checked = 'checked="checked"';
                            endif;
                            ?>
                            <span class="custom-checkbox <?php echo $class ?>">
                            <input class="group-data"
                                   type="checkbox" <?php echo $checked ?> name="packing_invoice">
                            <span class="circle-checkbox"></span>
                            </span>
                            <span class="title">انبار داری</span>
                        </label>
                        <label class="checkbox">
                            <?php
                            $class = "";
                            $checked = '';
                            if ( isset( $setting['pre_invoice'] ) && $setting['pre_invoice'] == "on"):
                                $class = "checked";
                                $checked = 'checked="checked"';
                            endif;
                            ?>
                            <span class="custom-checkbox <?php echo $class ?>">
                            <input class="group-data"
                                   type="checkbox" <?php echo $checked ?> name="pre_invoice">
                            <span class="circle-checkbox"></span>
                            </span>
                            <span class="title">پیش فاکتور</span>
                        </label>
                        <label class="checkbox">
                            <?php
                            $class = "";
                            $checked = '';
                            if (isset( $setting['product_label_invoice'] ) && $setting['product_label_invoice'] == "on"):
                                $class = "checked";
                                $checked = 'checked="checked"';
                            endif;
                            ?>
                            <span class="custom-checkbox <?php echo $class ?>">
                            <input class="group-data"
                                   type="checkbox" <?php echo $checked ?> name="product_label_invoice">
                            <span class="circle-checkbox"></span>
                            </span>
                            <span class="title">برچسب محصول</span>
                        </label>
                        <label class="checkbox">
                            <?php
                            $class = "";
                            $checked = '';
                            if (isset( $setting['address_label_invoice'] ) && $setting['address_label_invoice'] == "on"):
                                $class = "checked";
                                $checked = 'checked="checked"';
                            endif;
                            ?>
                            <span class="custom-checkbox <?php echo $class ?>">
                            <input class="group-data"
                                   type="checkbox" <?php echo $checked ?> name="address_label_invoice">
                            <span class="circle-checkbox"></span>
                            </span>
                            <span class="title">برچسب آدرس</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('lock.svg') ?>"></span>
                <span class="title-section">سطح دسترسی صدور فاکتور</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl value-data group-custom-checkbox" data-name="access_level">
                    <div class="description" style="margin-top: 0">
                        مشخص کنید که در پنل ادمین چه کسانی مجاز به دیدن فاکتورهای سفارشات مشتریان شما هستند.
                    </div>

                    <?php
                    global $wp_roles;
                    if ( ! isset( $wp_roles ) ) {
                        $wp_roles = new WP_Roles();
                    }

                    $all_capabilities = array();

                    foreach ( $wp_roles->roles as $role_name => $role_info ) :
                        $role = get_role( $role_name );
                        $all_capabilities[$role_name] = translate_user_role($role_info['name']);
                    endforeach;
                    $default = array(
                            'administrator' => "on",
                    );
                    $setting = pargar_get_setting('access_level', true, $default);
                    ?>
                    <div class="item-group ">
                        <?php foreach ( $all_capabilities as $role_key => $role_name ) :
                            if ( $role_key == "administrator" ){
                                continue;
                            }
                            ?>
                            <label class="checkbox">
                                <?php
                                $class = "";
                                $checked = '';
                                if ( isset( $setting[$role_key] ) && $setting[$role_key] == "on"):
                                    $class = "checked";
                                    $checked = 'checked="checked"';
                                endif;
                                ?>
                                <span class="custom-checkbox <?php echo $class ?>">
                                    <input class="group-data" type="checkbox" <?php echo $checked ?> name="<?php echo $role_key?>">
                                    <span class="circle-checkbox"></span>
                                </span>
                                <span class="title"><?php echo $role_name?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('size.svg') ?>"></span>
                <span class="title-section">سایز لوگو</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <label>
                        <span class="title">سایز لوگو فاکتور :</span>
                        <input class="value-data" type="number" name="market_logo_size" value="<?php echo pargar_get_setting('market_logo_size', true , 500) ?>">
                    </label>
                </div>
            </div>
        </div>
    </section>
</div>