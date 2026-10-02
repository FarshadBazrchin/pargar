<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> coupons-widget">
    <span class="pre-value-txt"> <?php echo $settings['coupons_pre_value'] ?> </span>
    <span class="coupons-value">
        <?php
        $replacement = $settings['coupons_empty_value'];
        if ( !wc_coupons_enabled() OR empty( WC()->cart->get_coupons() ) ) :
            echo $replacement;
        else :
            $coupons = [];
            foreach ( WC()->cart->get_coupons() as $key => $coupon ) :
                $coupons[] = $key;
            endforeach;

            if ( !empty( $coupons ) ) :
                echo implode( ' , ' , $coupons );
            endif;

        endif;
        ?>
    </span>
</div>