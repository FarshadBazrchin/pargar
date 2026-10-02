<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-address-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="market-address-value">
        <?php
        echo pargar_get_setting('market_address', true, '-');
        ?>
    </span>
</div>