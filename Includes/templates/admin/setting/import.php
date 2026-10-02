<div class="content-setting" data-tab="setting-import">
    <section>
        <div class="section-head">
            <div class="icon-title">
                <span class="icon-section mask-icon" style="<?php pargar_svg_style('vector-5.svg') ?>"></span>
                <span class="title-section">طرح های آماده</span>
            </div>
        </div>
        <div class="section-body">
            <div class="items-box">
                <div class="item-box mbl">
                    <span class="title">طرح های آماده</span>
                    <div class="description">اگر قصد دارید تا از فاکتور های طراحی شده با المنتور ما استفاده کنید، کافیت
                        تا از این بخش طرح مورد نظر خود را دانلود و به عنوان طرح پیشفرض جهت چاپ آن را انتخاب کنید.
                    </div>
                    <div class="list-template">
                        <?php if (is_array($all_template_remote)):
                            foreach ($all_template_remote as $item):?>
                                <div class="item-template">
                                    <div class="bg-image">
                                        <img src="<?php echo $item['img'] ?>"/>
                                        <?php if ($item['require_version'] > PARGAR_VERTION_CODE) : ?>
                                            <div class="error-version">
                                                <span><?php esc_html_e('شما درحال حاظر از نسخه قدیمی افزونه مجیک فاکتور استفاده می کنید برای دریافت دمو به نسخه جدید را  نصب کنید', PARGAR_TEXT_DOMAIN_NAME); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="date-update"><?php echo $item['modified_date'] ?></span>
                                            <?php if (sizeof($item['tag']) > 0): ?>
                                                <div class="tags">
                                                    <?php foreach ($item['tag'] as $value): ?>
                                                        <span class="tag"><?php echo $value ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <span class="title-template"><?php echo $item['title'] ?></span>
                                    <div class="item-bottom">
                                        <?php if ($item['require_version'] > PARGAR_VERTION_CODE) : ?>
                                            <div class="btn">
                                                <span class="txt">دریافت دمو</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="btn import-template" data-demo="<?php echo $item['url_txt'] ?>">
                                                <span class="txt">دریافت دمو</span>
                                                <div>
                                                    <span class="loading-btn"></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <a class="btn preview-template" target="_blank"
                                           href="<?php echo $item['preview_url'] ?>">پیشنمایش</a>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>