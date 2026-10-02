<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> coupons-total-discount-widget">
    <span class="pre-value-txt"> <?php echo $settings['coupon_total_discount_pre_value'] ?> </span>
    <span class="coupons-total-discount-value">
        <?php
        $replacement = $settings['empty_replacement_value'];
        if ( !wc_coupons_enabled() OR empty( WC()->cart->get_coupon_discount_totals() ) ) :
            echo $replacement;
        else:

            if ( $settings['show_currency_symbol_status'] === 'yes' ) :
                echo wc_price( array_sum( WC()->cart->get_coupon_discount_totals() ) );
            else:
                echo number_format( floatval( array_sum( WC()->cart->get_coupon_discount_totals() ) ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
            endif;
        endif;
        ?>
    </span>
</div>