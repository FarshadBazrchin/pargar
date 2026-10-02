<div class="order-total-row">
    <span class="ot-title"> <?php esc_html_e( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="ot-value">
        <?php
        if ($invoice_type === 'pre_invoice') :
            echo wc_price( WC()->cart->get_subtotal() );
        else:
            echo wc_price( $order->get_subtotal() );
        endif;

        ?>
    </span>
</div>

<?php if ( wc_shipping_enabled() ) : ?>

    <div class="order-total-row">
        <span class="ot-title"> <?php esc_html_e( 'مبلغ حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="ot-value">
            <?php
            if ( $invoice_type === 'pre_invoice' ) :
                if ( WC()->cart->needs_shipping() ) :
                    echo wc_price( WC()->cart->get_shipping_total() );
                else:
                    echo '-';
                endif;
            else:
                if ( !empty( $order->get_shipping_total() ) > 0 ) :
                    echo wc_price( $order->get_shipping_total() );
                else:
                    esc_html_e( 'رایگان' , PARGAR_TEXT_DOMAIN_NAME );
                endif;
            endif;
            ?>
        </span>
    </div>

<?php endif; ?>

<div class="order-total-row">
    <span class="ot-title"> <?php esc_html_e( 'مبلغ نهایی' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="ot-value">
        <?php
        if ( $invoice_type === 'pre_invoice' ) :
            echo WC()->cart->get_total();
        else:
            echo wc_price( $order->get_total() );
        endif;
        ?>
    </span>
</div>