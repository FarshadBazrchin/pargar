<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-meta-widget">
    <span class="pre-value-txt"> <?php echo $settings['meta_order_pre_value'] ?> </span>
    <span class="order-meta-widget-value">
        <?php
        $replacement = $settings['meta_order_empty_replacement'];
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) :
            esc_html_e( 'مقدار متا' , PARGAR_TEXT_DOMAIN_NAME );
            return;
        endif;

        if ( isset( $_GET['print_pre_invoice'] ) ) :
            echo $replacement;
            return;
        endif;

        if ( isset( $_GET['print_invoice'] ) ) :
            if ( !$order->meta_exists( $settings['meta_order_key'] ) ) :
                echo $replacement;
                return;
            endif;

            $value = $order->get_meta( $settings['meta_order_key'] );

            if ( $settings['meta_order_is_price'] !== 'yes' ) :
                echo $value;
                return;
            endif;

            if ( $settings['meta_order_show_currency_symbol'] === 'yes' ) :
                echo wc_price( floatval( $value ) );
                return;
            endif;

            echo number_format( floatval( $value ) , wc_get_price_decimals() , wc_get_price_decimal_separator() , wc_get_price_thousand_separator() );

        endif;

        ?>
    </span>
</div>