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
wp_enqueue_style( 'pargar_font' , PARGAR_URL . 'assets/fonts/iranSans/font.css' , [] , PARGAR_VERSION );
wp_enqueue_script( 'pargar-barcode' , PARGAR_URL . 'assets/js/BarCode.js' , [] , PARGAR_VERSION );
wp_enqueue_style( 'pargar-template-styles' , PARGAR_URL . 'assets/css/invoice/tm1/index.css' , [] , PARGAR_VERSION );

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
    wp_add_inline_style( 'magic_factor_styles' , $custom_css );
endif;
wp_head();

if ( $invoice_type === 'pre_invoice' ) :
    if ( WC()->cart->is_empty() ) :
        include 'is-empty.php';
        return;
    endif;
endif;

?>


<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta charset="UTF-8">


<div class="<?php echo pargar_class_export_screenshot_png()?>" style="display: flex;flex-direction: column;">
    <main>

        <div class="invoice-header">
            <?php include 'invoice-header.php'; ?>
        </div>

        <div class="invoice-customer-seller-info-wrapper">

            <div class="invoice-customer-wrapper">
                <?php include 'invoice-customer-wrapper.php'; ?>
            </div>

            <?php if ( !isset( $_GET['packing_invoice'] ) ) : ?>
                <div class="invoice-seller-wrapper">
                    <?php include "invoice-seller-wrapper.php"; ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="order-body-holder">
            <div class="invoice-order-items-wrapper">
                <?php include "order-table.php"; ?>
            </div>

            <?php
            $invoice_water_mark_status = pargar_get_setting( 'invoice_water_mark' , true , 'off' );
            $pre_invoice_water_mark_status = pargar_get_setting( 'pre_invoice_water_mark' , true , 'off' );
            if ( $invoice_water_mark_status === 'on' OR $pre_invoice_water_mark_status === 'on' ) : ?>
                <div class="invoice-water-mark-wrapper">
                    <?php
                    $img_id = 0;
                    if ( $invoice_type === 'pre_invoice' ) :
                        $img_id = pargar_get_setting( 'pre_invoice_water_mark' , true , 0 );
                    elseif ( $invoice_type === 'invoice' AND !empty( $order ) ) :
                        switch ( $order->get_status() ) :
                            case 'wc-completed' :
                            case 'completed' :
                                $img_id = pargar_get_setting( 'success_water_mark' , true , 0 );
                                break;
                            case 'wc-cancelled' :
                            case 'cancelled' :
                                $img_id = pargar_get_setting( 'canceled_water_mark' , true , 0 );
                                break;
                            case 'wc-refunded' :
                            case 'refunded' :
                                $img_id = pargar_get_setting( 'restitution_water_mark' , true , 0 );
                                break;
                        endswitch;
                    endif;

                    if ( !empty( $img_id ) ) :
                        $class = is_rtl() ? 'rtl' : '';
                        $img_url = wp_get_attachment_image_url( $img_id , 'large' );
                        if ( !empty( $img_url ) ) :
                            echo "<img class='invoice-water-mark-img {$class}' src='{$img_url}'>";
                        endif;

                    endif;

                    ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="invoice-total-table-wrapper">
            <div class="invoice-extra-data-wrapper">
                <?php
                include "customer-note.php";
                include 'seller-note.php';
                include 'payment-url.php';
                ?>
            </div>
            <?php include 'total-table.php'; ?>
        </div>

        <div class="invoice-signatures-wrapper">
            <?php
            include 'seller-signature.php';
            include 'customer-signature.php';
            ?>
        </div>

        <?php
        if ( $invoice_type === 'invoice' ) :
            $text = pargar_get_setting( 'seller_notice_text' , true , '' );
        else:
            $text = pargar_get_setting( 'pre_invoice_footer_text' , true , '' );
        endif;

        if ( !empty( $text ) ) :
            ?>
            <div class="invoice-footer-text">
                <?php echo $text; ?>
            </div>
        <?php endif; ?>

    </main>
</div>
<?php pargar_view_popup_export_pdf() ?>
<?php if ( $invoice_type === 'invoice' ) : ?>
<script>
    JsBarcode( '#order-code-barcode' , '<?php echo $pre_code . $order->get_id(); ?>' , {
        displayValue : false ,
        height : 40 ,
    } )
</script>
<?php endif; ?>

<div style="display: none !important;">
    <?php wp_footer(); ?>
</div>