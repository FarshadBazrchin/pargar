<?php
$pre_code = pargar_get_setting( 'pre_value_order_code' , true , '' );
?>
<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-code-widget">
    <span class="pre-value-txt"> <?php echo $settings['order_code_pre_value_text'] ?> </span>
    <span class="order-code-value">
        <?php
        if ( $settings['order_code_type'] === 'code' ) :
            echo $pre_code . str_replace('wc_order_','', $order->get_order_key() );
        else:
            echo $pre_code . $order->get_id();
        endif;
        ?>
    </span>
</div>