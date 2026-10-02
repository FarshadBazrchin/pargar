<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-completed-date-widget">
    <span class="pre-value-txt"> <?php echo $settings['order_completed_date_pre_value_text'] ?> </span>
    <span class="order-completed-date-value">
        <?php
        $locale = get_locale();
        if ( empty( $order->get_date_completed() ) ) :
            echo $settings['order_completed_replacement'];
        else:
            if ( $settings['order_completed_date_format'] === 'from_wp' ) :
                $settings['order_completed_date_format'] = get_option( 'date_format' );
            endif;

            if ( $locale === 'fa_IR' ) :
                require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
                echo jdate( $settings['order_completed_date_format'] , strtotime( $order->get_date_completed() ) );
            else:
                echo date( $settings['order_completed_date_format'] , strtotime( $order->get_date_created() ) );
            endif;
        endif;
        ?>
    </span>
</div>