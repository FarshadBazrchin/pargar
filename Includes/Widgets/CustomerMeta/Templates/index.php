<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-meta-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_meta_pre_value_text'] ?> </span>
    <span class="customer-meta-value">
        <?php
        $replace_text = $settings['customer_meta_empty_replacement'];
        if ( !empty( $order->get_customer_id() ) ) :
            $value = get_user_meta( $order->get_customer_id() , $settings['customer_meta_key'] , true );
            if ( !empty( $value ) ) :
                echo $value;
            else:
                $acf_value = null;
                if ( function_exists('get_field') ) :
                    $acf_value = get_field( $settings['customer_meta_key'] , 'user_' . $order->get_customer_id() );
                endif;

                if ( !is_null( $acf_value ) ) :
                    echo $acf_value;
                else:
                    echo $replace_text;
                endif;
            endif;
        endif;

        $guest_user_meta_value = $order->get_meta( $settings['customer_meta_key'] );
        if ( empty( $guest_user ) ) :
            echo $replace_text;
        else:
            echo $guest_user_meta_value;
        endif;
        ?>
    </span>
</div>