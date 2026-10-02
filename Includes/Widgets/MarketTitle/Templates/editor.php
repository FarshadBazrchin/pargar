<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-title-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="market-title-value">
        <?php
        echo pargar_get_setting( 'website_title' , true , '-' );
        ?>
    </span>
</div>