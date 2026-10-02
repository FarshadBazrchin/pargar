<div class="customer-data-row">
    <span class="cd-title"> <?php esc_html_e( 'نام کامل :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="cd-value">
        <?php
        if ( $invoice_type === 'pre_invoice' ) :

            if ( $address_type === 'billing' ) :
                echo WC()->customer->get_billing_first_name() . ' ' . WC()->customer->get_billing_last_name();
            else:
                echo WC()->customer->get_shipping_first_name() . ' ' . WC()->customer->get_shipping_last_name();
            endif;

        else:

            if ( $address_type === 'billing' ) :
                echo $order->get_billing_first_name() . ' ' .$order->get_billing_last_name();
            else:
                echo $order->get_shipping_first_name() . ' ' .$order->get_shipping_last_name();
            endif;

        endif;
        ?>
    </span>
</div>

<div class="customer-data-row">
    <span class="cd-title"> <?php esc_html_e( 'آدرس :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="cd-value">
        <?php
        $countries = new WC_Countries();
        if ( $invoice_type === 'pre_invoice' ) :

            if ( $address_type === 'billing' ) :
                $country_states = $countries->get_states( WC()->customer->get_billing_country() );
                $state_name = $country_states[WC()->customer->get_billing_state()];
                $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];
                $city_name = WC()->customer->get_billing_city();
                $full_address = "{$country_name} - {$state_name} - {$city_name} - " . WC()->customer->get_billing_address_1();
            else:
                $country_states = $countries->get_states( WC()->customer->get_shipping_country() );
                $state_name = $country_states[WC()->customer->get_shipping_state()];
                $country_name = $countries->get_countries()[WC()->customer->get_shipping_country()];
                $city_name = WC()->customer->get_shipping_city();
                $full_address = "{$country_name} - {$state_name} - {$city_name} - " . WC()->customer->get_shipping_address_1();
            endif;

        else:

            if ( $address_type === 'billing' ) :
                $country_states = $countries->get_states( $order->get_billing_country() );
                $state_name = $country_states[$order->get_billing_state()];
                $country_name = $countries->get_countries()[$order->get_billing_country()];
                $city_name = $order->get_billing_city();
                $full_address = "{$country_name} - {$state_name} - {$city_name} - {$order->get_billing_address_1()}";
            else:
                $country_states = $countries->get_states( $order->get_shipping_country() );
                $state_name = $country_states[$order->get_shipping_state()];
                $country_name = $countries->get_countries()[$order->get_shipping_country()];
                $city_name = $order->get_shipping_city();
                $full_address = "{$country_name} - {$state_name} - {$city_name} - {$order->get_shipping_address_1()}";
            endif;

        endif;

        echo $full_address;
        ?>
    </span>
</div>

<div class="customer-data-row">
    <span class="cd-title"> <?php esc_html_e( 'کدپستی :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="cd-value">
        <?php
        if ( $invoice_type === 'pre_invoice' ) :

            if ( $address_type === 'billing' ) :
                echo empty( WC()->customer->get_billing_postcode() ) ? '-' : WC()->customer->get_billing_postcode();
            else:
                echo empty( WC()->customer->get_shipping_postcode() ) ? '-' : WC()->customer->get_shipping_postcode();
            endif;

        else:

            if ( $address_type === 'billing' ) :
                echo empty( $order->get_billing_postcode() ) ? '-' : $order->get_billing_postcode();
            else:
                echo empty( $order->get_shipping_postcode() ) ? '-' : $order->get_shipping_postcode();
            endif;

        endif;
        ?>
    </span>
</div>

<div class="customer-data-row">
    <span class="cd-title"> <?php esc_html_e( 'تلفن :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="cd-value">
        <?php
        if ( $invoice_type === 'pre_invoice' ) :

            if ( $address_type === 'billing' ) :
                echo empty( WC()->customer->get_billing_phone() ) ? '-' : WC()->customer->get_billing_phone();
            else:
                echo empty( WC()->customer->get_shipping_phone() ) ? '-' : WC()->customer->get_shipping_phone();
            endif;

        else:

            if ( $address_type === 'billing' ) :
                echo empty( $order->get_billing_phone() ) ? '-' : $order->get_billing_phone();
            else:
                echo empty( $order->get_shipping_phone() ) ? '-' : $order->get_shipping_phone();
            endif;

        endif;
        ?>
    </span>
</div>