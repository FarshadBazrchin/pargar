<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> all-products-count-widget">
    <span class="pre-value-txt"> <?php echo $settings['pre_value_text'] ?> </span>
    <span class="all-products-count-value">
        <?php
        echo $order->get_item_count();
        ?>
    </span>
</div>