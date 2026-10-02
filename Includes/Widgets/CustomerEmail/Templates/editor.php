<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> customer-email-widget">
    <span class="pre-value-txt"> <?php echo $settings['email_pre_value_text'] ?> </span>
    <span class="customer-email-value">
        <?php
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) :
            esc_html_e( 'example@gmail.com' );
        endif;
        ?>
    </span>
</div>