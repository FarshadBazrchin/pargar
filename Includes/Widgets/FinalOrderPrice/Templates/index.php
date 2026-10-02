<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> final-order-price-widget">
    <span class="pre-value-txt"> <?php echo $settings['final_price_pre_value_text'] ?> </span>
    <span class="final-order-price-value">
        <?php
        if ( $settings['final_price_show_currency_symbol_status'] === 'yes' ) :
            echo wc_price( $order->get_total() );
        else:
            echo number_format( $order->get_total() , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
        endif;
        ?>
    </span>
</div>