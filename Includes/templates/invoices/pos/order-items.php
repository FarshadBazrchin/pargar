<?php
        $data = [];
        if ( $invoice_type === 'pre_invoice' ) :
            $data = WC()->cart->get_cart();
        else:
            $data = $order->get_items();
        endif;
        foreach ( $data as $key => $item ) :

            if ( $invoice_type === 'pre_invoice' ) :
                $product = $item['data'];
            else:
                $product = $item->get_product();
            endif;
?>
<div class="pos-order-item">
    <p class="pos-product-title"> <?php echo $product->get_name(); ?> </p>
    <div class="pos-item-data-wrapper">

        <div class="pid-wrapper">
            <span class="pid-title"> <?php esc_html_e( 'قیمت :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
            <span class="pid-value">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo wc_price( floatval( floatval( $item['line_subtotal'] ) / intval( $item['quantity'] ) ) );
                else:
                    echo wc_price( floatval( $item->get_subtotal() / $item->get_quantity() ) );
                endif;
                ?>
            </span>
        </div>

        <div class="pid-wrapper">
            <span class="pid-title"> <?php esc_html_e( 'تعداد :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
            <span class="pid-value">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo intval( $item['quantity'] );
                else:
                    echo $item->get_quantity();
                endif;
                ?>
            </span>
        </div>

        <div class="pid-wrapper">
            <span class="pid-title"> <?php esc_html_e( 'مجموع :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
            <span class="pid-value">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo wc_price( floatval( $item['line_subtotal'] ) );
                else:
                    echo wc_price( floatval( $item->get_subtotal() ) );
                endif;
                ?>
            </span>
        </div>

    </div>
</div>

<?php endforeach; ?>