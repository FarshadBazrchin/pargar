<div class="sd-data-row">
    <span class="sd-data-value">

         <?php
              if ( function_exists( 'dokan_get_store_info' ) ) :
                  $vendor_name = '';
                  $items = [];
                  if ( $invoice_type === 'pre_invoice' ) :
                      $items = WC()->cart->get_cart();
                  else:
                      $items = $order->get_items();
                  endif;

                  foreach ( $items as $item ) :
                      if ( $invoice_type === 'pre_invoice' ) :
                          $product_id = $item['product_id'];
                      else:
                          $product_id = $item->get_product_id();
                      endif;

                        $seller_id = get_post_field( 'post_author', $product_id );
                        $store_info = dokan_get_store_info( $seller_id );

                        if ( ! empty( $store_info['store_name'] ) ) :
                            $vendor_name = $store_info['store_name'];
                            break;
                        endif;
                  endforeach;

                  if ( !empty( $vendor_name ) ) :
                      echo $vendor_name;
                  else:
                        echo pargar_get_setting( 'website_title' , true , '-' );
                  endif;

                  else:
                        echo pargar_get_setting( 'website_title' , true , '-' );
              endif;

         ?>

    </span>
</div>

<div class="sd-data-row">
    <span class="sd-data-title"> <?php esc_html_e( 'آدرس :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="sd-data-value">
        <?php
        if ( function_exists( 'dokan_get_store_info' ) ) :
            $store_address = '';
            $countries = new WC_Countries();
            $items = [];
            if ( $invoice_type === 'pre_invoice' ) :
                $items = WC()->cart->get_cart();
            else:
                $items = $order->get_items();
            endif;

            foreach ( $items as $item ) :
                if ( $invoice_type === 'pre_invoice' ) :
                    $product_id = $item['product_id'];
                else:
                    $product_id = $item->get_product_id();
                endif;

                $seller_id = get_post_field( 'post_author', $product_id );
                $store_info = dokan_get_store_info( $seller_id );

                if ( !empty( $store_info['address']['country'] ) ) :
                    $country_name = $countries->get_countries()[$store_info['address']['country']];
                    $store_address = "{$country_name} - ";
                endif;

                if ( !empty( $store_info['address']['state'] ) ) :
                    $store_address .= "{$store_info['address']['state']} - ";
                endif;

                if ( !empty( $store_info['address']['city'] ) ) :
                    $store_address .= "{$store_info['address']['state']} - ";
                endif;

                if ( !empty( $store_info['address']['street_1'] ) ) :
                    $store_address .= "{$store_info['address']['street_1']} - ";
                endif;

                if ( !empty( $store_info['address']['street_2'] ) ) :
                    $store_address .= "{$store_info['address']['street_2']}";
                endif;

                if ( !empty( $store_address ) ) :
                    break;
                endif;

            endforeach;

            if ( !empty( $store_address ) ) :
                echo $store_address;
            else:
                echo pargar_get_setting( 'market_address' , true , '-' );
            endif;

        else:
            echo pargar_get_setting( 'market_address' , true , '-' );
        endif;
        ?>
    </span>
</div>

<div class="sd-data-row">
    <span class="sd-data-title"> <?php esc_html_e( 'کدپستی :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="sd-data-value">
        <?php
        if ( function_exists( 'dokan_get_store_info' ) ) :
            $vendor_postcode = '';
            $items = [];
            if ( $invoice_type === 'pre_invoice' ) :
                $items = WC()->cart->get_cart();
            else:
                $items = $order->get_items();
            endif;

            foreach ( $items as $item ) :
                if ( $invoice_type === 'pre_invoice' ) :
                    $product_id = $item['product_id'];
                else:
                    $product_id = $item->get_product_id();
                endif;

                $seller_id = get_post_field( 'post_author', $product_id );
                $store_info = dokan_get_store_info( $seller_id );

                if ( ! empty( $store_info['address']['zip'] ) ) :
                    $vendor_postcode = $store_info['address']['zip'];
                    break;
                endif;

            endforeach;

            if ( !empty( $vendor_postcode ) ) :
                echo $vendor_postcode;
            else:
                echo pargar_get_setting( 'market_postcode' , true , '-' );
            endif;

        else:
            echo pargar_get_setting( 'market_postcode' , true , '-' );
        endif;

        ?>
    </span>
</div>

<div class="sd-data-row">
    <span class="sd-data-title"> <?php esc_html_e( 'تلفن :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="sd-data-value">
        <?php
        if ( function_exists( 'dokan_get_store_info' ) ) :
            $store_phone  = '';
            $items = [];
            if ( $invoice_type === 'pre_invoice' ) :
                $items = WC()->cart->get_cart();
            else:
                $items = $order->get_items();
            endif;

            foreach ( $items as $item ) :
                if ( $invoice_type === 'pre_invoice' ) :
                    $product_id = $item['product_id'];
                else:
                    $product_id = $item->get_product_id();
                endif;

                $seller_id = get_post_field( 'post_author', $product_id );
                $store_info = dokan_get_store_info( $seller_id );

                if ( !empty( $store_info['phone'] ) ) :
                    $store_phone = $store_info['phone'];
                    break;
                endif;

            endforeach;

            if ( !empty( $store_phone ) ) :
                echo $store_phone;
            else:
                echo pargar_get_setting( 'market_phone_number' , true , '-' );
            endif;

        else:
            echo pargar_get_setting( 'market_phone_number' , true , '-' );
        endif;

        ?>
    </span>
</div>

<div class="sd-data-row">
    <span class="sd-data-title"> <?php esc_html_e( 'ایمیل :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="sd-data-value">
        <?php
        if ( function_exists( 'dokan_get_store_info' ) ) :
            $store_email  = '';
            $items = [];
            if ( $invoice_type === 'pre_invoice' ) :
                $items = WC()->cart->get_cart();
            else:
                $items = $order->get_items();
            endif;

            foreach ( $items as $item ) :
                if ( $invoice_type === 'pre_invoice' ) :
                    $product_id = $item['product_id'];
                else:
                    $product_id = $item->get_product_id();
                endif;

                $seller_id = get_post_field( 'post_author', $product_id );
                $store_info = dokan_get_store_info( $seller_id );

                if (!empty($store_info['store_email'])) :
                    $store_email = $store_info['store_email'];
                    break;
                endif;

            endforeach;

            if ( !empty( $store_email ) ) :
                echo $store_email;
            else:
                echo pargar_get_setting( 'website_email' , true , '-' );
            endif;

        else:
            echo pargar_get_setting( 'website_email' , true , '-' );
        endif;

        ?>
    </span>
</div>

<div class="sd-data-row">
    <span class="sd-data-title"> <?php esc_html_e( 'وبسایت :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
    <span class="sd-data-value"> <?php echo pargar_get_setting('website_url', true, '-'); ?> </span>
</div>

