    <?php foreach ( $settings['list_aa'] as $item ) : ?>

    <tr class="elementor-repeater-item-<?php echo $item['_id']; ?>">
        <td class="thead-instead-tr">
            <div class="holder">
                <?php
                if ( $item['thead_custom_icon_status'] === 'yes' ) :
                    \Elementor\Icons_Manager::render_icon( $item['tab_icon'] , [ 'aria-hidden' => 'true' ] );
                endif;

                if ( $item['thead_custom_title'] === 'yes' ) :
                    echo $item['thead_custom_title_value'];
                else:

                    switch ( $item['list_title'] ) :
                        case 'order_price':
                            esc_html_e( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME );
                            break;
                        case 'discount_price':
                            esc_html_e( 'مبلغ تخفیف' , PARGAR_TEXT_DOMAIN_NAME );
                            break;
                        case 'shipping_price':
                            esc_html_e( 'مبلغ حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME );
                            break;
                        case 'final_price':
                            esc_html_e( 'مبلغ نهایی سفارش' , PARGAR_TEXT_DOMAIN_NAME );
                            break;
                        case 'salary' :
                            esc_html_e( 'هزینه ها' , PARGAR_TEXT_DOMAIN_NAME );
                            break;
                    endswitch;

                endif;
                ?>
            </div>
        </td>
        <td class="tbody-instead-tr">
            <?php
            switch ( $item['list_title'] ) :
                case 'order_price':
                    if ( isset( $_GET['print_pre_invoice'] ) ) :
                        echo wc_price( WC()->cart->get_subtotal() );
                        break;
                    endif;

                    if ( isset( $_GET['print_invoice'] ) ) :
                        echo wc_price( $order->get_subtotal() );
                        break;
                    endif;

                    echo '-';
                    break;
                case 'discount_price':
                    if ( isset( $_GET['print_pre_invoice'] ) ) :
                        echo WC()->cart->get_total_discount();

                        if ( !wc_coupons_enabled() ) :
                            break;
                        endif;

                        $coupons = [];
                        foreach ( WC()->cart->get_coupons() as $key => $coupon ) :
                            $coupons[] = $key;
                        endforeach;

                        if ( !empty( $coupons ) ) :
                            echo '<br>';
                            echo esc_html__( 'کوپن ها :' , PARGAR_TEXT_DOMAIN_NAME ) . ' ' . implode( ' , ' , $coupons );
                        endif;
                        break;
                    endif;

                    if ( isset( $_GET['print_invoice'] ) ) :
                        echo wc_price( $order->get_total_discount() );
                        if ( !wc_coupons_enabled() ) :
                            break;
                        endif;

                        if ( !empty( $order->get_coupon_codes() ) ) :
                            echo '<br>';
                            echo esc_html__( 'کوپن ها :' , PARGAR_TEXT_DOMAIN_NAME ) . ' ' . implode( ' , ' , $order->get_coupon_codes() );
                        endif;
                        break;

                    endif;

                    echo '-';
                    break;
                case 'shipping_price':
                    if ( isset( $_GET['print_pre_invoice'] ) ) :
                        if ( wc_shipping_enabled() AND WC()->cart->needs_shipping() ) :
                            echo wc_price( WC()->cart->get_shipping_total() );
                            break;
                        endif;
                    endif;

                    if ( isset( $_GET['print_invoice'] ) ) :
                        if ( wc_shipping_enabled() ) :
                            echo wc_price( $order->get_shipping_total() );
                            break;
                        endif;
                    endif;

                    echo '-';
                    break;
                case 'final_price':
                    if ( isset( $_GET['print_pre_invoice'] ) ) :
                        echo WC()->cart->get_total();
                        break;
                    endif;

                    if ( isset( $_GET['print_invoice'] ) ) :
                        echo wc_price( $order->get_total() );
                        break;
                    endif;

                    echo '-';
                    break;

                case 'salary' :
                    if ( isset( $_GET['print_pre_invoice'] ) ) :
                        if ( !empty( WC()->cart->get_fee_total() ) ) :
                            echo wc_price( WC()->cart->get_fee_total() );
                            break;
                        endif;
                    endif;

                    if ( isset( $_GET['print_invoice'] ) ) :
                        if ( !empty( wc_price( $order->get_total_fees() ) ) ) :
                            echo wc_price( $order->get_total_fees() );
                            break;
                        endif;
                    endif;

                    echo '-';
                break;
            endswitch;
            ?>
        </td>
    </tr>

    <?php endforeach; ?>