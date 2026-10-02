<?php

if ( !isset( $_GET['orders_id'] , $_GET['print_product_label'] ) ) :
    return;
endif;

if ( empty( $_GET['orders_id'] ) ) :
    return;
endif;

wp_enqueue_script("jquery");
wp_enqueue_style( 'pargar_font' , PARGAR_URL . 'assets/fonts/iranSans/font.css' , [] , PARGAR_VERSION );
wp_enqueue_script( 'pargar_barcode' , PARGAR_URL . 'assets/js/BarCode.js' , [] , PARGAR_VERSION );
wp_enqueue_style( 'pargar-product-label-styles' , PARGAR_URL . 'assets/css/product-label/template1/index.css' , [] , PARGAR_VERSION );
wp_enqueue_style('pargar-popup-styles', PARGAR_URL . 'assets/css/popup/popup.css', [], PARGAR_VERSION);
wp_enqueue_script('pargar-popup-script', PARGAR_URL . 'assets/js/popup/popup.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_enqueue_script('pargar-screenshot-script', PARGAR_URL . 'assets/js/html2canvas.min.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_localize_script('pargar-popup-script','magic_factor_parameter_backend',
    array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce_print_pdf' => wp_create_nonce('print_pdf'),
    )
);
wp_head();
$ids = explode( ',' , $_GET['orders_id'] );

foreach ( $ids as $order_id ) :
$order = wc_get_order( $order_id );
if ( empty( $order ) ) :
    continue;
endif;

?>

<meta charset="UTF-8">

<div class="product-label-page <?php echo pargar_class_export_screenshot_png()?>">

<?php

foreach ( $order->get_items() as $item ) :
    $product = $item->get_product();
?>

<div class="product-label-wrapper">

    <div class="product-label-head">

        <span class="website-title"> <?php echo pargar_get_setting( 'website_title' , true , get_bloginfo( 'name' ) ); ?> </span>

        <span class="product-title"> <?php echo $item->get_name() ?> </span>

        <span class="creation-date">
            <?php
            $locale = get_locale();
            $format = pargar_get_setting( 'invoice_date_format' , true , 'Y/m/d' );
            if ( $format === 'from_wb' ) :
                $format = get_option('date_format');
            endif;

            if ( $locale === 'fa_IR' ) :
                require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
                echo jdate( $format , strtotime( $order->get_date_created() ) );
            else :
                echo date( $format , strtotime( $order->get_date_created() ) );
            endif;

            ?>
        </span>

    </div>

    <div class="barcode-wrapper">
        <img class="product-label-barcode-image"
             id="product-label-barcode-image-<?php echo $product->get_id() ?>"
             data-id="<?php echo $product->get_id() ?>"
             src="">
    </div>

</div>

<?php
    endforeach;
endforeach;
?>

</div>

<?php pargar_view_popup_export_pdf(); ?>
<script>

    (function ($) {
        "use strict";
        let elements = $('.product-label-barcode-image');

        for ( let i = 0 ; i <elements.length ; i++) {
            let value = $( elements ).eq( i ).attr( 'data-id' );
            let id = $( elements ).eq( i ).attr( 'id' );
            JsBarcode( `#${id}` , value , {
                displayValue : false ,
                height : 40 ,
            } )
        }

    })(jQuery)

</script>

<div style="display: none !important;">
    <?php wp_footer(); ?>
</div>
