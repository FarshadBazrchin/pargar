<div class="customer-title-wrapper">
    <span class="ic-title"> <?php esc_html_e( 'خریدار' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
</div>

<div class="icw-body">

    <?php

    if ( $invoice_type === 'pre_invoice' ) :
        if ( $address_type === 'billing' ) :
            $first_name = empty( WC()->customer->get_billing_first_name() ) ? '' : WC()->customer->get_billing_first_name();
            $last_name = empty( WC()->customer->get_billing_last_name() ) ? '' : WC()->customer->get_billing_last_name();
        else:
            $first_name = empty( WC()->customer->get_shipping_first_name() ) ? '' : WC()->customer->get_shipping_first_name();
            $last_name = empty( WC()->customer->get_shipping_last_name() ) ? '' : WC()->customer->get_shipping_last_name();
        endif;

    else:

        if ( $address_type === 'billing' ) :
            $first_name = empty( $order->get_billing_first_name() ) ? '' : $order->get_billing_first_name();
            $last_name = empty( $order->get_billing_last_name() ) ? '' : $order->get_billing_last_name();
        else:
            $first_name = empty( $order->get_shipping_first_name() ) ? '' : $order->get_shipping_first_name();
            $last_name = empty( $order->get_shipping_last_name() ) ? '' : $order->get_shipping_last_name();
        endif;

    endif;

    $full_name = $first_name . ' ' . $last_name;

    ?>

    <?php if ( !empty( $first_name ) OR !empty( $last_name ) ) : ?>
    <div class="icw-row">
        <span class="icw-title"> <?php esc_html_e( 'نام کامل :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="icw-value">
            <?php
            echo $full_name;
            ?>
        </span>
    </div>
    <?php endif; ?>

    <div class="icw-row">
        <span class="icw-title"> <?php esc_html_e( 'کد پستی :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="icw-value">
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

    <div class="icw-row">
        <span class="icw-title"> <?php esc_html_e( 'ایمیل :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="icw-value">
            <?php
            if ( $invoice_type === 'pre_invoice' ) :
                echo empty( WC()->customer->get_billing_email() ) ? '-' : WC()->customer->get_billing_email();
            else:
                echo empty( $order->get_billing_email() ) ? '-' : $order->get_billing_email();
            endif;
            ?>
        </span>
    </div>

    <div class="icw-row">
        <span class="icw-title"> <?php esc_html_e( 'تلفن :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="icw-value">
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

    <div class="icw-row">
        <span class="icw-title"> <?php esc_html_e( 'آدرس :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
        <span class="icw-value">
             <?php
                $countries = new WC_Countries();
                $full_address = '';
                 if ( $invoice_type === 'pre_invoice' ) :

                    if ( $address_type === 'billing' ) :
                        $country_states = $countries->get_states( WC()->customer->get_billing_country() );
                        $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];
                        $state_name = $country_states[WC()->customer->get_billing_state()];
                        $city_name = WC()->customer->get_billing_city();
                        $address = WC()->customer->get_billing_address_1();
                    else:
                        $country_states = $countries->get_states( WC()->customer->get_shipping_country() );
                        $state_name = $country_states[WC()->customer->get_shipping_state()];
                        $country_name = $countries->get_countries()[WC()->customer->get_shipping_country()];
                        $city_name = WC()->customer->get_shipping_city();
                        $address = WC()->customer->get_shipping_address_1();
                    endif;

                 else:

                     if ( $address_type === 'billing' ) :
                         $country_states = $countries->get_states( $order->get_billing_country() );
                         $state_name = $country_states[$order->get_billing_state()];
                         $country_name = $countries->get_countries()[$order->get_billing_country()];
                         $city_name = $order->get_billing_city();
                         $address = $order->get_billing_address_1();
                     else:
                         $country_states = $countries->get_states( $order->get_shipping_country() );
                         $state_name = $country_states[$order->get_shipping_state()];
                         $country_name = $countries->get_countries()[$order->get_shipping_country()];
                         $city_name = $order->get_shipping_city();
                         $address = $order->get_shipping_address_1();
                     endif;

                 endif;

                 if ( !empty( $country_name ) ) :
                     $full_address .= "{$country_name}";
                 endif;

                 if ( !empty( $state_name ) ) :
                     if ( !empty( $country_name ) ) :
                         $full_address .= " - {$state_name}";
                     else:
                         $full_address .= "{$state_name}";
                     endif;
                 endif;

                 if ( !empty( $city_name ) ) :
                     if ( !empty( $country_name ) OR !empty( $state_name ) ) :
                        $full_address .= " - {$city_name} ";
                     else:
                         $full_address .= "{$city_name}";
                     endif;
                 endif;

                 if ( !empty( $address ) ) :
                     if ( !empty( $country_name ) OR !empty( $state_name ) OR !empty( $city_name ) ) :
                        $full_address .= " - {$address}";
                     else:
                         $full_address .= "{$address}";
                     endif;
                 endif;

                 if ( !empty( str_replace( [ '-' , ' ' ] , '' , $full_address ) ) ) :
                     echo $full_address;
                 else:
                     echo '-';
                 endif;
             ?>
        </span>
    </div>

</div>