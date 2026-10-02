<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> coupons-widget">
    <span class="pre-value-txt"> <?php echo $settings['coupons_pre_value'] ?> </span>
    <span class="coupons-value">
        <?php
        $replacement = $settings['coupons_empty_value'];
        if ( !wc_coupons_enabled() OR empty( $order->get_coupon_codes() ) ) :
            echo $replacement;
        else :
            echo implode( ' , ' , $order->get_coupon_codes() );
        endif;
        ?>
    </span>
</div>