<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-phone-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span style="display: inline-block; direction: ltr" class="market-phone-value">
        <?php
        echo pargar_get_setting( 'market_phone_number' , true , '-' );
        ?>
    </span>
</div>