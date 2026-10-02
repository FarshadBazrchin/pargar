<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-country-widget">
    <span class="pre-value-txt"> <?php echo $settings['country_name_pre_text'] ?> </span>
    <span class="customer-country-value">
        <?php
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) :
            esc_html_e( "ایران" , PARGAR_TEXT_DOMAIN_NAME );
        endif;
        ?>
    </span>
</div>