
<div class="content-setting" data-tab="setting-store">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('store.svg')?>"></span>
                <span class="title-section">تنظیمات فروشگاه</span>
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
            <div class="items-box titles-store">
                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('website_logo', true, '');
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
                        <input type="hidden" class="value-data" name="website_logo" value="<?php echo $attachment_id?>">
                        <span class="title">انتخاب لوگو فروشگاه</span>
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
                        تصویر و لوگویی که قصد دارید در فاکتور ها به عنوان نماد فروشگاه شما قرار گیرد را از این بخش انتخاب کنید.
                    </div>
                </div>
                <div class="item-box" >
                    <span class="title">عنوان فروشگاه :</span>
                    <input class="value-data" type="text" name="website_title" value="<?php echo pargar_get_setting('website_title', true, get_bloginfo('name')) ?>">
                </div>
                <div class="item-box">
                    <span class="title">آدرس فروشگاه :</span>
                    <input class="value-data" type="text" name="market_address" value="<?php echo pargar_get_setting('market_address', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">شماره اقتصادی فروشگاه :</span>
                    <input class="value-data" type="text" name="market_economical_code" value="<?php echo pargar_get_setting('market_economical_code', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">شماره ملی فروشگاه :</span>
                    <input class="value-data" type="text" name="market_national_code" value="<?php echo pargar_get_setting('market_national_code', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">شماره ثبت فروشگاه :</span>
                    <input class="value-data" type="text" name="market_registration_code" value="<?php echo pargar_get_setting('market_registration_code', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">ایمیل فروشگاه :</span>
                    <input class="value-data" type="text" name="website_email" value="<?php echo pargar_get_setting('website_email', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">تلفن تماس فروشگاه :</span>
                    <input class="value-data" type="text" name="market_phone_number" value="<?php echo pargar_get_setting('market_phone_number', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">کد پستی فروشگاه :</span>
                    <input class="value-data" type="text" name="market_postcode" value="<?php echo pargar_get_setting('market_postcode', true) ?>">
                </div>
                <div class="item-box">
                    <span class="title">آدرس url فروشگاه :</span>
                    <input class="value-data" type="text" name="website_url" value="<?php echo pargar_get_setting('website_url', true) ?>">
                </div>
                <div class="item-box">
                    <?php
                    $attachment_id = pargar_get_setting('seller_signature', true, '');
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
                        <input type="hidden" class="value-data" name="seller_signature" value="<?php echo $attachment_id?>">
                        <span class="title">مهر و امضاء افروشگاه</span>
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
                        تصویری که قصد دارید به عنوان امضا خود در فاکتور نمایش داده شود را از این بخش انتخاب کنید.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>