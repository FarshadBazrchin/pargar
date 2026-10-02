<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-city-widget">
    <span class="pre-value-txt"> <?php echo $settings['city_name_pre_text'] ?> </span>
    <span class="customer-city-value">
        <?php
        if ( !WC()->cart->needs_shipping() ) :
            echo '-';
        else:

            if ( $settings['address_type'] === 'billing' ) :
                if ( empty( WC()->customer->get_billing_city() ) ) :
                    echo $settings['city_name_replacement'];
                else:
                    echo WC()->customer->get_billing_city();
                endif;
            else:
                if ( empty( WC()->customer->get_shipping_city() ) ) :
                    echo $settings['city_name_replacement'];
                else:
                    echo WC()->customer->get_shipping_city();
                endif;
            endif;

        endif;
        ?>
    </span>
</div>