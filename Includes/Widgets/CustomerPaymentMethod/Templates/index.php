<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-payment-method-widget">
    <span class="pre-value-txt"> <?php echo $settings['payment_method_pre_value_text'] ?> </span>
    <span class="customer-payment-method-value">
        <?php
        if ( empty( $order->get_payment_method_title() ) ) :
            echo $settings['payment_method_replacement'];
        else:
            echo $order->get_payment_method_title();
        endif;
        ?>
    </span>
</div>