<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> market-url-widget">

    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>

    <?php $url = pargar_get_setting( 'website_url' , true , '-' ); ?>

    <a <?php if ( $url != '-' ) : ?> href="<?php echo $url;?>" target="_blank" <?php endif; ?> class="market-url-value">
        <?php echo $url; ?>
    </a>

</div>