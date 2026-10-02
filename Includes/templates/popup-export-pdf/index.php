<div id="bg-popup-export-pdf">
    <?php
    $scale = 70;
    $type_page_factor = "printed";

    if ( isset( $data['scale'] ) ){
        $scale = $data['scale']['size'];
    }

    if ( isset( $data['type_page_factor'] ) ){
        $type_page_factor = $data['type_page_factor'];
    }


    ?>
    <div class="popup-export-pdf">
        <div class="popup-head">
            <div class="popup-btn-close">
                <span class="mask-icon icon-close" style="<?php pargar_svg_style('close.svg'); ?>"></span>
                <span class="text">بستن</span>
            </div>
        </div>
        <div class="popup-body">
            <h3 class="popup-title">تنظیمات چاپ فاکتور</h3>
            <div class="row-input">
                <div class="input-p">
                    <span class="title">اندازه کاغذ برای چاپ</span>
                    <select id="page_size">
                        <option value="A3">A3</option>
                        <option value="A4"
                            <?php if ( $type_page_factor == "printed"):
                                echo "selected";
                            endif;?>
                        >A4</option>
                        <option value="A5">A5</option>
                        <option value="custom"
                            <?php if ( $type_page_factor == "digital"):
                                echo "selected";
                            endif;?>
                        >دیجیتال ( بدون صفحه بندی )</option>
                    </select>
                </div>
                <p class="description">گزینه‌های موجود شامل A4، A5، و A3 هستند. مقدار پیش‌فرض این فیلد، A4 تنظیم شده
                    است.</p>
            </div>
            <div class="row-input">
                <div class="image-selected">
                    <span class="title">جهت گیری صفحه</span>
                    <p class="description">شما میتوانید انتخاب کنید که صفحه چاپی به صورت عمودی یا افقی  باشد</p>
                    <div class="input-image-selected" >
                        <div class="item-selected active portrait" data-value="portrait">
                            <img src="<?php echo  PARGAR_URL.'assets/image/portrait.png'?>">
                            <span class="text-selected">انتخاب شده - عمودی</span>
                        </div>
                        <div class="item-selected landscape" data-value="landscape">
                            <img src="<?php echo  PARGAR_URL.'assets/image/landscape.png'?>">
                            <span class="text-selected">انتخاب شده - افقی </span>
                        </div>
                        <input type="hidden" id="page_orientation" value="portrait">
                    </div>
                </div>
            </div>
            <div class="row-input">
                <div class="scale-input">
                    <span class="title">مقیاس</span>
                    <div class="input-scala">
                        <input class="range-scale" type="range" max="100" min="1" value="<?php echo $scale?>">
                        <div class="custom-scale">
                            <div class="btns-scale">
                                <div class="btn-plus">
                                    <span class="scale-icon mask-icon" style="<?php echo pargar_svg_style( 'up.svg' )?>"></span>
                                </div>
                                <div class="btn-minus">
                                    <span class="scale-icon mask-icon" style="<?php echo pargar_svg_style( 'down.svg' )?>"></span>
                                </div>
                            </div>
                            <input id="page_scale" class="custom-scale-input" type="text" value="<?php echo $scale?>">
                        </div>
                    </div>
                    <p class="description">برای تنظیم مقیاس چاپ در فایل PDF، از این گزینه استفاده کنید. این تنظیم به شما اجازه می‌دهد اندازه یا نسبت صفحه را برای چاپ دلخواه خود انتخاب کنید.</p>
                </div>
            </div>
            <div class="btns">
                <span class="btn-print-pdf">
                    <span class="text-a">چاپ PDF</span>
                    <span class="loading-export"></span>
                </span>
            </div>
        </div>
    </div>
    <input type="hidden" id="url" value="<?php echo $_SERVER['REQUEST_URI'];?>">
    <input type="hidden" id="type_page_factor" value="<?php echo $type_page_factor;?>">
</div>
<?php if ( pargar_active_export_screenshot_png() ): ?>
    <script>
        function takeScreenshot() {
            const elementToCapture = document.querySelector('.<?php echo pargar_class_export_screenshot_png()?>');
            html2canvas(elementToCapture, {
                scale: 3,
                useCORS: true,
            }).then(function(canvas) {
                var link = document.createElement('a');
                let t = + new Date();
                link.download = 'pargar-invoice-'+t+'.png';
                link.href = canvas.toDataURL("image/png");
                link.click();
            }).catch(function(error) {
            });
        }
    </script>
    <div id="btn-export-png" onclick="takeScreenshot()">
        <span class="icon-export" style="<?php pargar_svg_style('png.svg')?>"></span>
    </div>
<?php endif; ?>
<div id="btn-export-pdf" data-t="<?php echo pargar_get_setting('output_type_pdf',true,'browser')?>"
     data-in="<?php if ( isset( $_GET['print_pre_invoice'] ) ):
         echo "1";
     else:
         echo "0";
     endif; ?>">
    <span class="icon-export" style="<?php pargar_svg_style('export-pdf.svg')?>"></span>
</div>