<?php
$value = '';

if ( is_preview() OR \Elementor\Plugin::$instance->editor->is_edit_mode() ) :
    $value = '123456789';
else:
    if ( $settings['BarCode_value'] === 'custom' ) :
        $value = $settings['BarCode_custom_value'];
    endif;

    if ( $settings['BarCode_value'] === 'order_id' ) :
        $pre_id_value = pargar_get_setting( 'pre_value_order_code' , true , '' );
        $value = $pre_id_value . $order->get_id();
    endif;

    if ( $settings['BarCode_value'] === 'order_code' ) :
        $pre_id_value = pargar_get_setting( 'pre_value_order_code' , true , '' );
        $value = $pre_id_value . str_replace('wc_order_','', $order->get_order_key() );
    endif;
endif;

?>

<div style="display: flex" class="barcode-maker-wrapper">
    <img class="magic-factor-barcode-image" id="<?php echo $el_id ?>"/>
</div>

<script>
    JsBarcode("#<?php echo $el_id ?>", "<?php echo $value; ?>",{
        format : "<?php echo $settings['BarCode_type']; ?>",
        <?php if ( $settings['BarCode_sub_text'] != 'yes' ): ?>
        displayValue : false,
        <?php endif; ?>
        width : "<?php echo $settings['BarCode_bar_width']['size']; ?>",
        height : <?php echo $settings['BarCode_bar_height']['size']; ?>,
        background : "<?php echo $settings['BarCode_background']; ?>",
        lineColor : "<?php echo $settings['BarCode_bar_color']; ?>",
	    <?php if ( $settings['BarCode_sub_text'] == 'yes' ): ?>
        fontSize : <?php echo $settings['BarCode_sub_text_size']['size']; ?>,
        textAlign : "<?php echo $settings['BarCode_sub_text_align']; ?>",
        textPosition : "<?php echo $settings['BarCode_sub_text_position']; ?>",
        textMargin : <?php echo $settings['BarCode_sub_text_margin']['size'] ?? 4 ?> ,
        <?php endif; ?>
    });
</script>