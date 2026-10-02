<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> tax-price-widget">
    <span class="pre-value-txt"> <?php echo $settings['tax_price_pre_value_text'] ?> </span>
    <span class="tax-price-value">
        <?php
        if ( empty( WC()->cart->get_total_tax() ) ) :
            echo $settings['tax_price_replacement'];
        else:
            if ( $settings['tax_price_symbol_status'] === 'yes' ) :
                echo wc_price( WC()->cart->get_total_tax() );
            else:
                echo number_format( WC()->cart->get_total_tax() ,  wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
            endif;
        endif;
        ?>
    </span>
</div>