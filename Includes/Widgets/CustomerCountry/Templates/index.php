<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-country-widget">
    <span class="pre-value-txt"> <?php echo $settings['country_name_pre_text'] ?> </span>
    <span class="customer-country-value">
        <?php
        $countries = new WC_Countries();
        if ( $settings['address_type'] === 'billing' ) :
            if ( empty( $order->get_billing_country() ) ) :
                $country_name = $settings['country_name_replacement'];
            else:
                $country_name = $countries->get_countries()[$order->get_billing_country()];
            endif;
        else:
            if ( empty( $order->get_shipping_country() ) ) :
                $country_name = $settings['country_name_replacement'];
            else:
                $country_name = $countries->get_countries()[$order->get_shipping_country()];
            endif;
        endif;
        echo $country_name;
        ?>
    </span>
</div>