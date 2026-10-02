<table class="totals-table" id="totals-table">

    <tbody>
        <tr>
            <td colspan="2" class="title-total-td"> <?php esc_html_e( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME ); ?> </td>
            <td colspan="3">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo wc_price( WC()->cart->get_subtotal() );
                else:
                    echo wc_price( $order->get_subtotal() );
                endif;
                ?>
            </td>

        </tr>

        <tr>
            <td colspan="2" class="title-total-td"> <?php esc_html_e( 'مبلغ تخفیف' , PARGAR_TEXT_DOMAIN_NAME ); ?> </td>
            <td colspan="3">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    if ( empty( WC()->cart->get_total_discount() ) ) :
                        echo wc_price( 0 );
                    else:
                        echo WC()->cart->get_total_discount();
                        if ( wc_coupons_enabled() ) :
                            $coupons = [];
                            foreach ( WC()->cart->get_coupons() as $key => $coupon ) :
                                $coupons[] = $key;
                            endforeach;

                            if ( !empty( $coupons ) ) :
                                echo '<br>';
                                echo esc_html__( 'کوپن ها :' , PARGAR_TEXT_DOMAIN_NAME ) . ' ' . implode( ' , ' , $coupons );
                            endif;
                        endif;
                    endif;
                else:
                    echo wc_price( $order->get_total_discount() );
                    if ( wc_coupons_enabled() ) :
                        if ( !empty( $order->get_coupon_codes() ) ) :
                            echo '<br>';
                            echo esc_html__( 'کوپن ها :' , PARGAR_TEXT_DOMAIN_NAME ) . ' ' . implode( ' , ' , $order->get_coupon_codes() );
                        endif;
                    endif;
                endif;
                ?>
            </td>
        </tr>

        <?php if ( wc_shipping_enabled() ) : ?>
        <tr>
            <td colspan="2" class="title-total-td"> <?php esc_html_e( 'مبلغ حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME ); ?> </td>
            <td colspan="3">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    if ( WC()->cart->needs_shipping() ) :
                        if ( empty( WC()->cart->get_shipping_total() ) ) :
                            echo esc_html__( 'رایگان' , PARGAR_TEXT_DOMAIN_NAME );
                        else:
                            echo wc_price( WC()->cart->get_shipping_total() );
                        endif;
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
            </td>
        </tr>
        <?php endif; ?>

        <?php
        if ( ( $invoice_type === 'pre_invoice' AND !empty( WC()->cart->get_fee_total() ) )
            OR
            ( $invoice_type === 'invoice' AND !empty( $order->get_total_fees() ) ) ) :
        ?>
        <tr>
            <td colspan="2" class="title-total-td"> <?php esc_html_e( 'هزینه ها' , PARGAR_TEXT_DOMAIN_NAME ); ?> </td>
            <td colspan="3">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo wc_price( WC()->cart->get_fee_total() );
                else:
                    echo wc_price( $order->get_total_fees() );
                endif;
                ?>
            </td>
        </tr>

        <?php
            endif;
        ?>

        <tr>
            <td colspan="2" class="title-total-td"> <?php esc_html_e( 'مبلغ نهایی سفارش' , PARGAR_TEXT_DOMAIN_NAME ); ?> </td>
            <td colspan="3">
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo WC()->cart->get_total();
                else:
                    echo wc_price( $order->get_total() );
                endif;
                ?>
            </td>
        </tr>

    </tbody>

</table>