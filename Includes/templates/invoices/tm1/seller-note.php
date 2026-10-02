<?php
if ( $invoice_type === 'invoice' ) :
    $notes = wc_get_order_notes([
        'order_id' => $order->get_id() ,
        'type' => 'customer' ,
    ]);
    if ( !empty( $notes ) ) :
        ?>

        <div class="seller-note-wrapper">
            <span class="seller-note-title"> <?php esc_html_e( 'یادداشت فروشنده :' , PARGAR_TEXT_DOMAIN_NAME ); ?> </span>
            <span class="seller-note-value">
                <?php
                    foreach ( $notes as $key => $note ) :
                        if ( $key > 0 ) :
                            echo '<br>';
                        endif;
                        echo $note->content;
                    endforeach; ?>
            </span>
        </div>

    <?php endif; endif; ?>