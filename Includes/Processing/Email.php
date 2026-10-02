<?php

namespace Pargar\Includes\Processing;

/**
 * Class Email
 *
 * The Email class is responsible for adding custom elements, such as buttons or links,
 * to WooCommerce order-related emails using hooks and filters.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class Email
{
    /**
     * Holds the Singleton instance
     *
     * @var self|null $_instance Stores the instance of the class
     */
    private static $_instance = null;

    /**
     * Creates or retrieves the Singleton instance of the class
     *
     * @return self
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor method
     *
     * This method adds a filter to WooCommerce emails. The filter hooks into
     * WooCommerce's email system to allow the addition of custom metadata
     * (such as custom buttons or links) in order-related emails.
     */
    public function __construct()
    {
        add_filter('woocommerce_email_order_meta', array( $this , 'addCustomMetaEmail' ), 10, 3);
    }


    /**
     * Add custom metadata to WooCommerce order emails
     *
     * This method generates a button with a link to the invoice page. The button
     * is styled using inline CSS for appearance. The link is specific to the
     * order and is dynamically generated based on the order ID.
     *
     * @since 1.0.0
     * @param WC_Order $order The WooCommerce order object
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function addCustomMetaEmail($order, $sent_to_admin, $plain_text)
    {
        if (pargar_get_setting('include_invoice_link_in_email', true, 'off') == "on") {
            $link = pargar_invoice_page_url_short($order->get_id());
            echo '<a href="' . $link . '" style="display: inline-block; background-color: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">مشاهده فاکتور</a>';
        }
    }
}