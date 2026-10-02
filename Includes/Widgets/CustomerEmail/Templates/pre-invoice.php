<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-email-widget">
    <span class="pre-value-txt"> <?php echo $settings['email_pre_value_text'] ?> </span>
    <span class="customer-email-value">
        <?php
        if ( isset( $_GET['print_pre_invoice'] ) ) :
            if ( empty( WC()->customer->get_billing_email() ) ) :
                echo $settings['email_empty_replacement'];
            else:
                echo WC()->customer->get_billing_email();
            endif;
        endif;
        ?>
    </span>
</div>