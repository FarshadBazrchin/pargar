<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> shipping-price-widget">
    <span class="pre-value-txt"> <?php echo $settings['shipping_price_pre_value_text'] ?> </span>
    <span class="shipping-price-value">
        <?php
        if ( empty( $order->get_shipping_total() ) ) :
            echo $settings['shipping_price_replacement'];
        else:
            if ( $settings['shipping_price_symbol_status'] === 'yes' ) :
                echo wc_price( floatval( $order->get_shipping_total() ) );
            else:
                echo number_format( floatval( $order->get_shipping_total() ) ,  wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
            endif;
        endif;
        ?>
    </span>
</div>