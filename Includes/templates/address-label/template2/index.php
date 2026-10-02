<?php
if ( !isset( $_GET['orders_id'] , $_GET['print_address_label'] ) ) :
    return;
endif;

if ( empty( $_GET['orders_id'] ) ) :
    return;
endif;

wp_enqueue_script("jquery");
wp_enqueue_style( 'magic_factor_font' , PARGAR_URL . 'assets/fonts/iranSans/font.css' , [] , PARGAR_VERSION );
wp_enqueue_script( 'magic_factor_barcode' , PARGAR_URL . 'assets/js/BarCode.js' , [] , PARGAR_VERSION );
wp_enqueue_style( 'magic-factor-address-label-styles' , PARGAR_URL . '/assets/css/address-label/template2/index.css' , [] , PARGAR_VERSION );

wp_enqueue_style('pargar-popup-styles', PARGAR_URL . 'assets/css/popup/popup.css', [], PARGAR_VERSION);
wp_enqueue_script('pargar-popup-script', PARGAR_URL . 'assets/js/popup/popup.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_enqueue_script('pargar-screenshot-script', PARGAR_URL . 'assets/js/html2canvas.min.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_localize_script('pargar-popup-script','magic_factor_parameter_backend',
    array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce_print_pdf' => wp_create_nonce('print_pdf'),
    )
);

$website_logo = pargar_get_setting( 'website_logo' , true , 0 );
$address_label_logo_size = pargar_get_setting( 'address_label_logo_size' , true , '180' );
$custom_css = pargar_get_setting( 'address_label_custom_css' , true , '' );

wp_add_inline_style( 'magic-factor-address-label-styles' , $custom_css );

wp_head();
?>

<meta charset="UTF-8">

<div class="address-label-page <?php echo pargar_class_export_screenshot_png()?>">

    <?php
    $ids = explode( ',' , $_GET['orders_id'] );
    foreach ( $ids as $order_id ) :
        $order = wc_get_order( $order_id );
        if ( empty( $order ) ) :
            continue;
        endif;
        ?>

        <div class="address-label-wrapper">

            <div class="address-label-content">

                <div class="address-label-head">
                    <?php
                    if ( !empty( $website_logo ) ) : ?>
                        <img class="website-logo-img"
                             style="max-width: <?php echo $address_label_logo_size;?>px;"
                             src="<?php echo wp_get_attachment_image_url( $website_logo , 'Large' ) ?>">
                    <?php endif; ?>
                </div>

                <div class="market-data-wrapper">

                    <div class="md-row single-content">
                        <span class="shipping-method">
                            <?php echo $order->get_shipping_method(); ?>
                        </span>
                    </div>

                    <div class="md-row">

                        <div class="md-title-parent">
                            <img width="38" height="38"
                                 class="md-icon"
                                 src="<?php echo PARGAR_URL . '/assets/svg/truck.svg' ?>">
                            <span class="md-title"> <?php esc_html_e( 'فرستنده' , PARGAR_TEXT_DOMAIN_NAME ) ?> </span>
                        </div>

                        <div class="info-row">

                    <span class="info-value">
                        <?php
                        if ( function_exists( 'dokan_get_store_info' ) ) :
                            $vendor_name = '';

                            foreach ( $order->get_items() as $item ) :
                                $product_id = $item->get_product_id();

                                $seller_id = get_post_field( 'post_author', $product_id );
                                $store_info = dokan_get_store_info( $seller_id );

                                if ( ! empty( $store_info['store_name'] ) ) :
                                    $vendor_name = $store_info['store_name'];
                                    break;
                                endif;

                            endforeach;

                            if ( !empty( $vendor_name ) ) :
                                echo $vendor_name;
                            else:
                                echo pargar_get_setting( 'website_title' , true , '-' );
                            endif;
                        else:
                            echo pargar_get_setting( 'website_title' , true , '-' );
                        endif;
                        ?>
                    </span>

                        </div>

                        <div class="market-info-wrapper">

                            <div class="info-row">
                                <span class="info-value">
                        <?php
                        if ( function_exists( 'dokan_get_store_info' ) ) :
                            $store_address = '';
                            $countries = new WC_Countries();

                            foreach ( $order->get_items() as $item ) :
                                $product_id = $item->get_product_id();

                                $seller_id = get_post_field( 'post_author', $product_id );
                                $store_info = dokan_get_store_info( $seller_id );

                                if ( !empty( $store_info['address']['country'] ) ) :
                                    $country_name = $countries->get_countries()[$store_info['address']['country']];
                                    $store_address = "{$country_name} - ";
                                endif;

                                if ( !empty( $store_info['address']['state'] ) ) :
                                    $store_address .= "{$store_info['address']['state']} - ";
                                endif;

                                if ( !empty( $store_info['address']['city'] ) ) :
                                    $store_address .= "{$store_info['address']['state']} - ";
                                endif;

                                if ( !empty( $store_info['address']['street_1'] ) ) :
                                    $store_address .= "{$store_info['address']['street_1']} - ";
                                endif;

                                if ( !empty( $store_info['address']['street_2'] ) ) :
                                    $store_address .= "{$store_info['address']['street_2']}";
                                endif;

                                if ( !empty( $store_address ) ) :
                                    break;
                                endif;

                            endforeach;

                            if ( !empty( $store_address ) ) :
                                echo $store_address;
                            else:
                                echo pargar_get_setting( 'market_address' , true , '-' );
                            endif;

                        else:
                            echo pargar_get_setting( 'market_address' , true , '-' );
                        endif;
                        ?>

                    </span>
                            </div>

                            <div class="info-row">
                                <span class="info-title">
                        <?php esc_html_e( 'کدپستی :' , PARGAR_TEXT_DOMAIN_NAME ); ?>
                    </span>

                                <span class="info-value">
                        <?php
                        if ( function_exists( 'dokan_get_store_info' ) ) :
                            $vendor_postcode = '';

                            foreach ( $order->get_items() as $item ) :
                                $product_id = $item->get_product_id();

                                $seller_id = get_post_field( 'post_author', $product_id );
                                $store_info = dokan_get_store_info( $seller_id );

                                if ( ! empty( $store_info['address']['zip'] ) ) :
                                    $vendor_postcode = $store_info['address']['zip'];
                                    break;
                                endif;

                            endforeach;

                            if ( !empty( $vendor_postcode ) ) :
                                echo $vendor_postcode;
                            else:
                                echo pargar_get_setting( 'market_postcode' , true , '-' );
                            endif;

                        else:
                            echo pargar_get_setting( 'market_postcode' , true , '-' );
                        endif;
                        ?>
                    </span>
                            </div>

                            <div class="info-row">
                                <span class="info-title">
                        <?php esc_html_e( 'تلفن تماس :' , PARGAR_TEXT_DOMAIN_NAME ); ?>
                    </span>

                                <span class="info-value">
                                    <?php
                        if ( function_exists( 'dokan_get_store_info' ) ) :
                            $store_phone  = '';

                            foreach ( $order->get_items() as $item ) :
                                $product_id = $item->get_product_id();

                                $seller_id = get_post_field( 'post_author', $product_id );
                                $store_info = dokan_get_store_info( $seller_id );

                                if ( !empty( $store_info['phone'] ) ) :
                                    $store_phone = $store_info['phone'];
                                    break;
                                endif;

                            endforeach;

                            if ( !empty( $store_phone ) ) :
                                echo $store_phone;
                            else:
                                echo pargar_get_setting( 'market_phone_number' , true , '-' );
                            endif;

                        else:
                            echo pargar_get_setting( 'market_phone_number' , true , '-' );
                        endif;
                        ?>
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="customer-data-wrapper">

                    <div class="cd-title-side">
                        <div class="cd-title-parent">
                            <img width="28" height="28"
                                 class="cd-icon"
                                 src="<?php echo PARGAR_URL . '/assets/svg/location.svg' ?>">
                            <span class="cd-title"> <?php esc_html_e( 'گیرنده' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
                        </div>
                    </div>

                    <div class="cd-info-side">

                        <div class="info-row">
                    <span class="info-value">
                        <?php
                        if ( empty( $order->get_formatted_shipping_full_name() ) ) :
                            echo $order->get_formatted_billing_full_name();
                        else:
                            echo $order->get_formatted_shipping_full_name();
                        endif;
                        ?>
                    </span>
                        </div>

                        <div class="customer-info-wrapper">

                            <div class="info-row">
                        <span class="info-value">
                            <?php
                            $countries = new WC_Countries();
                            if ( !$order->has_shipping_address() ) :
                                $country_states = $countries->get_states( $order->get_billing_country() );
                                $state_name = $country_states[$order->get_billing_state()];
                                $country_name = $countries->get_countries()[$order->get_billing_country()];
                                $city_name = $order->get_billing_city();
                                echo "{$country_name}-{$state_name}-{$city_name}-{$order->get_billing_address_1()}";
                            else:
                                $country_states = $countries->get_states( $order->get_shipping_country() );
                                $state_name = $country_states[$order->get_shipping_state()];
                                $country_name = $countries->get_countries()[$order->get_shipping_country()];
                                $city_name = $order->get_shipping_city();
                                echo "{$country_name}-{$state_name}-{$city_name}-{$order->get_shipping_address_1()}";
                            endif;
                            ?>
                        </span>
                            </div>

                            <div class="info-row">

                                <div class="info-title">
                                    <?php esc_html_e( 'کدپستی :' , PARGAR_TEXT_DOMAIN_NAME ); ?>
                                </div>

                                <div class="info-value">
                                    <?php
                                    if ( empty( $order->get_shipping_postcode() ) ) :
                                        echo $order->get_billing_postcode();
                                    else:
                                        echo $order->get_shipping_postcode();
                                    endif;
                                    ?>
                                </div>

                            </div>

                            <div class="info-row">

                                <div class="info-title">
                                    <?php esc_html_e( 'تلفن تماس :' , PARGAR_TEXT_DOMAIN_NAME ); ?>
                                </div>

                                <div class="info-value">
                                    <?php
                                    if ( empty( $order->get_shipping_phone() ) ) :
                                        echo $order->get_billing_phone();
                                    else:
                                        echo $order->get_shipping_phone();
                                    endif;
                                    ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>

<?php pargar_view_popup_export_pdf(); ?>

<div style="display: none !important;">
    <?php wp_footer(); ?>
</div>
