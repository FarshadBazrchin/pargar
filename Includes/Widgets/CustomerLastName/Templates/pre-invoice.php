<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-Lname-widget">
    <span class="pre-value-txt"> <?php echo $settings['Lname_pre_value_text'] ?> </span>
    <span class="customer-Lname-value">
        <?php
        if ( $settings['name_type'] === 'billing' ) :
            echo empty( WC()->customer->get_billing_last_name() ) ? '-' : WC()->customer->get_billing_last_name();
        else:
            echo empty( WC()->customer->get_shipping_last_name() ) ? '-' : WC()->customer->get_shipping_last_name();
        endif;
        ?>
    </span>
</div>