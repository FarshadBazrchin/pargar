<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> seller-note-widget">
    <span class="pre-value-txt"> <?php echo $settings['seller_note_pre_value_text'] ?> </span>
    <span class="seller-note-value">
        <?php
        if ( empty( $notes ) ) :
            echo $settings['seller_note_replacement'];
        else:
            foreach ( $notes as $key => $note ) :
                if ( $key > 0 ) :
                    echo '<br>';
                endif;
                echo $note->content;

            endforeach;
        endif;
        ?>
    </span>
</div>