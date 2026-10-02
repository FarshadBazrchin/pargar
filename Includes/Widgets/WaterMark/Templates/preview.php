<?php
$editor_mod_status = $settings['editor_mode_show_status'];
$el_class = 'elementor-repeater-item-';
$item_settings = [];
foreach ( $settings['water_mark_font_repeater'] as $item ) :
    if ( $item['status_key'] === 'wc-' . $editor_mod_status ) :
        $el_class .= $item['_id'];
        $item_settings = $item;
        break;
    elseif ( $item['status_key'] === $editor_mod_status ) :
        $el_class .= $item['_id'];
        $item_settings = $item;
        break;
    endif;
endforeach;
?>

<div style="width: fit-content;"
     data-status="<?php echo $editor_mod_status ?>"
     class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> water-mark-parent <?php echo $el_class; ?>">
    <?php if ( $item_settings['water_mark_type'] === 'text' ) : ?>
        <span class="water-mark-value">
        <?php
        if ( $editor_mod_status === 'pre_invoice' ) :
            esc_html_e( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME );
        elseif ( isset( wc_get_order_statuses()[ 'wc-' . $editor_mod_status ] ) ) :
            echo wc_get_order_statuses()[ 'wc-' . $editor_mod_status ];
        elseif ( isset( wc_get_order_statuses()[ $editor_mod_status ] ) ) :
            echo wc_get_order_statuses()[ $editor_mod_status ];
        endif;
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