<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-fee-widget">
    <span class="pre-value-txt"> <?php echo $settings['order_fee_pre_value_text'] ?> </span>
    <span class="order-fee-widget-value">
        <?php
        if ( $settings['order_fee_currency_symbol_status'] === 'yes' ) :
            echo wc_price( 2850 );
        else:
            echo number_format( 2850 , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
        endif;
        ?>
    </span>
</div>