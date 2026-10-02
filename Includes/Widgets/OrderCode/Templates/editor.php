<?php
$pre_code = pargar_get_setting( 'pre_value_order_code' , true , '' );
?>
<div class="<?php echo PARGAR_UNIQUE_TEMPLATE_NAEM?> order-code-widget">
    <span class="pre-value-txt"> <?php echo $settings['order_code_pre_value_text'] ?> </span>
    <span class="order-code-value">
        <?php
        echo $pre_code . '2481898';
        ?>
    </span>
</div>