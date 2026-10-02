<?php

namespace Pargar\Includes\Processing;

/**
 * The Sms class is designed to handle WooCommerce SMS customizations.
 * It integrates custom shortcodes into SMS templates and dynamically replaces
 * placeholders with actual content, like invoice links.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class Sms
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
     * Constructor method.
     *
     * This method adds two filters to customize WooCommerce SMS functionality:
     * - `pwoosms_shortcodes_list`: Allows custom shortcodes to be added.
     * - `pwoosms_order_sms_body_before_replace`: Replaces placeholders in SMS content.
     */

    public function __construct()
    {
        add_filter('pwoosms_shortcodes_list', array( $this, 'detailsShortCode') );
        add_filter('pwoosms_order_sms_body_before_replace', array( $this, 'replaceSms' ),10,5);
    }

    /**
     * Add custom shortcodes to the WooCommerce SMS system.
     *
     * This method integrates additional shortcodes, like `{invoice_url}`, into
     * the SMS templates. These shortcodes can be utilized in various tabs based
     * on conditions, such as the `super_admin` or `buyer` tabs.
     *
     * @since 1.0.0
     * @param string $shortcodes Existing shortcodes provided by the SMS system.
     * @return string The updated shortcodes list with custom additions.
     */
    public function detailsShortCode( $shortcodes )
    {
        if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], array( 'super_admin', 'buyer' ) ) ) {
            $shortcodes .= sprintf('
				<strong>' . esc_html__( "شورتکدهای اختصاصی پرگار :" , PARGAR_TEXT_DOMAIN_NAME ) . '</strong><br>
				<code>{invoice_url}</code> = لینک فاکتور
				');
        }
        return $shortcodes;
    }

    /**
     * Replace placeholders in the SMS content.
     *
     * This method dynamically replaces the `{invoice_url}` placeholder in the SMS
     * body with the actual invoice link for the corresponding order.
     *
     * @since 1.0.0
     * @param string $content The SMS content before replacement.
     * @param string $key_tag The key tag used in the placeholder.
     * @param string $value_tag The value tag to replace the key tag.
     * @param int $order_id The ID of the WooCommerce order.
     * @param WC_Order $order The WooCommerce order object.
     * @return string The SMS content after placeholder replacement.
     */
    public function replaceSms( $content, $key_tag, $value_tag, $order_id, $order )
    {
        $link = pargar_invoice_page_url_short( $order_id );
        return str_ireplace( "{invoice_url}", $link, $content );
    }

}