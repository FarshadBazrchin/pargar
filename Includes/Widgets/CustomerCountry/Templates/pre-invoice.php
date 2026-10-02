<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-country-widget">
    <span class="pre-value-txt"> <?php echo $settings['country_name_pre_text'] ?> </span>
    <span class="customer-country-value">
        <?php
        if ( !WC()->cart->needs_shipping() ) :
            $country_name = $settings['country_name_replacement'];
        else:
            $countries = new WC_Countries();
            if ( $settings['address_type'] === 'billing' ) :
                if ( empty( WC()->customer->get_billing_country() ) ) :
                    $country_name = $settings['country_name_replacement'];
                else:
                    $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];
                endif;
            else:
                if ( empty( WC()->customer->get_shipping_country() ) ) :
                    $country_name = $settings['country_name_replacement'];
                else:
                    $country_name = $countries->get_countries()[WC()->customer->get_shipping_country()];
                endif;
            endif;
        endif;

        echo $country_name;
        ?>
    </span>
</div>