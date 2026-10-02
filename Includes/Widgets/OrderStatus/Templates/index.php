<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-status-widget">
    <span class="pre-value-txt"> <?php echo $settings['order_status_pre_value_text'] ?> </span>
    <span class="order-status-widget-value">
        <?php
        if ( isset( wc_get_order_statuses()[ 'wc-' . $order->get_status() ] ) ) :
            echo wc_get_order_statuses()['wc-' . $order->get_status()];
        elseif(isset(wc_get_order_statuses()[$order->get_status()])) :
            echo wc_get_order_statuses()[$order->get_status()];
        endif;
        ?>
    </span>
</div>