<span class="pos-order-id">
    <?php
    echo esc_html__( 'شماره سفارش' , PARGAR_TEXT_DOMAIN_NAME );
    echo ' ' . $pre_code . $order->get_id();
    ?>
</span>

<span class="pos-order-creation-date">
    <?php

    $locale = get_locale();
    $format = pargar_get_setting( 'invoice_date_format' , true , 'Y/m/d' );
    if ( $format === 'from_wp' ) :
        $settings['order_completed_date_format'] = get_option( 'date_format' );
    endif;

    if ( $locale === 'fa_IR' ) :
        require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
        echo jdate( $format , time() );
    else:
        echo date( $format , time() );
    endif;

    ?>
</span>