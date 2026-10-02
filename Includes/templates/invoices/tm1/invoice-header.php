<div class="website-info-wrapper">

    <?php
    $website_title = pargar_get_setting('website_title', true, '-');;
    if (!empty($website_title)):
        ?>
        <div class="website-info-row">
            <span class="ih-title"> <?php esc_html_e('عنوان :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="ih-value">
            <?php echo pargar_get_setting('website_title', true, '-'); ?>
        </span>
        </div>
    <?php endif; ?>

    <?php
    $website_email = pargar_get_setting('website_email', true, '-');;
    if (!empty($website_email)):
        ?>
        <div class="website-info-row">
            <span class="ih-title"> <?php esc_html_e('ایمیل :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="ih-value"> <?php echo pargar_get_setting('website_email', true, '-'); ?> </span>
        </div>
    <?php endif; ?>

    <?php
    $website_url = pargar_get_setting('website_url', true, '-');;
    if (!empty($website_url)):
        ?>
        <div class="website-info-row">
            <span class="ih-title"> <?php esc_html_e('وبسایت :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="ih-value"> <?php echo pargar_get_setting('website_url', true, '-'); ?> </span>
        </div>
    <?php endif; ?>

    <?php
    $market_phone_number = pargar_get_setting('market_phone_number', true, '-');
    if (!empty($market_phone_number)):
        ?>
        <div class="website-info-row">
            <span class="ih-title"> <?php esc_html_e('تلفن :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="ih-value"> <?php echo pargar_get_setting('market_phone_number', true, '-'); ?> </span>
        </div>
    <?php endif; ?>

</div>

<?php
$market_image_size = pargar_get_setting( 'market_logo_size' , true , '500' );
$id = pargar_get_setting('website_logo', true, '');
$url = '';
if (!empty($id)) :
    $url = wp_get_attachment_image_url($id, 'Large');
endif;
?>

<img style="height :<?php echo $market_image_size; ?>px;"
     loading="lazy" class="website-logo-img" src="<?php echo $url; ?>">

<div class="invoice-info-wrapper">

    <div class="invoice-info-row">
        <span class="ii-title"> <?php esc_html_e('تاریخ چاپ :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
        <span class="ii-value">
            <?php
            $locale = get_locale();
            $format = pargar_get_setting('invoice_date_format', true, 'Y/m/d');
            if ($format === 'from_wp') :
                $settings['order_completed_date_format'] = get_option('date_format');
            endif;

            if ($locale === 'fa_IR') :
                require_once PARGAR_PATH . 'Includes/Processing/Jalali/jdf.php';
                echo jdate($format, time());
            else:
                echo date($format, time());
            endif;
            ?>
        </span>
    </div>

    <?php if ($invoice_type === 'invoice') : ?>
        <div class="invoice-info-row">
            <span class="ii-title"> <?php esc_html_e('شناسه سفارش :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="ii-value">
            <?php
            echo $pre_code . $order->get_id();
            ?>
        </span>
        </div>
    <?php endif; ?>

    <div class="invoice-info-row">
        <span class="ii-title"> <?php esc_html_e('وضعیت سفارش :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
        <span class="ii-value">
              <?php
              if ($invoice_type === 'pre_invoice') :
                  echo esc_html__('پیش فاکتور', PARGAR_TEXT_DOMAIN_NAME);
              else:
                  if (isset(wc_get_order_statuses()['wc-' . $order->get_status()])) :
                      echo wc_get_order_statuses()['wc-' . $order->get_status()];
                  elseif (isset(wc_get_order_statuses()[$order->get_status()])) :
                      echo wc_get_order_statuses()[$order->get_status()];
                  else:
                      echo '-';
                  endif;
              endif;
              ?>
        </span>
    </div>

    <?php if ($invoice_type === 'invoice') : ?>
        <img width="200" id="order-code-barcode" class="order-code-barcode">
    <?php endif; ?>

</div>