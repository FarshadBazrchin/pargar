<?php
if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) :
    return;
endif;

$invoice_type = 'invoice';
if ( isset( $_GET['print_pre_invoice'] ) ) :
    $invoice_type = 'pre_invoice';
else:
    $order = wc_get_order( $_GET['order_id'] );
    if ( empty( $order ) ) :
        return;
    endif;
endif;

$address_type = pargar_get_setting( 'invoice_method_address' , true , 'billing' );
$pre_code = pargar_get_setting( 'pre_value_order_code' , true , '' );

wp_enqueue_script("jquery");
wp_enqueue_style( 'magic_factor_font' , PARGAR_URL . 'assets/fonts/iranSans/font.css' , [] , PARGAR_VERSION );
wp_enqueue_script( 'magic_factor_barcode' , PARGAR_URL . 'assets/js/BarCode.js' , [] , PARGAR_VERSION );
wp_enqueue_style( 'magic_factor_styles' , PARGAR_URL . 'assets/css/invoice/pos/index.css' , [] , PARGAR_VERSION );
wp_enqueue_style('pargar-popup-styles', PARGAR_URL . 'assets/css/popup/popup.css', [], PARGAR_VERSION);
wp_enqueue_script('pargar-popup-script', PARGAR_URL . 'assets/js/popup/popup.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_enqueue_script('pargar-screenshot-script', PARGAR_URL . 'assets/js/html2canvas.min.js', array( 'jquery' ) , PARGAR_VERSION , true );
wp_localize_script('pargar-popup-script','magic_factor_parameter_backend',
    array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce_print_pdf' => wp_create_nonce('print_pdf'),
    )
);
if ( $invoice_type === 'pre_invoice' ) :
    $custom_css = pargar_get_setting( 'pre_invoice_custom_css' , true , '' );
else:
    $custom_css = pargar_get_setting( 'invoice_custom_css' , true , '' );
endif;

if ( !empty( $custom_css ) ) :
    var_dump( $custom_css );
    wp_add_inline_style( 'magic_factor_styles' , $custom_css );
endif;
wp_head();
?>

<meta charset="UTF-8">
<div class="<?php echo pargar_class_export_screenshot_png()?>" style="display: flex;flex-direction: column;">
    <main>

    <div class="pose-header">
        <?php include 'pos-header.php'; ?>
    </div>

    <div class="pos-seller-data-wrapper">
        <?php include 'seller-data.php'; ?>
    </div>

    <div class="products-list-wrapper">
        <?php include 'order-items.php';?>
    </div>

    <div class="order-total-wrapper">
        <?php include 'order-total.php'; ?>
    </div>

    <div class="pos-customer-data-wrapper">
        <?php include 'customer-data.php'; ?>
    </div>

    <?php if ( $invoice_type === 'invoice' ) : ?>
        <div class="pos-order-barcode-wrapper">
            <img id="pos-order-barcode">
            <script>
                JsBarcode( '#pos-order-barcode' , '<?php echo $pre_code . $order->get_id(); ?>' , {
                    displayValue : false ,
                    height : 40 ,
                } )
            </script>
        </div>
    <?php endif; ?>

    <?php
    $text = pargar_get_setting( 'seller_notice_text' , true , '' );
    if ( !empty( $text ) ) :
        ?>
        <div class="pos-footer-text">
            <?php echo $text; ?>
        </div>
    <?php endif; ?>

</main>
</div>
<?php pargar_view_popup_export_pdf() ?>

<div style="display: none !important;">
    <?php wp_footer(); ?>
</div>