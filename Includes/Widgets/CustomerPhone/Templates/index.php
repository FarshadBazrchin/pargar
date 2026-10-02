<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-phone-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_phone_pre_value_text'] ?> </span>
    <span style="direction: ltr;display: inline-block" class="customer-phone-value">
        <?php
        if ( $settings['phone_type'] === 'billing' ) :
            echo empty( $order->get_billing_phone() ) ? $settings['customer_phone_replacement'] : $order->get_billing_phone();
        else:
            echo empty( $order->get_shipping_phone() ) ? $settings['customer_phone_replacement'] : $order->get_shipping_phone();
        endif;
        ?>
    </span>
</div>