<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-fname-widget">
    <span class="pre-value-txt"> <?php echo $settings['fname_pre_value_text'] ?> </span>
    <span class="customer-fname-value">
        <?php
        if ( $settings['name_type'] === 'billing' ) :
            echo empty( WC()->customer->get_billing_first_name() ) ? '-' : WC()->customer->get_billing_first_name();
        else:
            echo empty( WC()->customer->get_shipping_first_name() ) ? '-' : WC()->customer->get_shipping_first_name();
        endif;
        ?>
    </span>
</div>