<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> tax-price-widget">
    <span class="pre-value-txt"> <?php echo $settings['tax_price_pre_value_text'] ?> </span>
    <span class="tax-price-value">
        <?php
        if ( $settings['tax_price_symbol_status'] === 'yes' ) :
            echo wc_price( 11000 );
        else:
            echo number_format( 11000 ,  wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
        endif;
        ?>
    </span>
</div>