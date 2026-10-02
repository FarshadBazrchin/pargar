<div class="wrap">
    <div id="magic-factor" class="main-setting">
        <div class="menu-setting">
           <div class="list-items">
               <div class="item active" data-tab="setting-general">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('settings.svg')?>"></span>
                   <span class="title-item">تنظیمات عمومی</span>
               </div>
               <div class="item" data-tab="setting-invoice">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('vector-1.svg')?>"></span>
                   <span class="title-item">تنظیمات فاکتور</span>
               </div>
               <div class="item" data-tab="setting-pre-invoice">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('vector-1.svg')?>"></span>
                   <span class="title-item">تنظیمات پیش فاکتور</span>
               </div>
               <div class="item" data-tab="setting-address-label">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('vector-1.svg')?>"></span>
                   <span class="title-item">تنظیمات برچسب آدرس</span>
               </div>
               <div class="item" data-tab="setting-store">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('store.svg')?>"></span>
                   <span class="title-item">تنظیمات فروشگاه</span>
               </div>
               <div class="item" data-tab="setting-import">
                   <span class="icon-item mask-icon" style="<?php pargar_svg_style('vector-5.svg')?>"></span>
                   <span class="title-item">درون ریزی</span>
               </div>
           </div>
        </div>
        <?php
        include PARGAR_PATH_TEMPLATE . '/admin/setting/store.php';;
        include PARGAR_PATH_TEMPLATE . '/admin/setting/general.php';
        include PARGAR_PATH_TEMPLATE . '/admin/setting/invoice.php';
        include PARGAR_PATH_TEMPLATE . '/admin/setting/pre_invoice.php';
        include PARGAR_PATH_TEMPLATE . '/admin/setting/address_label.php';
        include PARGAR_PATH_TEMPLATE . '/admin/setting/import.php';
        ?>
    </div>
</div>