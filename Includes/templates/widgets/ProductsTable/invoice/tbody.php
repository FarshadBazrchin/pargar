<?php
foreach ( $products['products'] as $key => $product) :
?>
	<tr>
		<?php
		foreach ( $settings['list_aa'] as $item ) :
            $order_data = [];
            if ( !empty( $products['order_data'][$product->get_id()] ) ) :
                $order_data = $products['order_data'][$product->get_id()];
            endif;

            $thead_colspan = 1;
            if ( $item['space_filling'] != 'custom' OR $item['space_filling'] != 'auto'):
				$thead_colspan = $item['colspan'];
			endif;

            $text_align = '';
            if ( $item['tbody_inherit_text_align_from_thead'] === 'yes' ) :
                $text_align = "text-align : {$item['text_align']}";
            endif;

			?>
			<td style="<?php echo $text_align; ?>" colspan=" <?php echo $thead_colspan; ?> ">
				<?php
				switch ( $item['list_title'] ) :
					case 'row_number':
						echo $key+1;
					break;
					case 'product_name':
                        if ( !empty( $order_data ) ) :
                            echo $order_data->get_name();
                        else:
                            echo $product->get_name();
                        endif;
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
                            if ( !empty( $order_data ) ):
                                echo $order_data->get_quantity();
                            else:
                                echo 6;
                            endif;
					break;
					case 'product_image':
                        echo "<div style='display: inline-block' class='product-invoice-image-thumb'> {$product->get_image( 'shop_thumbnail' )} </div>";
                    break;

                    case 'item_price' :
                        if ( !empty( $order_data ) ) :
                            echo wc_price( floatval( $order_data->get_subtotal() / $order_data->get_quantity() ) );
                        else:
                            echo wc_price( floatval( $product->get_price() ) );
                        endif;
                    break;

                    case 'item_price_no_symbol' :
                        if ( !empty( $order_data ) ) :
                            echo number_format( floatval( $order_data->get_subtotal() / $order_data->get_quantity() ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        else:
                            echo number_format( floatval( $product->get_price() ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        endif;
                    break;

                    case 'discount' :
                        if ( !empty( $order_data ) ) :
                            $was_on_sale = $order_data->get_meta( 'pargar_was_on_sale' , true );
                            if ( !empty( $was_on_sale ) AND intval( $was_on_sale ) ) :
                                $regular = $order_data->get_meta( 'pargar_regular_price' , true );
                                $discount = $order_data->get_meta( 'pargar_sale_price' , true );
                                echo wc_price( floatval( $regular ) - floatval( $discount ) );
                            else:
                                echo '-';
                            endif;
                        else:
                            if ( $product->is_on_sale() ) :
                                echo wc_price( floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() ) );
                            else:
                                echo '-';
                            endif;
                        endif;
                    break;

                    case 'discount_no_symbol' :
                        if ( !empty( $order_data ) ) :
                            $was_on_sale = $order_data->get_meta( 'pargar_was_on_sale' , true );
                            if ( !empty( $was_on_sale ) AND intval( $was_on_sale ) ) :
                                $discount = $order_data->get_meta( 'pargar_sale_price' , true );
                                $regular = $order_data->get_meta( 'pargar_regular_price' , true );
                                echo number_format( floatval( $regular ) - floatval( $discount ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                            else:
                                echo '-';
                            endif;
                        else:
                            if ( $product->is_on_sale() ) :
                                echo number_format( floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                            else:
                                echo '-';
                            endif;
                        endif;
                    break;

                    case 'item_final_price' :
                        if ( !empty( $order_data ) ) :
                            echo wc_price( floatval( $order_data->get_subtotal() ) );
                        else:
                            echo wc_price( floatval( $product->get_sale_price() ) * 6 );
                        endif;
                    break;

                    case 'item_final_price_no_symbol' :
                        if ( !empty( $order_data ) ) :
                            echo number_format( floatval( $order_data->get_subtotal() ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        else:
                            echo number_format( floatval( $product->get_sale_price() ) * 6 , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        endif;
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
                        if ( isset( $order ) AND !empty( $order ) ) :
                            echo $order->get_item_count();
                        else:
                            echo 12;
                        endif;
                        break;
                    case 'order_items_price' :
                        if ( isset( $order ) AND !empty( $order ) ) :
                            echo wc_price( $order->get_subtotal() );
                        else:
                            echo wc_price( 15800000 );
                        endif;
                        break;
                    case 'order_items_price_no_symbol' :
                        if ( isset( $order ) AND !empty( $order ) ) :
                            echo number_format( $order->get_subtotal() , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        else:
                            echo number_format( 15800000 , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                        endif;
                        break;
                    case 'order_items_discount' :
                        if ( isset( $order ) AND !empty( $order ) ) :
                            $total = 0;
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

                            if ( $total <= 0 ) :
                                echo '-';
                            else:
                                echo wc_price( floatval( $total ) );
                            endif;
                        else:
                            echo wc_price( 150000 );
                        endif;
                        break;
                    case 'order_items_discount_no_symbol' :
                        if ( isset( $order ) AND !empty( $order ) ) :
                            $total = 0;
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

                            if ( $total <= 0 ) :
                                echo '-';
                            else:
                                echo number_format( floatval( $total ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
                            endif;
                        else:
                            echo number_format( 150000 , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );
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
