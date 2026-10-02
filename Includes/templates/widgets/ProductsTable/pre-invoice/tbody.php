<?php
foreach ( $data as $key => $row ) :
    $product = $row['data'];
?>
	<tr>
		<?php
		foreach ( $settings['list_aa'] as $item ) :
			$thead_colspan = 1;
			if ( $item['space_filling'] != 'custom' OR $item['space_filling'] != 'auto'):
				$thead_colspan = $item['colspan'];
			endif;

            $text_align = '';
            if ( $item['tbody_inherit_text_align_from_thead'] === 'yes' ) :
                $text_align = "text-align : {$item['text_align']}";
            endif;

			?>
			<td style="<?php echo $text_align; ?>" colspan="<?php echo $thead_colspan; ?>">
				<?php
				switch ( $item['list_title'] ) :
					case 'row_number':
						echo $key+1;
					break;
					case 'product_name':
                        echo $product->get_name();
					break;
                    case 'product_id':
                        if ( $item['product_id_sku_gtin_print_type'] === 'code_only' ) :
                            echo empty( $product->get_id() ) ? '-' : $product->get_id();
                        elseif ( $item['product_id_sku_gtin_print_type'] === 'barcode' ):
                            echo '<img id="ot-id-barcode-img-'. $product->get_id() .'" data-id="'. $product->get_id() .'" class="ot-barcode-img">';
                        else:
                            echo '<img style="width:80%;" id="ot-id-qrcode-img-'. $product->get_id() .'" data-id="'. $product->get_id() .'" class="ot-qrcode-img">';
                            echo $product->get_id();
                        endif;
                        break;
                    case 'product_sku':
                        if ( empty( $product->get_sku() ) ) :
                            echo '-';
                            break;
                        endif;

                        if ( $item['product_id_sku_gtin_print_type'] === 'only_code' ) :
                            echo empty( $product->get_sku() ) ? '-' : $product->get_sku();
                        elseif ( $item['product_id_sku_gtin_print_type'] === 'barcode' ) :
                            echo '<img id="ot-sku-barcode-img-'. $product->get_id() .'" data-id="'. $product->get_sku() .'" class="ot-barcode-img">';
                        else:
                            echo '<img style="width:80%;" id="ot-sku-qrcode-img-'. $product->get_id() .'" data-id="'. $product->get_sku() .'" class="ot-qrcode-img">';
                            echo $product->get_sku();
                        endif;

                        break;
                    case 'product_gtin':
                        if ( empty( $product->get_global_unique_id() ) ) :
                            echo '-';
                            break;
                        endif;

                        if ( $item['product_id_sku_gtin_print_type'] === 'only_code' ) :
                            echo $product->get_global_unique_id();
                        elseif ( $item['product_id_sku_gtin_print_type'] === 'barcode' ) :
                            echo '<img id="ot-gtin-barcode-img-'. $product->get_id() .'" data-id="'. $product->get_global_unique_id() .'" class="ot-barcode-img">';
                        else:
                            echo '<img style="width:80%;" id="ot-gtin-qrcode-img-'. $product->get_id() .'" data-id="'. $product->get_global_unique_id() .'" class="ot-qrcode-img">';
                            echo $product->get_global_unique_id();
                        endif;
                        break;
					case 'product_count':
                        echo $row['quantity'];
					break;
					case 'product_image':
                        echo "<div style='display: inline-block' class='product-invoice-image-thumb'> {$product->get_image( 'shop_thumbnail' )} </div>";
                    break;

                    case 'item_price' :
                        echo wc_price( floatval( floatval( $row['line_subtotal'] ) / intval( $row['quantity'] ) ) );
                    break;

                    case 'item_price_no_symbol' :
                        echo number_format( floatval( floatval( $row['line_subtotal'] ) / intval( $row['quantity'] ) ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                    break;

                    case 'discount' :
                        if ( $product->is_on_sale() ) :
                            echo wc_price( floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() ) );
                        else:
                            echo '-';
                        endif;
                    break;

                    case 'discount_no_symbol' :
                        if ( $product->is_on_sale() ) :
                            echo number_format( floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        else:
                            echo '-';
                        endif;
                    break;

                    case 'item_final_price' :
                        echo wc_price( floatval( $row['line_subtotal'] ) );
                    break;

                    case 'item_final_price_no_symbol' :
                        echo number_format( floatval( $row['line_subtotal'] ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                    break;
				endswitch;
			?>
			</td>
		<?php endforeach; ?>
	</tr>
<?php endforeach; ?>

<?php if ( $settings['include_tfoot'] == 'yes' ): ?>
<tr class="tfoot">
    <?php
    if (isset( $settings['tfoot_list'] ) ):
        foreach ( $settings['tfoot_list'] as $key => $row ):
            $tfoot_colspan = 1;
            if ( $row['tfoot_space_filling'] != 'custom' OR $row['tfoot_space_filling'] != 'auto'):
                $tfoot_colspan = $row['colspan'];
            endif;
            ?>
            <td colspan=" <?php echo $tfoot_colspan ?> "
                class="elementor-repeater-item-<?php echo $row['_id']; ?>">
                <?php
                switch ( $row['tfoot_list_title'] ) :
                    case 'custom' :
                        echo $row['tfoot_custom_title_value'];
                        break;
                    case 'order_items_count' :
                        echo WC()->cart->get_cart_contents_count();
                        break;
                    case 'order_items_price' :
                        echo wc_price( WC()->cart->get_subtotal() );
                        break;
                    case 'order_items_price_no_symbol' :
                        echo number_format( WC()->cart->get_subtotal() , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        break;
                    case 'order_items_discount' :
                        $total = 0;
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

                        if ( $total <= 0 ) :
                            echo '-';
                        else:
                            echo wc_price( floatval( $total ) );
                        endif;
                        break;
                    case 'order_items_discount_no_symbol' :
                        $total = 0;
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

                        if ( $total <= 0 ) :
                            echo '-';
                        else:
                            echo number_format( floatval( $total ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        endif;
                        break;
                endswitch;
                ?>
            </td>
        <?php
        endforeach;
    endif;
    ?>
</tr>
<?php endif; ?>
