<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-signature-widget">

    <?php
    $img_url = '';
    if ( $settings['get_signature_from'] === 'from_setting' ) :
        $signature_image_id = pargar_get_setting( 'seller_signature' , true , 0 );
        if ( !empty( $signature_image_id ) ) :
            $img_url = wp_get_attachment_image_url( $signature_image_id , 'Large' );
        endif;

    else:
        $img_url = $settings['media_signature']['url'] ?? '';
    endif;
    ?>

    <?php if ( !empty( $img_url ) ) : ?>
    <img src="<?php echo $img_url; ?>"
         height="<?php echo $settings['signature_image_size']['height'] ?? 125 ?>"
         width="<?php echo $settings['signature_image_size']['width'] ?? 125 ?>" class="market-signature-img">
    <?php endif; ?>
</div>