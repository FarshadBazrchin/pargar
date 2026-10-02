<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> print-date-widget">
    <span class="pre-value-txt"> <?php echo $settings['print_date_pre_value_text'] ?> </span>
    <span class="print-date-value">
        <?php
        $locale = get_locale();
        if ( $settings['print_date_format'] === 'from_wp' ) :
            $settings['print_date_format'] = get_option( 'date_format' );
        endif;

        if ( $locale === 'fa_IR' ) :
            require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
            echo jdate( $settings['print_date_format'] , time() );
        else:
            echo date( $settings['print_date_format'] , time() );
        endif;

        ?>
    </span>
</div>