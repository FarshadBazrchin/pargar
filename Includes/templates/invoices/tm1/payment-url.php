<?php
$payment_url_status = pargar_get_setting( 'pre_invoice_show_payment_url' , true , 'off' );
if ( $payment_url_status === 'on' AND $invoice_type === 'pre_invoice' ) :
?>

    <div class="payment-url-wrapper">
        <span class="payment-url-title"> <?php esc_html_e( 'لینک پرداخت:' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <a href="<?php echo wc_get_checkout_url() ?>" target="_blank" class="payment-url-value">
            <?php echo wc_get_checkout_url(); ?>
        </a>
    </div>

<?php
endif;
?>