<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-city-widget">
    <span class="pre-value-txt"> <?php echo $settings['city_name_pre_text'] ?> </span>
    <span class="customer-city-value">
        <?php
        if ( $settings['address_type'] === 'billing' ) :

            if ( empty( $order->get_billing_city() ) ) :
                echo $settings['city_name_replacement'];
            else:
                echo $order->get_billing_city();
            endif;

        else:
            if ( empty( $order->get_shipping_city() ) ) :
                echo $settings['city_name_replacement'];
            else:
                echo $order->get_shipping_city();
            endif;
        endif;
        ?>
    </span>
</div>