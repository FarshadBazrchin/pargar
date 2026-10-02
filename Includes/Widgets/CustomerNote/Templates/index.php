<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-note-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_note_pre_value_text'] ?> </span>
    <span class="customer-note-value">
        <?php
        if ( empty( $order->get_customer_note() ) ) :
            echo $settings['customer_note_replacement'];
        else:
            echo $order->get_customer_note();
        endif;
        ?>
    </span>
</div>