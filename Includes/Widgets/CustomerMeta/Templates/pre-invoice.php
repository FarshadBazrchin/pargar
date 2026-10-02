<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-meta-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_meta_pre_value_text'] ?> </span>
    <span class="customer-meta-value">
        <?php
        $replace_text = $settings['customer_meta_empty_replacement'];
        if ( is_user_logged_in() ) :
            $value = get_user_meta( get_current_user_id() , $settings['customer_meta_key'] , true );
            if ( !empty( $value ) ) :
                echo $value;
            else:
                $acf_value = null;
                if ( function_exists('get_field') ) :
                    $acf_value = get_field( $settings['customer_meta_key'] , 'user_' . get_current_user_id() );
                endif;

                if ( !is_null( $acf_value ) ) :
                    echo $acf_value;
                else:
                    echo $replace_text;
                endif;
            endif;
        endif;

        $guest_value = WC()->session->get( $settings['customer_meta_key'] );
        if ( !empty( $guest_value ) ) :
            echo $guest_value;
        else:
            if ( !is_null( $customer = WC()->customer ) ) :
                $last_order = WC()->customer->get_last_order();
                if ( $last_order ) :
                    $value = $last_order->get_meta( $settings['customer_meta_key'] );
                    if ( !empty( $value ) ) :
                        echo $value;
                    else:
                        echo $replace_text;
                    endif;
                endif;
            endif;
        endif;
        ?>
    </span>
</div>