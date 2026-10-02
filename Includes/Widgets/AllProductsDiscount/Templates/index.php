<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> all-products-discount-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="all-products-discount-value">
        <?php
        $total = 0;
        foreach ( $order->get_items() as $item ) :
            $was_on_sale = $item->get_meta( 'pargar_was_on_sale' );
            $discount = 0;
            $regular = 0;
            if ( $was_on_sale ) :
                $regular = $item->get_meta( 'pargar_regular_price' );
                $discount = $item->get_meta( 'pargar_sale_price' );
            endif;
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