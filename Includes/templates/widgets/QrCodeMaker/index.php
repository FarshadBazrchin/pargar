<?php
$value = '';

if ( is_preview() OR \Elementor\Plugin::$instance->editor->is_edit_mode() ) :
    $value = '123456789';
else:

    if ( $settings['QR_value'] === 'custom' ) :
        $value = $settings['QR_tag_value'];
    endif;

    if ( $settings['QR_value'] === 'order_id' ) :
        $pre_id_value = pargar_get_setting( 'pre_value_order_code' , true , '' );
        $value = $pre_id_value . $order->get_id();
    endif;

    if ( $settings['QR_value'] === 'order_code' ) :
        $pre_id_value = pargar_get_setting( 'pre_value_order_code' , true , '' );
        $value = $pre_id_value . str_replace('wc_order_','', $order->get_order_key() );
    endif;

endif;

?>

<div style="display: flex;" class="qrcode-maker-wrapper">
    <img src="" id="<?php echo $el_id; ?>">
</div>

<script>
    function QR_maker( value , element_id='' ){
        const qrCode = new QRCodeStyling({
            width: '<?php echo $settings['QR_tag_size']['size'] ?>',
            height: '<?php echo $settings['QR_tag_size']['size'] ?>',
            type: "<?php if (isset($settings['QR_icon_img']['url'])): echo pathinfo($settings['QR_icon_img']['url'], PATHINFO_EXTENSION); endif; ?>",
            data: value,
            image: '<?php if ( $settings['QR_has_icon'] == 'yes' AND !empty( $settings['QR_icon_img']['url'] ) ): echo $settings['QR_icon_img']['url']; endif; ?>',
            dotsOptions: {
                type: "<?php echo $settings['QR_middle_dot_type']; ?>",
                <?php if ($settings['QR_body_color_type'] == 'single'): ?>
                color : '<?php echo $settings['QR_body_color1'] ?>',
                <?php else: ?>
                gradient: {
                    type: '<?php echo $settings['QR_body_color_type']; ?>',
                    rotation: <?php echo $settings['QR_body_color_rotation']['size']; ?>,
                    colorStops: [
                        {
                            offset: 0,
                            color: "<?php echo $settings['QR_body_color1']; ?>"
                        },
                        {
                            offset: 1,
                            color: "<?php echo $settings['QR_body_color2']; ?>"
                        }
                    ]
                }
                <?php endif; ?>
            },
            backgroundOptions: {
                color: "transparent",
            },
            <?php if ( $settings['QR_has_icon'] == 'yes' AND !empty( $settings['QR_icon_img']['url'] ) ): ?>
            imageOptions: {
                crossOrigin: "anonymous",
                imageSize: '<?php echo floatval($settings['QR_image_size']['size']/100) ?>' ,
                margin: <?php echo $settings['QR_image_margin']['size']; ?> ,
            },
            <?php endif; ?>
            cornersSquareOptions:{
                type: '<?php echo $settings['QR_corner_type']; ?>',
	            <?php if ($settings['QR_corner_color_type'] == 'single'): ?>
                color : '<?php echo $settings['QR_corner_color1'] ?>',
	            <?php else: ?>
                gradient: {
                    type: '<?php echo $settings['QR_corner_color_type']; ?>',
                    rotation: <?php echo $settings['QR_corner_color_rotation']['size']; ?>,
                    colorStops: [
                        {
                            offset: 0,
                            color: "<?php echo $settings['QR_corner_color1']; ?>"
                        },
                        {
                            offset: 1,
                            color: "<?php echo $settings['QR_corner_color2']; ?>"
                        }
                    ]
                }
	            <?php endif; ?>
            },
            cornersDotOptions:{
                type : '<?php echo $settings['QR_corner_dot_type']; ?>',
	            <?php if ($settings['QR_dot_color_type'] == 'single'): ?>
                color : '<?php echo $settings['QR_dot_color1'] ?>',
	            <?php else: ?>
                gradient: {
                    type: '<?php echo $settings['QR_dot_color_type']; ?>',
                    rotation: <?php echo $settings['QR_dot_color_rotation']['size']; ?>,
                    colorStops: [
                        {
                            offset: 0,
                            color: "<?php echo $settings['QR_dot_color1']; ?>"
                        },
                        {
                            offset: 1,
                            color: "<?php echo $settings['QR_dot_color2']; ?>"
                        }
                    ]
                }
	            <?php endif; ?>
            },
            qrOptions: {
                typeNumber: <?php echo $settings['QR_level']['size']; ?>,
                mode: 'Byte',
                errorCorrectionLevel: 'H'
            },
        });

        qrCode.getRawData("PNG").then( ( buffer ) => {
            buffer = URL.createObjectURL( buffer );
            let elem = document.getElementById( element_id );
            elem.setAttribute( 'src' , buffer )
        });
    }

    QR_maker("<?php echo $value; ?>",'<?php echo $el_id ?>');
</script>