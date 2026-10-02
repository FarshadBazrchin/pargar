<div class="seller-signature-wrapper">
    <span class="signature-text"> <?php esc_html_e( 'امضاء فروشگاه :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <?php
    $signature_image_id = pargar_get_setting( 'seller_signature' , true , 0 );
    if ( !empty( $signature_image_id ) ) :
    ?>

    <img class="seller-signature-icon"
         src="<?php echo wp_get_attachment_image_url( $signature_image_id , 'Large' ) ?>" >

    <?php endif; ?>
</div>