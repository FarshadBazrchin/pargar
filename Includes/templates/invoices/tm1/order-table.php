<?php
$include_id = pargar_get_setting( 'invoice_include_product_id' , true , 'on' );
$include_image = pargar_get_setting( 'invoice_product_image' , true , 'off' );
$include_discount = pargar_get_setting( 'invoice_product_discount' , true , 'off' );
?>
<table class="order-table" id="order-table">

    <thead>
        <tr>
            <td class="row-counter-td">
                <?php esc_html_e( 'ردیف' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

            <?php if ( $include_id == 'on' ) : ?>
            <td class="row-id-td" colspan="1">
                <?php esc_html_e( 'شناسه' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>
            <?php endif; ?>

            <?php if ( $include_image == 'on' ) : ?>
            <td class="product-image-td" colspan="1">
                <?php esc_html_e( 'تصویر' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>
            <?php endif; ?>

            <td class="product-name-col-td" colspan="3">
                <?php esc_html_e( 'محصول' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

            <td colspan="1">
                <?php esc_html_e( 'مبلغ' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

            <?php if ( $include_discount == 'on' ) : ?>
            <td colspan="1">
                <?php esc_html_e( 'تخفیف' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>
            <?php endif; ?>

            <td class="product-count-td">
                <?php esc_html_e( 'تعداد' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

            <td colspan="1">
                <?php esc_html_e( 'مجموع' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

        </tr>
    </thead>

    <tbody>
        <?php
        $data = [];
        if ( $invoice_type === 'pre_invoice' ) :
            $data = WC()->cart->get_cart();
        else:
            $data = $order->get_items();
        endif;

        $counter = 1;
        foreach ( $data as $key => $item ) :

            if ( $invoice_type === 'pre_invoice' ) :
                $product = $item['data'];
            else:
                $product = $item->get_product();
            endif;

            ?>

            <tr>

                <td> <?php echo $counter ?> </td>

                <?php if ( $include_id == 'on' ) : ?>
                <td>
                    <?php
                    $id_type = pargar_get_setting( 'invoice_order_code_type' , true , 'id' );
                    if ( $id_type === 'id' ) :
                        echo empty( $product->get_id() ) ? '-' : $product->get_id();
                    elseif ( $id_type === 'sku' ):
                        echo empty( $product->get_sku() ) ? '-' : $product->get_sku();
                    elseif ( $id_type === 'gtin' ):
                        echo empty( $product->get_global_unique_id() ) ? '-' : $product->get_global_unique_id();
                    endif;
                    ?>
                </td>
                <?php endif; ?>

                <?php if ( $include_image == 'on' ) : ?>
                <td>
                    <div class="product-invoice-image-thumb">
                        <?php
                        echo $product->get_image( 'shop_thumbnail' );
                        ?>
                    </div>
                </td>
                <?php endif; ?>

                <td colspan="3">
                    <?php
                    echo $product->get_name();
                    ?>
                </td>

                <td>
                    <?php
                    if ( $invoice_type === 'pre_invoice' ) :
                        echo wc_price( floatval( floatval( $item['line_subtotal'] ) / intval( $item['quantity'] ) ) );
                    else:
                        echo wc_price( floatval( $item->get_subtotal() / $item->get_quantity() ) );
                    endif;
                    ?>
                </td>

                <?php if ( $include_discount === 'on' ) : ?>
                <td>
                    <?php
                    if ( $invoice_type === 'pre_invoice' ) :
                        if ( $product->is_on_sale() ) :
                            echo wc_price( floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() ) );
                        else:
                            echo '-';
                        endif;
                    else:
                        $was_on_sale = $item->get_meta( 'pargar_was_on_sale' , true );
                        if ( !empty( $was_on_sale ) AND intval( $was_on_sale ) ) :
                            $regular = $item->get_meta( 'pargar_regular_price' , true );
                            $discount = $item->get_meta( 'pargar_sale_price' , true );
                            echo wc_price( floatval( $regular ) - floatval( $discount ) );
                        else:
                            echo '-';
                        endif;
                    endif;
                    ?>
                </td>

                <?php endif; ?>

                <td>
                    <?php
                    if ( $invoice_type === 'pre_invoice' ) :
                        echo intval( $item['quantity'] );
                    else:
                        echo $item->get_quantity();
                    endif;
                    ?>
                </td>

                <td>
                    <?php
                    if ( $invoice_type === 'pre_invoice' ) :
                        echo wc_price( floatval( $item['line_subtotal'] ) );
                    else:
                        echo wc_price( floatval( $item->get_subtotal() ) );
                    endif;
                    ?>
                </td>

            </tr>

        <?php $counter++; endforeach; ?>

        <tr class="tfoot-tr">
            <?php
            $cs = 5;
            if ( $include_id == 'on' ) :
                $cs+=1;
            endif;

            if ( $include_image == 'on' ) :
                $cs+=1;
            endif;

            ?>
            <td class="tfoot-all-text-td" colspan="<?php echo $cs?>">
                <?php esc_html_e( 'کل' , PARGAR_TEXT_DOMAIN_NAME ); ?>
            </td>

            <?php if ( $include_discount == 'on' ) : ?>
            <td>
                <?php
                $total = 0;
                if ( $invoice_type === 'pre_invoice' ) :
                    foreach ( $data as $_product ) :
                        $_product = $_product['data'];
                        $discount = 0;
                        $regular = 0;
                        if ( !$_product->is_on_sale() ) :
                            continue;
                        endif;
                        $regular = $_product->get_regular_price();
                        $discount = $_product->get_sale_price();
                        $total += floatval( $regular ) - floatval( $discount );
                    endforeach;
                else:
                    foreach ( $order->get_items() as $item ) :
                        $discount = 0;
                        $regular = 0;
                        $was_on_sale = $item->get_meta( 'pargar_was_on_sale' );
                        if ( $was_on_sale ) :
                            $regular = $item->get_meta( 'pargar_regular_price' );
                            $discount = $item->get_meta( 'pargar_sale_price' );
                        endif;
                        $total += floatval( $regular ) - floatval( $discount );
                    endforeach;
                endif;
                if ( $total <= 0 ) :
                    echo '-';
                else:
                    echo wc_price( floatval( $total ) );
                endif;
                ?>
            </td>
            <?php endif; ?>

            <td>
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo WC()->cart->get_cart_contents_count();
                else:
                    echo $order->get_item_count();
                endif;
                ?>
            </td>

            <td>
                <?php
                if ( $invoice_type === 'pre_invoice' ) :
                    echo wc_price( WC()->cart->get_subtotal() );
                else:
                    echo wc_price( $order->get_subtotal() );
                endif;
                ?>
            </td>

        </tr>

    </tbody>

</table>