<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-full-name-widget">
    <span class="pre-value-txt"> <?php echo $settings['full_name_pre_value_text'] ?> </span>
    <span class="customer-full-name-value">
        <?php
        if ( $settings['name_type'] === 'billing' ) :
            $first_name = empty( $order->get_billing_first_name() ) ? '' : $order->get_billing_first_name();
            $last_name = empty( $order->get_billing_last_name() ) ? '' : $order->get_billing_last_name();
        else:
            $first_name = empty( $order->get_shipping_first_name() ) ? '' : $order->get_shipping_first_name();
            $last_name = empty( $order->get_shipping_last_name() ) ? '' : $order->get_shipping_last_name();
        endif;

        echo $first_name . ' ' . $last_name;
        ?>
    </span>
</div>