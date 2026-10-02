<?php

namespace Pargar\Includes\Processing;

/**
 * Handles custom WooCommerce admin order actions.
 *
 * This class adds a custom button with an image to the WooCommerce admin
 * order actions, allowing for additional functionalities like generating
 * invoices. It uses the Singleton pattern to ensure a single instance.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class WoocommerceOrderAction
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
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor method called when an instance is created.
     * Adds a custom action to WooCommerce's hooks to insert custom buttons in the admin order actions area.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('woocommerce_admin_order_actions_end', array($this, 'addCustomButtonImage'));


        add_filter('bulk_actions-woocommerce_page_wc-orders', array($this, 'addBulkActions'));

        add_filter('bulk_actions-edit-shop_order', array($this, 'addBulkActions'));

        add_action('handle_bulk_actions-edit-shop_order', array($this, 'HandleBulkActions'), 10, 3);

        add_action('handle_bulk_actions-woocommerce_page_wc-orders', array($this, 'HandleBulkActions'), 10, 3);

        add_action('add_meta_boxes', array($this, 'addMetaBoxes'));

        add_filter('woocommerce_my_account_my_orders_actions', array($this, 'addMyAccountOrdersAction'), 10, 2);


    }

    /**
     *
     * @param $actions
     * @param $order
     * @return mixed
     * @since 1.0.0
     */

    public function addMyAccountOrdersAction($actions, $order)
    {
        if (pargar_get_setting('print_invoice_for_user_panel', true, "off") == "on") {
            $actions["print_invoice"] = array(
                'url' => pargar_invoice_page_url($order->get_id()),
                'name' => 'فاکتور',
            );
        }
        return $actions;
    }

    /**
     * Adds custom bulk actions to the WooCommerce orders page.
     *
     * This method defines additional options such as:
     * - Print address labels
     * - Print product labels
     *
     * These will appear in the bulk actions dropdown in the admin order list.
     *
     * @return array The modified array of bulk action labels.
     * @since 1.0.0
     */
    public function addBulkActions()
    {
        $bulk_actions['print_address_label'] = 'چاپ برچسب آدرس';
        $bulk_actions['print_product_label'] = 'چاپ برچسب محصول';
        return $bulk_actions;
    }

    /**
     * Handles custom bulk actions triggered from the orders page.
     *
     * Based on the selected bulk action (`print_address_label` or `print_product_label`),
     * it generates a redirect URL to the appropriate invoice page, including selected order IDs.
     *
     * @param string $redirect_to The URL to redirect to after action completion.
     * @param string $action The action name triggered.
     * @param array $post_ids Array of selected WooCommerce order IDs.
     * @return string Modified redirect URL for processing invoice printing.
     * @since 1.0.0
     */
    public function HandleBulkActions($redirect_to, $action, $post_ids)
    {
        if ($action === 'print_address_label') {
            $order_ids = implode(',', $post_ids);
            $redirect_to = pargar_create_invoice_url(array('print_address_label' => 'yes', 'orders_id' => $order_ids));
        }
        if ($action === 'print_product_label') {
            $order_ids = implode(',', $post_ids);
            $redirect_to = pargar_create_invoice_url(array('print_product_label' => 'yes', 'orders_id' => $order_ids));
        }
        return $redirect_to;
    }

    /**
     * Adds a custom button with an image to the WooCommerce admin order actions.
     *
     * This function dynamically generates and displays a custom button in the WooCommerce
     * admin interface. The button includes a clickable image and a tooltip (data-tip) for additional context.
     * It is conditionally displayed based on user access level and links to an invoice page.
     *
     * @param object $order The current WooCommerce order object, used to fetch the order ID.
     * @since 1.0.0
     */
    public function addCustomButtonImage($order)
    {
        $list_button = $this->listBtn($order->get_id());
        foreach ($list_button as $key => $button) {
            if (AccessLevel::CheckButtonAdminWc($key)) {
                printf('<a href="%s" class="button tips custom-class" target="_blank" data-tip="%s">%s</a>', esc_url($button['url']), $button['name'], $button['title']);
            }
        }
    }

    /**
     * Builds a list of custom buttons to be shown in the order interface.
     *
     * Generates buttons for invoice actions like packing slips, product labels, and address labels.
     * Each button includes a name, link URL, and a corresponding icon image.
     * Only available if the current user has permission based on access level settings.
     *
     * @param int $order_id The ID of the WooCommerce order.
     * @return array Associative array of button definitions.
     * @since  1.0.0
     */

    private function listBtn($order_id)
    {
        $list_button = array();
        if (AccessLevel::CheckUser()) {
            $list_button = array(
                "invoice" => array(
                    'name' => esc_html__("فاکتور", PARGAR_TEXT_DOMAIN_NAME),
                    'url' => pargar_invoice_page_url($order_id),
                    'title' => sprintf('<img src="%s" alt="Custom Button" style="width:20px; margin:4px 2px; ">', PARGAR_URL . 'assets/svg/icon-btn-invoice.svg'),
                ),
                "packing_invoice" => array(
                    'name' => esc_html__("انبار داری", PARGAR_TEXT_DOMAIN_NAME),
                    'url' => pargar_invoice_page_url($order_id, array('packing_invoice' => 'yes')),
                    'title' => sprintf('<img src="%s" alt="Custom Button" style="width:20px; margin:4px 2px; ">', PARGAR_URL . 'assets/svg/icon-btn-packing-invoice.svg'),
                ),
                "address_label_invoice" => array(
                    'name' => esc_html__("برچسب آدرس", PARGAR_TEXT_DOMAIN_NAME),
                    'url' => pargar_create_invoice_url(array('print_address_label' => 'yes', 'orders_id' => $order_id)),
                    'title' => sprintf('<img src="%s" alt="Custom Button" style="width:20px; margin:4px 2px; ">', PARGAR_URL . 'assets/svg/icon-btn-address-label-invoice.svg'),
                ),
                "product_label_invoice" => array(
                    'name' => esc_html__("برچسب محصول", PARGAR_TEXT_DOMAIN_NAME),
                    'url' => pargar_create_invoice_url(array('print_product_label' => 'yes', 'orders_id' => $order_id)),
                    'title' => sprintf('<img src="%s" alt="Custom Button" style="width:20px; margin:4px 2px; ">', PARGAR_URL . 'assets/svg/icon-btn-product-label-invoice.svg'),
                ),
            );
        }

        return $list_button;
    }

    /**
     * Registers a custom meta box on the WooCommerce order edit screen.
     *
     * This box allows administrators to access quick invoice printing actions.
     * It appears in the sidebar of the "Edit Order" page in the admin panel.
     *
     * @return void
     * @since 1.0.0
     */
    public function addMetaBoxes()
    {
        $screen_id = function_exists('wc_get_page_screen_id') ? wc_get_page_screen_id('shop-order') : 'shop_order';

        add_meta_box(
            'meta_box_order_pargar',
            'پرگار ( چاپ فاکتور )',
            array($this, 'metaBoxCallback'),
            $screen_id,
            'side',
            'high',
        );
    }

    /**
     * Callback function that renders the HTML content of the custom meta box.
     *
     * Displays a vertical list of action buttons (e.g. invoice, packing, label printing)
     * based on access permission and the current order’s ID.
     *
     * @param \WP_Post $post The current order post object.
     * @return void
     * @since 1.0.0
     */
    public function metaBoxCallback($post)
    {
        $list_button = $this->listBtn($post->get_id());
        echo '<style>.pargar-custom-btn{display: flex !important;justify-content: space-between;align-items: center;font-weight: bold;max-width: 200px;width: 100%;}.pargar-custom-btns{display: flex; flex-direction: column;gap: 16px;align-items: center;}</style>';
        printf("<div class='pargar-custom-btns'>");
        foreach ($list_button as $key => $button) {
            if (AccessLevel::CheckButtonAdminWc($key)) {
                printf('<a href="%s" class="button pargar-custom-btn" target="_blank">%s %s</a>', esc_url($button['url']), $button['name'], $button['title']);
            }
        }
        printf('</div>');
    }
}