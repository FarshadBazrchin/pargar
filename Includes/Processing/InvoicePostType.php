<?php
namespace Pargar\Includes\Processing;

use Elementor\Plugin;
use Pargar\Includes\Core\Utility;
use Pargar\Includes\Processing\API\APIToken;

/**
 *
 * InvoicePostType
 *
 * This class handles the functionality for the custom post type 'Invoice Template'.
 *
 * Main features:
 * - Registers a custom post type used as an invoice template.
 * - Integrates with Elementor for customizing templates.
 * - Implements logic for template redirection and permissions handling.
 * - Provides utility methods for fetching and managing templates.
 *
 * Note: Make sure that constants like PARGAR_UNIQUE_TEMPLATE_NAME and PARGAR_UNIQUE_TEMPLATE_NAME
 * are properly defined in your environment.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class InvoicePostType
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
     * Retrieves the unique name for the custom post type.
     *
     * @return string Custom post type identifier.
     */
    public static function getPostType()
    {
        return PARGAR_UNIQUE_TEMPLATE_NAEM;
    }

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'));

        $post_type = self::getPostType();
        add_filter("theme_{$post_type}_templates", array($this, 'registerTemplate'), 10, 4);
        add_filter("single_template", array($this, 'pageTemplate'));

        add_action('elementor/documents/register', array($this, 'register_documents'));

        add_filter('template_redirect', array($this, 'redirectElementorTemplate'));
        add_action('template_redirect', array($this, 'verifyOrderPostType'), 1);

        add_action('template_redirect', function () {
            if (isset($_GET['o']) && isset($_GET['p'])) {
                wp_redirect(pargar_invoice_page_url($_GET['o']));
                die();
            }
        }, 1);

        add_action( 'save_post_pargar_factor', array( $this, 'changeDefultPageTemplate' ), 10, 3 );

        add_action('wp_enqueue_scripts', array($this, 'dequeueStyles'), PHP_INT_MAX);

        add_action('elementor/editor/after_enqueue_styles', array( $this, 'addSvgIconStyle') );
    }

    /**
     * Automatically set default page template for new 'pargar_factor' posts
     * @return void
     * @since 1.0.0
     */
    public function changeDefultPageTemplate( $post_id, $post, $update )
    {
        if ( $update ) {
            return;
        }
        if ( get_post_type( $post_id ) !== 'pargar_factor' ){
            return;
        };
        update_post_meta( $post_id, '_wp_page_template', 'caver_factor' );
    }

    /**
     * Enqueue SVG icon styles for 'pargar_factor' post type
     * @return void
     * @since 1.0.0
     */
    public function addSvgIconStyle() {
        wp_enqueue_style('pargar-factor-svg', PARGAR_URL . 'assets/css/svg.css');
    }

    /**
     * Registers the custom post type 'Invoice Template' with specific settings.
     *
     * @return void
     * @since 1.0.0
     */
    public function registerPostType()
    {
        $labels = array(
            'name'               => 'قالب های پرگار',
            'singular_name'      => 'قالب های پرگار',
            'add_new'            => 'افزودن جدید',
            'add_new_item'       => 'افزودن طرح جدید',
            'edit_item'          => 'ویرایش پست',
            'new_item'           => 'طرح جدید',
            'view_item'          => 'مشاهده پست',
            'search_items'       => 'جستجوی طرح ‌ها',
            'not_found'          => 'چیزی یافت نشد',
            'not_found_in_trash' => 'در زباله‌دان یافت نشد',
        );
        $menu_icon  = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUiIGhlaWdodD0iMjciIHZpZXdCb3g9IjAgMCAyNSAyNyIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIzLjcyNzQgMTguMDkzOEgwLjg5MzY1NEMwLjU3MTYxMyAxOC4wOTM4IDAuMzEwNTQ3IDE4LjM1NDggMC4zMTA1NDcgMTguNjc2OVYxOS4zMjM5QzAuMzEwNTQ3IDE5LjY0NiAwLjU3MTYxMyAxOS45MDcxIDAuODkzNjU0IDE5LjkwNzFIMjMuNzI3NEMyNC4wNDk1IDE5LjkwNzEgMjQuMzEwNSAxOS42NDYgMjQuMzEwNSAxOS4zMjM5VjE4LjY3NjlDMjQuMzEwNSAxOC4zNTQ4IDI0LjA0OTUgMTguMDkzOCAyMy43Mjc0IDE4LjA5MzhaIiBmaWxsPSIjQjNCNkVCIi8+CjxwYXRoIGQ9Ik0xMi43MDA2IDE3LjQxMDJIMTEuOTE3N0MxMS41OTQzIDE3LjQxMDIgMTEuMzMyIDE3LjY3MjQgMTEuMzMyIDE3Ljk5NTlWMjAuMDAxMUMxMS4zMzIgMjAuMzI0NiAxMS41OTQzIDIwLjU4NjggMTEuOTE3NyAyMC41ODY4SDEyLjcwMDZDMTMuMDI0MSAyMC41ODY4IDEzLjI4NjMgMjAuMzI0NiAxMy4yODYzIDIwLjAwMTFWMTcuOTk1OUMxMy4yODYzIDE3LjY3MjQgMTMuMDI0MSAxNy40MTAyIDEyLjcwMDYgMTcuNDEwMloiIGZpbGw9IiNEM0QzRkYiLz4KPHBhdGggZD0iTTIuMTg5NTkgMjYuNDQ3NEMyLjg1MDcyIDI2LjgyOTIgMy42OTY1MSAyNi42MDI0IDQuMDc4MzIgMjUuOTQxM0w1LjU2OTEyIDIzLjM1OTJMMy4xNzQyNyAyMS45NzY2TDEuNjgzNDcgMjQuNTU4N0MxLjMwMTY2IDI1LjIxOTggMS41Mjg0NiAyNi4wNjU2IDIuMTg5NTkgMjYuNDQ3NFoiIGZpbGw9IiNEM0QzRkYiLz4KPHBhdGggZD0iTTIuMTkwMDQgMjMuMTE0M0w0LjExODgyIDI0LjIyOEM0Ljg0MzkzIDI0LjY0NjggNS43NzEzOSAyNC4zOTgxIDYuMTkwNjQgMjMuNjczTDEzLjc5MzQgMTAuNTA1TDkuMjM3NzggNy44NzVMMS42MzUwMiAyMS4wNDNDMS4yMTYyOCAyMS43NjgxIDEuNDY0OTIgMjIuNjk1NiAyLjE5MDA0IDIzLjExNDlWMjMuMTE0M1oiIGZpbGw9IiM0RjkzRjAiLz4KPHBhdGggZD0iTTIyLjQzMjMgMjYuNDQ3NEMyMS43NzExIDI2LjgyOTIgMjAuOTI1MyAyNi42MDI0IDIwLjU0MzUgMjUuOTQxM0wxOS4wNTI3IDIzLjM1OTJMMjEuNDQ3NiAyMS45NzY2TDIyLjkzODQgMjQuNTU4N0MyMy4zMjAyIDI1LjIxOTggMjMuMDkzNCAyNi4wNjU2IDIyLjQzMjMgMjYuNDQ3NFoiIGZpbGw9IiM1QzZFODQiLz4KPHBhdGggZD0iTTIyLjQzMTUgMjMuMTE0M0wyMC41MDI3IDI0LjIyOEMxOS43Nzc2IDI0LjY0NjggMTguODUwMSAyNC4zOTgxIDE4LjQzMDkgMjMuNjczTDEwLjgyODEgMTAuNTA1TDE1LjM4MzggNy44NzVMMjIuOTg2NSAyMS4wNDNDMjMuNDA1MyAyMS43NjgxIDIzLjE1NjYgMjIuNjk1NiAyMi40MzE1IDIzLjExNDlWMjMuMTE0M1oiIGZpbGw9IiM0RjkzRjAiLz4KPHBhdGggZD0iTTEzLjAxODUgMEgxMS42QzEwLjc3NzggMCAxMC4xMTEzIDAuNjY2NTIyIDEwLjExMTMgMS40ODg3MlYzLjA3Njc5QzEwLjExMTMgMy44OTg5OSAxMC43Nzc4IDQuNTY1NTEgMTEuNiA0LjU2NTUxSDEzLjAxODVDMTMuODQwNyA0LjU2NTUxIDE0LjUwNzMgMy44OTg5OSAxNC41MDczIDMuMDc2NzlWMS40ODg3MkMxNC41MDczIDAuNjY2NTIyIDEzLjg0MDcgMCAxMy4wMTg1IDBaIiBmaWxsPSIjRDNEM0ZGIi8+CjxwYXRoIGQ9Ik0xNS4zODM4IDcuODc1NTJMMTAuODI4MSAxMC41MDU1TDEzLjgwNDUgMTUuNjYwM0MxNS42MTI2IDE1LjI3MzMgMTcuMTY3OSAxNC4xOTgyIDE4LjE4MzMgMTIuNzIzNUwxNS4zODM4IDcuODc1VjcuODc1NTJaIiBmaWxsPSIjMkY3MkVBIi8+CjxwYXRoIGQ9Ik05LjIzNzA0IDcuODc1TDYuNDM3NSAxMi43MjM1QzcuNDUyODcgMTQuMTk4MiA5LjAwODE3IDE1LjI3MjggMTAuODE2MyAxNS42NjAzTDEzLjc5MjcgMTAuNTA1NUw5LjIzNzA0IDcuODc1NTJWNy44NzVaIiBmaWxsPSIjMkY3MkVBIi8+CjxwYXRoIGQ9Ik0xNi4wMzAyIDEyLjQxMTFDMTguMDg0NSAxMC4zNTY4IDE4LjA4NDUgNy4wMjYwNCAxNi4wMzAyIDQuOTcxN0MxMy45NzU5IDIuOTE3MzcgMTAuNjQ1MSAyLjkxNzM3IDguNTkwOCA0Ljk3MTdDNi41MzY0NiA3LjAyNjA0IDYuNTM2NDYgMTAuMzU2OCA4LjU5MDggMTIuNDExMUMxMC42NDUxIDE0LjQ2NTQgMTMuOTc1OSAxNC40NjU0IDE2LjAzMDIgMTIuNDExMVoiIGZpbGw9IiM1QzZFODQiLz4KPHBhdGggZD0iTTkuMjA4NDMgOC44Njk5OUM5LjUwMjYxIDguODY5OTkgOS43NDEwOCA4LjYzMTUxIDkuNzQxMDggOC4zMzczNEM5Ljc0MTA4IDguMDQzMTYgOS41MDI2MSA3LjgwNDY5IDkuMjA4NDMgNy44MDQ2OUM4LjkxNDI2IDcuODA0NjkgOC42NzU3OCA4LjA0MzE2IDguNjc1NzggOC4zMzczNEM4LjY3NTc4IDguNjMxNTEgOC45MTQyNiA4Ljg2OTk5IDkuMjA4NDMgOC44Njk5OVoiIGZpbGw9IiMyRjQ0NUUiLz4KPHBhdGggZD0iTTE1LjQxMTYgOC44Njk5OUMxNS43MDU3IDguODY5OTkgMTUuOTQ0MiA4LjYzMTUxIDE1Ljk0NDIgOC4zMzczNEMxNS45NDQyIDguMDQzMTYgMTUuNzA1NyA3LjgwNDY5IDE1LjQxMTYgNy44MDQ2OUMxNS4xMTc0IDcuODA0NjkgMTQuODc4OSA4LjA0MzE2IDE0Ljg3ODkgOC4zMzczNEMxNC44Nzg5IDguNjMxNTEgMTUuMTE3NCA4Ljg2OTk5IDE1LjQxMTYgOC44Njk5OVoiIGZpbGw9IiMyRjQ0NUUiLz4KPHBhdGggZD0iTTEyLjMwOTcgOS44OTM4OUMxMS44MzQ4IDkuODkzODkgMTEuMzgxNyA5LjcyMzggMTAuOTk5OSA5LjQwMjMzQzEwLjg2NzggOS4yOTE1NCAxMC44NTExIDkuMDk0MzkgMTAuOTYxOSA4Ljk2Mjc5QzExLjA3MjcgOC44MzA2NyAxMS4yNjk5IDguODE0MDIgMTEuNDAxNSA4LjkyNDgyQzExLjY2OTQgOS4xNTA1NyAxMS45ODM1IDkuMjY5NjkgMTIuMzA5NyA5LjI2OTY5QzEyLjYzNTggOS4yNjk2OSAxMi45NDk1IDkuMTUwNTcgMTMuMjE3OSA4LjkyNDgyQzEzLjM1IDguODE0MDIgMTMuNTQ2NiA4LjgzMDY3IDEzLjY1NzQgOC45NjI3OUMxMy43NjgyIDkuMDk0OTEgMTMuNzUxNiA5LjI5MTU0IDEzLjYxOTUgOS40MDIzM0MxMy4yMzc3IDkuNzIzOCAxMi43ODQ2IDkuODkzMzcgMTIuMzA5NyA5Ljg5MzM3VjkuODkzODlaIiBmaWxsPSIjMkY0NDVFIi8+Cjwvc3ZnPgo=';
        register_post_type(self::getPostType(),
            array(
                'label' => esc_html__('قالب های پرگار', PARGAR_TEXT_DOMAIN_NAME),
                'labels' => $labels,
                'show_ui' => true,
                'has_archive' => false,
                'exclude_from_search' => true,
                'supports' => array('title', 'editor'),
                'hierarchical' => false,
                'public' => true,
                'menu_position' => 32,
                'menu_icon' => $menu_icon,
                'show_in_rest' => true,
                'capability_type' => 'page',
            )
        );
    }

    /**
     * Registers custom page templates for the custom post type.
     *
     * This method allows specific templates to be associated with posts of the custom post type 'Invoice Template'.
     * It modifies the list of available templates by adding custom entries.
     *
     * @param array $post_templates An array of existing post templates.
     * @param object $wp_theme Current theme object.
     * @param \WP_Post $post Current post object.
     * @param string $post_type Type of the current post.
     * @return array Modified list of templates including the custom template.
     * @since 1.0.0
     */
    public function registerTemplate($post_templates, $wp_theme, $post, $post_type)
    {
        $post_templates['caver_factor'] = "کاور فاکتور";
        return $post_templates;
    }

    /**
     * Determines the template file path for pages of the custom post type.
     *
     * This method checks if the current post belongs to the custom post type 'Invoice Template'
     * and uses a specific template file if the post's page template matches the identifier 'caver_factor'.
     *
     * @param string $page_template The default template file path.
     * @return string The updated template file path for the specific post type or the original path.
     * @since 1.0.0
     */
    public function pageTemplate($page_template)
    {
        global $post;
        if ( $post->post_type == self::getPostType() && $post->page_template == "caver_factor") {
            wp_enqueue_style('pargar-factor-font', PARGAR_URL . 'assets/fonts/iranSans/font.css', [], PARGAR_VERSION);
            wp_enqueue_style('pargar-factor-popup', PARGAR_URL . 'assets/css/popup/popup.css', [], PARGAR_VERSION);
            wp_enqueue_script('pargar-factor-popup', PARGAR_URL . 'assets/js/popup/popup.js', array('jquery'), PARGAR_VERSION, true);
            wp_enqueue_script('pargar-screenshot-script', PARGAR_URL . 'assets/js/html2canvas.min.js', array( 'jquery' ) , PARGAR_VERSION , true );
            wp_localize_script('pargar-factor-popup','magic_factor_parameter_backend',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'ajax_nonce_print_pdf' => wp_create_nonce('print_pdf'),
                )
            );
            $page_template = PARGAR_PATH . '/Includes/templates/elementor/caver-factor.php';
        }
        return $page_template;
    }

    public function register_documents($documents_manager)
    {
        $documents_manager->register_document_type(self::getPostType(), new InvoiceDocument());
    }

    /**
     * Redirects the custom post type to a URL with dynamic query parameters.
     *
     * @param int $post_id ID of the custom post to redirect.
     * @param int $order_id ID of the order for the invoice.
     * @return void
     * @since 1.0.0
     */
    private function redirectCustomPostType($post_id, $order_id)
    {
        $post = get_post($post_id);
        if ($post && $post->post_type === self::getPostType()) {
            $url = get_permalink($post_id);

            $new_params = array();
            if ( $order_id != 0 ){
                $new_params['order_id'] = $order_id;
            }

            if (isset($_GET['print_invoice'])) {
                $new_params['print_invoice'] = 'yes';
            }
            if (isset($_GET['print_pre_invoice'])) {
                $new_params['print_pre_invoice'] = 'yes';
            }
            $url = add_query_arg($new_params, $url);
            wp_redirect($url);
        } else {
            wp_die('شما دسترسی به این صفحه ندارید.');
        }
    }

    /**
     * Manages template redirection for custom Elementor templates.
     *
     * This method handles the redirection process for templates associated with the 'Invoice Template' post type.
     * If the Elementor editor is active, the redirection is bypassed. Otherwise, it checks for specific query
     * parameters to redirect the template based on custom conditions.
     *
     * Logic Breakdown:
     * - Uses `Utility::IsElementorEditor()` to verify if Elementor editor is loaded.
     * - Redirects if `invoice_p` and `invoice_o` query parameters are provided.
     * - Ensures only templates of the specific post type are handled.
     * - Displays an access restriction message (`wp_die()`) if necessary conditions are not met.
     *
     * @param string $template The current template file path.
     * @return string Modified template file path or original template if redirection conditions are not satisfied.
     * @since 1.0.0
     */
    public function redirectElementorTemplate($template)
    {
        global $post;
        if (isset($_GET['action']) && $_GET['action'] == 'elementor') {
            return $template;
        }

        $is_preview = apply_filters("pargar_preview_enabled", false);

        if (isset($_GET['order_id']) && isset($_GET['packing_invoice'])) {
            $page_id = "tm1";
            if (!is_numeric($page_id) && $this->verifyOrder( $_GET['order_id'] )) {
                $dir_template = apply_filters("pargar_invoice_template_" . $page_id, false);
                include $dir_template;
                die();
            }
            if ( $is_preview ){
                $page_id = $_GET['invoice_p'];
            }
            $this->redirectCustomPostType($page_id, 0);
        }

        if (isset($_GET['print_address_label']) && isset($_GET['orders_id'])) {
            $tem_id = pargar_get_setting("address_label_template_id",true , 'template1' );
            $dir_template = apply_filters("pargar_address_label_template_" . $tem_id, false);
            $orders_id = explode(',', $_GET['orders_id']);
            if (sizeof($orders_id) >= 1) {
                foreach ( $orders_id as $order_id ){

                    if ( !$this->verifyOrder( $order_id ) && !$is_preview ){
                        wp_die("عدم دسترسی به این صفحه");
                    }
                }
                if ($dir_template != false) {
                    include $dir_template;
                    die();
                } else {
                    wp_die("عدم دسترسی به این صفحه");
                }
            } else {
                wp_die("عدم دسترسی به این صفحه");
            }
        }

        if (isset($_GET['print_product_label']) && isset($_GET['orders_id'])) {
            $dir_template = apply_filters("pargar_product_label_template", false);
            $orders_id = explode(',', $_GET['orders_id']);
            if (sizeof($orders_id) >= 1) {
                foreach ( $orders_id as $order_id ){
                    if ( !$this->verifyOrder( $order_id ) && !$is_preview ){
                        wp_die("عدم دسترسی به این صفحه");
                    }
                }
                if ($dir_template != false) {
                    include $dir_template;
                    die();
                } else {
                    wp_die("عدم دسترسی به این صفحه");
                }
            } else {
                wp_die("عدم دسترسی به این صفحه");
            }
        }

        if (isset($_GET['invoice_p']) && isset($_GET['order_id'])) {
            $page_id = pargar_get_setting( 'invoice_template_id', true );
            if (!is_numeric($page_id)) {
                $dir_template = apply_filters("pargar_invoice_template_" . $page_id, false);
                $order_id = intval($_GET['order_id']);
                if (!empty($page_id)  && $order_id > 0) {
                    if ($dir_template != false && $this->verifyOrder( $order_id) ) {
                        include $dir_template;
                        die();
                    } else {
                        wp_die("عدم دسترسی به این صفحه");
                    }
                } else {
                    wp_die("عدم دسترسی به این صفحه");
                }
            }
            if ( $is_preview ){
                $page_id = $_GET['invoice_p'];
            }

            $order_id = intval($_GET['order_id']);
            if ($page_id > 0 && $order_id > 0) {
                $this->redirectCustomPostType($page_id, $order_id);
            }
        }

        if (isset($_GET['invoice_p']) && isset($_GET['print_pre_invoice'])) {
            $page_id = pargar_get_setting( 'invoice_template_id', true );
            if ( $page_id == "pos" ){
                $page_id = "tm1";
            }
            if (!is_numeric($page_id)) {
                $dir_template = apply_filters("pargar_invoice_template_" . $page_id, false);
                include $dir_template;
                die();
            }
            if ( $is_preview ){
                $page_id = $_GET['invoice_p'];
            }
            $this->redirectCustomPostType($page_id, 0);
        }



        return $template;
    }

    /**
     * Verifies if the logged-in user has access to a specific custom post type template based on
     * WooCommerce order ownership and environment context.
     *
     * This function checks:
     * - If the request is coming from the admin environment (/wp-admin/), it bypasses all checks and returns the template directly.
     * - If the "print_pre_invoice" parameter exists in the URL, the function bypasses further checks and returns the template.
     * - If the current post matches the defined custom post type, additional checks are performed:
     * - Ensures the user is logged in.
     * - Retrieves the WooCommerce order ID from the URL parameter "order_id."
     * - Verifies the ownership of the order by comparing the user ID with the current logged-in user ID.
     * If any of these checks fail, the user is denied access with an error message displayed via wp_die().
     *
     * @param $template
     * @return mixed
     * @since 1.0.0
     */
    public function verifyOrderPostType($template)
    {
        global $post;
        if (isset($_GET['elementor-preview'])) {
            return $template;
        }
        if (isset($_GET['print_pre_invoice'])) {
            $page_id = pargar_get_setting( 'invoice_template_id', true );
            if ( isset($post->post_type) && $post->ID != $page_id && $post->post_type === self::getPostType() ){
                wp_redirect( pargar_pre_invoice_page_url( array( 'print_pre_invoice' => "yes" ) ) );
            }else if ( isset( $_GET['invoice_p'] ) && $_GET['invoice_p'] != $page_id){
                wp_redirect( pargar_pre_invoice_page_url( array( 'print_pre_invoice' => "yes" ) ) );
            }
            return $template;
        }
        $is_preview = apply_filters("pargar_preview_enabled", false);

        if ($is_preview) {
            return $template;
        }

        if (isset($post->post_type) && $post->post_type === self::getPostType()) {
            $page_id = pargar_get_setting( 'invoice_template_id', true );
            if ( $post->ID != $page_id ){
                wp_redirect( pargar_invoice_page_url( $_GET['order_id'] ) );
            }
            if (is_user_logged_in()) {
                $current_user_id = get_current_user_id();
                $order_id = (isset($_GET['order_id'])) ? $_GET['order_id'] : 0;
                $order = wc_get_order($order_id);
                if (is_bool($order) && !$order) {
                    wp_die('شما دسترسی به این صفحه ندارید');
                }
                if (AccessLevel::CheckUser()) {
                    return $template;
                }

                if ($order && $order->get_user_id() === $current_user_id) {
                    return $template;
                } else {
                    wp_die('شما دسترسی به این صفحه ندارید');
                }
            } else {
                if (isset($_GET['signature']) && APIToken::instance()->verify($_GET['signature'])) {
                    $name = PARGAR_PATH . 'assets/handle/' . $_GET['signature'] . '.html';
                    if ( file_exists( $name ) ){
                        unlink( $name );
                    }
                    return $template;
                }
                wp_die('شما دسترسی به این صفحه ندارید');
            }
        }
        return $template;
    }

    public function verifyOrder($order_id)
    {
        if (is_user_logged_in()) {

            $current_user_id = get_current_user_id();
            $order = wc_get_order($order_id);

            if (is_bool($order) && !$order) {
                return false;
            }

            if (AccessLevel::CheckUser()) {
                return true;
            }

            if ( pargar_get_setting( 'dokan_show_vendor_btn_invoice' , true , 'off') == 'on' ){
                if ( function_exists( 'dokan_get_seller_id_by_order' ) ){

                    $seller = dokan_get_seller_id_by_order( $order );
                    $seller_info = get_userdata( $seller );

                    if ( $current_user_id === $seller_info->ID ) {
                        return true;
                    }
                }
            }

            if ($order && $order->get_user_id() === $current_user_id) {
                return true;
            } else {
                return false;
            }

        } else {
            if (isset($_GET['signature']) && APIToken::instance()->verify($_GET['signature'])) {
                $name = PARGAR_PATH . 'assets/handle/' . $_GET['signature'] . '.html';
                if ( file_exists( $name ) ){
                    unlink( $name );
                }
                return true;
            }
            return false;
        }
    }

    /**
     * Fetches all posts of the custom post type 'Invoice Template'.
     *
     * Creates a WP_Query object to retrieve all posts sorted by date.
     * Resets the query state after fetching posts.
     *
     * @return array List of all retrieved posts.
     * @since 1.0.0
     */

    public function getAllTemplates()
    {
        $args = array(
            'post_type' => self::getPostType(),
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $my_query = new \WP_Query($args);
        wp_reset_postdata();
        return $my_query->posts;
    }

    /**
     * Dequeue and deregister specific styles for custom post type.
     *
     * This function checks if the current post's post type matches the custom post type
     * defined in the application. If the condition is true, it removes and deregisters
     * the 'hello-elementor' style, ensuring it does not conflict with the styling of
     * the custom post type.
     * @return void
     */
    public function dequeueStyles()
    {
        global $post;
        if (isset($post->post_type) && $post->post_type == self::getPostType()) {
            wp_dequeue_style('hello-elementor');
            wp_deregister_style('hello-elementor');
        }
    }
}
