<?php
$dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info", true, "off");
?>
<div class="seller-title-wrapper">
    <span class="is-title"> <?php esc_html_e('فروشنده', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
</div>

<div class="isw-body">

    <?php
    if (function_exists('dokan_get_store_info') && $dokan_show_vendor_info == "on") :
        $vendor_name = '';
        $items = [];
        if ($invoice_type === 'pre_invoice') :
            $items = WC()->cart->get_cart();
        else:
            $items = $order->get_items();
        endif;

        foreach ($items as $item) :
            if ($invoice_type === 'pre_invoice') :
                $product_id = $item['product_id'];
            else:
                $product_id = $item->get_product_id();
            endif;

            $seller_id = get_post_field('post_author', $product_id);
            $store_info = dokan_get_store_info($seller_id);

            if (!empty($store_info['store_name'])) :
                $vendor_name = $store_info['store_name'];
                break;
            endif;

        endforeach;

        if (empty($vendor_name)) :
            $vendor_name = pargar_get_setting('website_title', true, '-');
        endif;
    else:
        $vendor_name = pargar_get_setting('website_title', true, '-');
    endif;

    ?>

    <?php if (!empty($vendor_name)) : ?>
        <div class="isw-row">
            <span class="isw-title"> <?php esc_html_e('فروشگاه :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="isw-value"><?php echo $vendor_name ?></span>
        </div>
    <?php endif; ?>

    <?php
    if (function_exists('dokan_get_store_info') && $dokan_show_vendor_info == "on") :
        $vendor_postcode = '';
        $items = [];
        if ($invoice_type === 'pre_invoice') :
            $items = WC()->cart->get_cart();
        else:
            $items = $order->get_items();
        endif;

        foreach ($items as $item) :
            if ($invoice_type === 'pre_invoice') :
                $product_id = $item['product_id'];
            else:
                $product_id = $item->get_product_id();
            endif;

            $seller_id = get_post_field('post_author', $product_id);
            $store_info = dokan_get_store_info($seller_id);

            if (!empty($store_info['address']['zip'])) :
                $vendor_postcode = $store_info['address']['zip'];
                break;
            endif;

        endforeach;

        if (empty($vendor_postcode)) :
            $vendor_postcode = pargar_get_setting('market_postcode', true, '-');
        endif;

    else:
        $vendor_postcode = pargar_get_setting('market_postcode', true, '-');
    endif;

    ?>
    <?php if (!empty($vendor_postcode)) : ?>
        <div class="isw-row">
            <span class="isw-title"> <?php esc_html_e('کد پستی :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="isw-value"><?php echo $vendor_postcode ?> </span>
        </div>
    <?php endif; ?>

    <?php
    $dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info", true, "off");
    $economic = '';
    if (function_exists('get_vendor_economic_number') && $dokan_show_vendor_info == "on") :
        $vendor_id = 0;
        if (isset($_GET['print_invoice'])) :
            $order = wc_get_order($_GET['order_id']);
            $vendor_id = get_post_meta($order->get_id(), '_dokan_vendor_id', true);
        elseif (isset($_GET['print_pre_invoice']) AND !WC()->cart->is_empty()) :
            $cart = WC()->cart->get_cart();
            $first_item = reset($cart);
            $product_id = $first_item['product_id'];
            $vendor_id = get_post_field('post_author', $product_id);
        endif;

        if ($vendor_id) :
            $economic_number = get_vendor_economic_number($vendor_id);
            if ($economic_number) :
                $economic = $economic_number;
            else :
                $economic = pargar_get_setting('market_economical_code', true, '-');
            endif;
        endif;
    else :
        $economic = pargar_get_setting('market_economical_code', true, '-');
    endif;

    ?>

    <?php if (!empty($economic)) : ?>
        <div class="isw-row">
            <span class="isw-title"> <?php esc_html_e('شماره اقتصادی :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="isw-value"><?php echo $economic; ?></span>
        </div>
    <?php endif; ?>

    <?php
    $dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info",true , "off");
    if ( function_exists( 'dokan_get_store_info' ) &&  $dokan_show_vendor_info == "on"  ) :
        $vendor_id = 0;
        $national_id = 0;
        if (isset($_GET['print_invoice'])) :
            $order = wc_get_order($_GET['order_id']);
            $vendor_id = get_post_meta($order->get_id(), '_dokan_vendor_id', true);
        elseif (isset($_GET['print_pre_invoice']) and !WC()->cart->is_empty()) :
            $cart = WC()->cart->get_cart();
            $first_item = reset($cart);
            $product_id = $first_item['product_id'];
            $vendor_id = get_post_field('post_author', $product_id);
        endif;

        if ($vendor_id) :
            $store_info = dokan_get_store_info($vendor_id);
            $national_id = '';
            if (isset($store_info['dokan_company_national_id'])) :
                $national_id = $store_info['dokan_company_national_id'];
            elseif (isset($store_info['national_id'])) :
                $national_id = $store_info['national_id'];
            elseif (isset($store_info['melli_code'])) :
                $national_id = $store_info['melli_code'];
            endif;
            if (empty($national_id)) :
                $national_id = pargar_get_setting('market_national_code', true, '-');
            endif;
        endif;
    else :
        $national_id = pargar_get_setting('market_national_code', true, '-');
    endif;

    ?>

    <?php if (!empty($national_id)) : ?>
        <div class="isw-row">
            <span class="isw-title"> <?php esc_html_e('شماره ملی :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="isw-value"><?php echo $national_id ?></span>
        </div>
    <?php endif; ?>

    <?php
    $dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info",true , "off");

    if ( function_exists( 'dokan_get_store_info' ) &&  $dokan_show_vendor_info == "on"  ) :
        $vendor_id = 0;
        if (isset($_GET['print_invoice'])) :
            $order = wc_get_order($_GET['order_id']);
            $vendor_id = get_post_meta($order->get_id(), '_dokan_vendor_id', true);
        elseif (isset($_GET['print_pre_invoice']) and !WC()->cart->is_empty()) :
            $cart = WC()->cart->get_cart();
            $first_item = reset($cart);
            $product_id = $first_item['product_id'];
            $vendor_id = get_post_field('post_author', $product_id);
        endif;

        if ($vendor_id) :
            $reg_number = '';
            $possible_keys = [
                'dokan_company_reg_number',
                'company_registration_number',
                'registration_number',
                'shop_reg_number',
                'cr_number'
            ];
            foreach ($possible_keys as $key) :
                if (!empty($store_info[$key])) :
                    $reg_number = sanitize_text_field($store_info[$key]);
                    break;
                endif;
            endforeach;

            if (empty($reg_number)) :
                $reg_number = pargar_get_setting('market_registration_code', true, '-');
            endif;

        else :
            $reg_number = pargar_get_setting('market_registration_code', true, '-');
        endif;

    else :
        $reg_number = pargar_get_setting('market_registration_code', true, '-');
    endif;
    ?>

    <?php if (!empty($reg_number)) : ?>
        <div class="isw-row">
            <span class="isw-title"> <?php esc_html_e('شماره ثبت :', PARGAR_TEXT_DOMAIN_NAME); ?> </span>
            <span class="isw-value"><?php echo $reg_number ?></span>
        </div>
    <?php endif; ?>
</div>