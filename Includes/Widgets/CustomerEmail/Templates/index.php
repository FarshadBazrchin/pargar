<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-email-widget">
    <span class="pre-value-txt"> <?php echo $settings['email_pre_value_text'] ?> </span>
    <span class="customer-email-value">
        <?php
        if ( empty( $order->get_billing_email() ) ) :
            echo $settings['email_empty_replacement'];
        else:
            echo $order->get_billing_email();
        endif;
        ?>
    </span>
</div>