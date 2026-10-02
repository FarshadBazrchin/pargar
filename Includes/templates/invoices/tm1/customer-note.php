<?php
if ( $invoice_type === 'invoice' ) :
    if ( !empty( $order->get_customer_note() ) ) :
?>

<div class="customer-note-wrapper">
    <span class="customer-note-title"> <?php esc_html_e( 'یادداشت مشتری :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="customer-note-value"> <?php echo $order->get_customer_note() ?> </span>
</div>

<?php endif; endif; ?>