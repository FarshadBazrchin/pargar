<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-post-code-widget">
    <span class="pre-value-txt"> <?php echo $settings['customer_post_code_pre_value_text'] ?> </span>
    <span class="customer-post-code-value">
        <?php
        if ( $settings['address_type'] === 'billing' ) :
            echo empty( WC()->customer->get_billing_postcode() ) ? $settings['customer_post_code_replacement'] : WC()->customer->get_billing_postcode();
        else:
            echo empty( WC()->customer->get_shipping_postcode() ) ? $settings['customer_post_code_replacement'] : WC()->customer->get_shipping_postcode();
        endif;
        ?>
    </span>
</div>