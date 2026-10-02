<style>
    .invoice-website-logo[src=''] {
        display: none;
    }
</style>
<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-icon-widget">
    <?php
    $id = pargar_get_setting( 'website_logo' , true , 0 );
    if ( !empty( $id ) ) :
    ?>
    <img width="<?php echo $settings['market_icon_size']['width'] ?? 125 ?>"
         height="<?php echo $settings['market_icon_size']['height'] ?? 125 ?>"
         class="invoice-website-logo" src="<?php echo wp_get_attachment_image_url( $id , 'Large' ) ?>">
    <?php endif; ?>
</div>