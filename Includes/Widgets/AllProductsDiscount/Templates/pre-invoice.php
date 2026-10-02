<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> all-products-discount-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="all-products-discount-value">
        <?php
        $items = WC()->cart->get_cart();
        $total = 0;
        foreach( $items as $values ) :
            $product = $values['data'];
            if ( !$product->is_on_sale() ) :
                continue;
            endif;
            $regular = $product->get_regular_price();
            $discount = $product->get_sale_price();
            $total += floatval( $regular ) - floatval( $discount );
        endforeach;
        if ( floatval( $total ) <= 0 ) :
            echo $settings['value_replacement_text'];
            return;
        endif;

        if ( $settings['all_products_discount_currency_symbol_status'] === 'yes' ) :
            echo wc_price( floatval( $total ) );
        else:
            echo number_format( floatval( $total ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
        endif;
        ?>
    </span>
</div>