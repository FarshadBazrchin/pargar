<?php
$el_class = 'elementor-repeater-item-';
$item_settings = [];
foreach ( $settings['water_mark_font_repeater'] as $item ) :
    if ( $item['status_key'] === 'pre_invoice' ) :
        $el_class .= $item['_id'];
        $item_settings = $item;
        break;
    endif;
endforeach;
?>


<?php if ( !empty( $item_settings ) AND $item_settings['show_wc_status'] === 'yes' ) : ?>
    <div style="width: fit-content;" data-status="pre_invoice"
         class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> water-mark-parent <?php echo $el_class ?>">
        <?php if ( $item_settings['water_mark_type'] === 'text' ) : ?>
            <span class="water-mark-value">
                <?php
                    esc_html_e( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME );
                ?>
            </span>
        <?php else: ?>
            <?php if ( !empty( $item_settings['water_mark_image']['url'] ) ) : ?>
                <img    width="<?php echo $item_settings['water_mark_image_size']['width'] ?? 200 ?>"
                        height="<?php echo $item_settings['water_mark_image_size']['height'] ?? 150 ?>"
                        class="water-mark-image"
                        src="<?php echo $item_settings['water_mark_image']['url'] ?? '' ?>">
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>