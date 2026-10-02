<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-national-number-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="market-national-number-value">
        <?php
        echo pargar_get_setting( 'market_national_code' , true , '-' );
        ?>
    </span>
</div>