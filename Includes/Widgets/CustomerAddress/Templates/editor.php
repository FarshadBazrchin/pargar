<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-address-widget">
    <span class="pre-value-txt"> <?php echo $settings['address_pre_text'] ?> </span>
    <span class="customer-address-value">
        <?php
        $spector = $settings['address_text_spector'];
        $address = "ایران{$spector}خوزستان{$spector}اهواز{$spector}خیابان سوم";

        if ( $settings['show_country_status'] !== 'yes' ) :
            $address = str_replace( "ایران{$spector}" , '' , $address );
        endif;

        if ( $settings['show_state_status'] !== 'yes' ) :
            $address = str_replace( "خوزستان{$spector}" , '' , $address );
        endif;

        if ( $settings['show_city_status'] !== 'yes' ) :
            $address = str_replace( "اهواز{$spector}" , '' , $address );
        endif;

        echo $address;
        ?>
    </span>
</div>