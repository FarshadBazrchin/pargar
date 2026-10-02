<div class="content-setting" data-tab="setting-address-label">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-1.svg') ?>"></span>
                <span class="title-section">تنظیمات برچسب آدرس</span>
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
                $value_template = pargar_get_setting('address_label_template_id' , true ,'');

                ?>
                <div class="item-box mbl">
                    <span class="title">قالب برچسب آدرس</span>
                    <select class="value-data" name="address_label_template_id">
                        <?php foreach ($all_address_template as $key => $item) : ?>
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
                        قالب انتخابی شما به عنوان برچسب آدرس برای کاربران قابل مشاهده و دانلود خواهد بود.
                    </div>
                </div>
                <div class="item-box mbl">
                    <label>
                        <span class="title">سایز لوگو برچسب آدرس :</span>
                        <input class="value-data" type="number" name="address_label_logo_size" value="<?php echo pargar_get_setting('address_label_logo_size', true , 180) ?>">
                    </label>
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
                    <textarea dir="ltr" class="value-data" name="address_label_custom_css"><?php echo pargar_get_setting('address_label_custom_css', true, '' )?></textarea>
                </div>
            </div>
        </div>
    </section>
</div>