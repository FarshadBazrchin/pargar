<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-address-2-widget">
    <span class="pre-value-txt"> <?php echo $settings['address_pre_text'] ?> </span>
    <span class="customer-address-value">
        <?php
        $spector = $settings['address_text_spector'];
        $countries = new WC_Countries();
        if ( $settings['address_type'] === 'yes' ) :
            $country_states = $countries->get_states( WC()->customer->get_billing_country() );
            $state_name = $country_states[WC()->customer->get_billing_state()];
            $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];
            $city_name = WC()->customer->get_billing_city();
            $full_address = "{$country_name}{$spector}{$state_name}{$spector}{$city_name}{$spector}" . WC()->customer->get_billing_address_2();
        else:
            $country_states = $countries->get_states( WC()->customer->get_shipping_country() );
            $state_name = $country_states[WC()->customer->get_shipping_state()];
            $country_name = $countries->get_countries()[WC()->customer->get_shipping_country()];
            $city_name = WC()->customer->get_shipping_city();
            $full_address = "{$country_name}{$spector}{$state_name}{$spector}{$city_name}{$spector}" . WC()->customer->get_shipping_address_2();
        endif;

        if ( $settings['show_country_status'] !== 'yes' ) :
            $address = str_replace( "{$country_name}{$spector}" , '' , $full_address );
        endif;

        if ( $settings['show_state_status'] !== 'yes' ) :
            $address = str_replace( "{$state_name}{$spector}" , '' , $full_address );
        endif;

        if ( $settings['show_city_status'] !== 'yes' ) :
            $address = str_replace( "{$city_name}{$spector}" , '' , $full_address );
        endif;

        echo $full_address;
        ?>
    </span>
</div>