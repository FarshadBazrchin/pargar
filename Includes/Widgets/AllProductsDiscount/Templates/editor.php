<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> all-products-discount-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="all-products-discount-value">
        <?php
        if ( $settings['all_products_discount_currency_symbol_status'] === 'yes' ) :
            echo wc_price( 150000 );
        else:
            echo number_format( 150000 , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
        endif;
        ?>
    </span>
</div>