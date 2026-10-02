<tr>
	<?php
	if (isset( $settings['list_aa'] ) ):
		foreach ( $settings['list_aa'] as $key => $row ):
			$thead_colspan=1;
			if ($row['space_filling']!='custom' || $row['space_filling']!='auto'):
				$thead_colspan=$row['colspan'];
			endif;
			?>
			<td colspan="<?php echo $thead_colspan ?>"
			    class="elementor-repeater-item-<?php echo $row['_id']; ?>">
				<div class="holder">
					<?php
                    if ( $row['thead_custom_icon_status'] === 'yes' ) :
                        \Elementor\Icons_Manager::render_icon( $row['tab_icon'] , [ 'aria-hidden' => 'true' ] );
                    endif;
					if ($row['thead_custom_title'] == 'yes' ):
						echo $row['thead_custom_title_value'];
						continue;
					endif;

                    switch ( $row['list_title'] ) :
                        case 'row_number':
                            esc_html_e('ردیف',PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'product_name':
                            esc_html_e( 'محصول', PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'product_id':
                        case 'product_sku':
                        case 'product_gtin':
                            esc_html_e( 'کد محصول', PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'product_image':
                            esc_html_e( 'تصویر', PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'item_price' :
                        case 'item_price_no_symbol' :
                            esc_html_e( 'قیمت' , PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'discount' :
                        case 'discount_no_symbol' :
                            esc_html_e( 'مبلغ تخفیف' , PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'product_count':
                            esc_html_e( 'تعداد', PARGAR_TEXT_DOMAIN_NAME );
                        break;
                        case 'item_final_price' :
                        case 'item_final_price_no_symbol' :
                            esc_html_e( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME );
                        break;
                    endswitch;
					?>
				</div>
			</td>
		<?php
		endforeach;
	endif;
	?>
</tr>