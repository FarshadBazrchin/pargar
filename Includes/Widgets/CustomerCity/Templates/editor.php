<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-city-widget">
    <span class="pre-value-txt"> <?php echo $settings['city_name_pre_text'] ?> </span>
    <span class="customer-city-value">
        <?php
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) :
            esc_html_e( "اهواز" , PARGAR_TEXT_DOMAIN_NAME );
        endif;
        ?>
    </span>
</div>