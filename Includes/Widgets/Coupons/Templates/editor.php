<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> coupons-widget">
    <span class="pre-value-txt"> <?php echo $settings['coupons_pre_value'] ?> </span>
    <span class="coupons-value">
        <?php
        $replacement = $settings['coupons_empty_value'];
        if ( !wc_coupons_enabled() ) :
            echo $replacement;
        else :
            esc_html_e( 'pargar-coupon' , PARGAR_TEXT_DOMAIN_NAME );
        endif;
        ?>
    </span>
</div>