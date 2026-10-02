<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> shipping-method-widget">
    <span class="pre-value-txt"> <?php echo $settings['shipping_method_pre_value_text'] ?> </span>
    <span class="shipping-method-value">
        <?php
        if ( empty( $order->get_shipping_method() ) ) :
            echo $settings['shipping_method_replacement'];
        else:
            echo $order->get_shipping_method();
        endif;
        ?>
    </span>
</div>