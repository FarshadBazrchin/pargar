<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-economical-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="market-economical-value">
        <?php
        echo pargar_get_setting( 'market_economical_code' , true , '-' );
        ?>
    </span>
</div>