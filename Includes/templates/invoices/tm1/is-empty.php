<div class="is-empty-wrapper">

    <img class="empty-cart-image"
         width="210"
         height="210"
         src="<?php echo PARGAR_URL . '/assets/image/empty-cart.png' ?>">

    <h3 class="cart-empty-text"> <?php esc_html_e( 'سبد خرید شما خالی است' , PARGAR_TEXT_DOMAIN_NAME ); ?> </h3>

    <h4 class="cart-empty-subtext"> <?php esc_html_e( 'برای نمایش پیش فاکتور، حداقل یک محصول باید در سبد خرید افزوده شده باشد' , PARGAR_TEXT_DOMAIN_NAME ); ?> </h4>

    <a href="<?php echo get_site_url(); ?>" class="back-to-main-btn">
        <?php esc_html_e( 'بازگشت به صفحه اصلی' , PARGAR_TEXT_DOMAIN_NAME ); ?>
    </a>

</div>