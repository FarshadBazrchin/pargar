<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-state-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_state_pre_value_text'] ?> </span>
    <span class="customer-state-value">
        <?php
        if ( !WC()->cart->needs_shipping() ) :
            echo $settings['customer_state_replacement'];
        else:
            $countries = new WC_Countries();
            if ( $settings['address_type'] === 'yes' ) :
                $country_states = $countries->get_states( WC()->customer->get_billing_country() );
                $state_name = $country_states[WC()->customer->get_billing_state()];
            else:
                $country_states = $countries->get_states( WC()->customer->get_shipping_country() );
                $state_name = $country_states[WC()->customer->get_shipping_state()];
            endif;

            echo empty( $state_name ) ? $settings['customer_state_replacement'] : $state_name;
        endif;
        ?>
    </span>
</div>