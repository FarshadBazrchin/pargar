<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-registration-number-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="market-registration-number-value">
        <?php
        echo pargar_get_setting( 'market_registration_code' , true , '-' );
        ?>
    </span>
</div>