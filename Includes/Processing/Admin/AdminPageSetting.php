<?php
namespace Pargar\Includes\Processing\Admin;

use Pargar\Includes\Processing\AccessLevel;
use Pargar\Includes\Processing\ImportTemplate;
use Pargar\Includes\Processing\InvoicePostType;

/**
 * AdminPageSetting
 *
 * Handles the admin interface for plugin settings and configurations.
 *
 * This class is responsible for:
 * - Registering custom admin menu pages.
 * - Enqueuing styles and scripts for admin UI.
 * - Rendering the settings page with local and remote templates.
 * - Integrating AJAX handlers for dynamic admin interactions.
 *
 * It follows the Singleton pattern to ensure a single instance during runtime.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */

class AdminPageSetting
{
    /**
     * Holds the Singleton instance
     *
     * @var self|null $_instance Stores the instance of the class
     */
    private static $_instance;

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
     * Hooks admin-related methods like menu registration and script loading.
     * Also initializes and binds AJAX handlers via AjaxSetting class.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this , 'adminMenu' ) );
        add_action( 'admin_enqueue_scripts', array( $this , 'adminScripts' ) );

        $O = new AjaxSetting();
        $O->hookAjax();
    }

    /**
     * Registers the plugin's admin menu page in the WordPress dashboard.
     *
     * Only adds the menu if the current user passes the AccessLevel check.
     *
     * @since 1.0.0
     */
    public function adminMenu(){
        if( AccessLevel::CheckUser() ){
            $menu_icon  = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUiIGhlaWdodD0iMjciIHZpZXdCb3g9IjAgMCAyNSAyNyIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIzLjcyNzQgMTguMDkzOEgwLjg5MzY1NEMwLjU3MTYxMyAxOC4wOTM4IDAuMzEwNTQ3IDE4LjM1NDggMC4zMTA1NDcgMTguNjc2OVYxOS4zMjM5QzAuMzEwNTQ3IDE5LjY0NiAwLjU3MTYxMyAxOS45MDcxIDAuODkzNjU0IDE5LjkwNzFIMjMuNzI3NEMyNC4wNDk1IDE5LjkwNzEgMjQuMzEwNSAxOS42NDYgMjQuMzEwNSAxOS4zMjM5VjE4LjY3NjlDMjQuMzEwNSAxOC4zNTQ4IDI0LjA0OTUgMTguMDkzOCAyMy43Mjc0IDE4LjA5MzhaIiBmaWxsPSIjQjNCNkVCIi8+CjxwYXRoIGQ9Ik0xMi43MDA2IDE3LjQxMDJIMTEuOTE3N0MxMS41OTQzIDE3LjQxMDIgMTEuMzMyIDE3LjY3MjQgMTEuMzMyIDE3Ljk5NTlWMjAuMDAxMUMxMS4zMzIgMjAuMzI0NiAxMS41OTQzIDIwLjU4NjggMTEuOTE3NyAyMC41ODY4SDEyLjcwMDZDMTMuMDI0MSAyMC41ODY4IDEzLjI4NjMgMjAuMzI0NiAxMy4yODYzIDIwLjAwMTFWMTcuOTk1OUMxMy4yODYzIDE3LjY3MjQgMTMuMDI0MSAxNy40MTAyIDEyLjcwMDYgMTcuNDEwMloiIGZpbGw9IiNEM0QzRkYiLz4KPHBhdGggZD0iTTIuMTg5NTkgMjYuNDQ3NEMyLjg1MDcyIDI2LjgyOTIgMy42OTY1MSAyNi42MDI0IDQuMDc4MzIgMjUuOTQxM0w1LjU2OTEyIDIzLjM1OTJMMy4xNzQyNyAyMS45NzY2TDEuNjgzNDcgMjQuNTU4N0MxLjMwMTY2IDI1LjIxOTggMS41Mjg0NiAyNi4wNjU2IDIuMTg5NTkgMjYuNDQ3NFoiIGZpbGw9IiNEM0QzRkYiLz4KPHBhdGggZD0iTTIuMTkwMDQgMjMuMTE0M0w0LjExODgyIDI0LjIyOEM0Ljg0MzkzIDI0LjY0NjggNS43NzEzOSAyNC4zOTgxIDYuMTkwNjQgMjMuNjczTDEzLjc5MzQgMTAuNTA1TDkuMjM3NzggNy44NzVMMS42MzUwMiAyMS4wNDNDMS4yMTYyOCAyMS43NjgxIDEuNDY0OTIgMjIuNjk1NiAyLjE5MDA0IDIzLjExNDlWMjMuMTE0M1oiIGZpbGw9IiM0RjkzRjAiLz4KPHBhdGggZD0iTTIyLjQzMjMgMjYuNDQ3NEMyMS43NzExIDI2LjgyOTIgMjAuOTI1MyAyNi42MDI0IDIwLjU0MzUgMjUuOTQxM0wxOS4wNTI3IDIzLjM1OTJMMjEuNDQ3NiAyMS45NzY2TDIyLjkzODQgMjQuNTU4N0MyMy4zMjAyIDI1LjIxOTggMjMuMDkzNCAyNi4wNjU2IDIyLjQzMjMgMjYuNDQ3NFoiIGZpbGw9IiM1QzZFODQiLz4KPHBhdGggZD0iTTIyLjQzMTUgMjMuMTE0M0wyMC41MDI3IDI0LjIyOEMxOS43Nzc2IDI0LjY0NjggMTguODUwMSAyNC4zOTgxIDE4LjQzMDkgMjMuNjczTDEwLjgyODEgMTAuNTA1TDE1LjM4MzggNy44NzVMMjIuOTg2NSAyMS4wNDNDMjMuNDA1MyAyMS43NjgxIDIzLjE1NjYgMjIuNjk1NiAyMi40MzE1IDIzLjExNDlWMjMuMTE0M1oiIGZpbGw9IiM0RjkzRjAiLz4KPHBhdGggZD0iTTEzLjAxODUgMEgxMS42QzEwLjc3NzggMCAxMC4xMTEzIDAuNjY2NTIyIDEwLjExMTMgMS40ODg3MlYzLjA3Njc5QzEwLjExMTMgMy44OTg5OSAxMC43Nzc4IDQuNTY1NTEgMTEuNiA0LjU2NTUxSDEzLjAxODVDMTMuODQwNyA0LjU2NTUxIDE0LjUwNzMgMy44OTg5OSAxNC41MDczIDMuMDc2NzlWMS40ODg3MkMxNC41MDczIDAuNjY2NTIyIDEzLjg0MDcgMCAxMy4wMTg1IDBaIiBmaWxsPSIjRDNEM0ZGIi8+CjxwYXRoIGQ9Ik0xNS4zODM4IDcuODc1NTJMMTAuODI4MSAxMC41MDU1TDEzLjgwNDUgMTUuNjYwM0MxNS42MTI2IDE1LjI3MzMgMTcuMTY3OSAxNC4xOTgyIDE4LjE4MzMgMTIuNzIzNUwxNS4zODM4IDcuODc1VjcuODc1NTJaIiBmaWxsPSIjMkY3MkVBIi8+CjxwYXRoIGQ9Ik05LjIzNzA0IDcuODc1TDYuNDM3NSAxMi43MjM1QzcuNDUyODcgMTQuMTk4MiA5LjAwODE3IDE1LjI3MjggMTAuODE2MyAxNS42NjAzTDEzLjc5MjcgMTAuNTA1NUw5LjIzNzA0IDcuODc1NTJWNy44NzVaIiBmaWxsPSIjMkY3MkVBIi8+CjxwYXRoIGQ9Ik0xNi4wMzAyIDEyLjQxMTFDMTguMDg0NSAxMC4zNTY4IDE4LjA4NDUgNy4wMjYwNCAxNi4wMzAyIDQuOTcxN0MxMy45NzU5IDIuOTE3MzcgMTAuNjQ1MSAyLjkxNzM3IDguNTkwOCA0Ljk3MTdDNi41MzY0NiA3LjAyNjA0IDYuNTM2NDYgMTAuMzU2OCA4LjU5MDggMTIuNDExMUMxMC42NDUxIDE0LjQ2NTQgMTMuOTc1OSAxNC40NjU0IDE2LjAzMDIgMTIuNDExMVoiIGZpbGw9IiM1QzZFODQiLz4KPHBhdGggZD0iTTkuMjA4NDMgOC44Njk5OUM5LjUwMjYxIDguODY5OTkgOS43NDEwOCA4LjYzMTUxIDkuNzQxMDggOC4zMzczNEM5Ljc0MTA4IDguMDQzMTYgOS41MDI2MSA3LjgwNDY5IDkuMjA4NDMgNy44MDQ2OUM4LjkxNDI2IDcuODA0NjkgOC42NzU3OCA4LjA0MzE2IDguNjc1NzggOC4zMzczNEM4LjY3NTc4IDguNjMxNTEgOC45MTQyNiA4Ljg2OTk5IDkuMjA4NDMgOC44Njk5OVoiIGZpbGw9IiMyRjQ0NUUiLz4KPHBhdGggZD0iTTE1LjQxMTYgOC44Njk5OUMxNS43MDU3IDguODY5OTkgMTUuOTQ0MiA4LjYzMTUxIDE1Ljk0NDIgOC4zMzczNEMxNS45NDQyIDguMDQzMTYgMTUuNzA1NyA3LjgwNDY5IDE1LjQxMTYgNy44MDQ2OUMxNS4xMTc0IDcuODA0NjkgMTQuODc4OSA4LjA0MzE2IDE0Ljg3ODkgOC4zMzczNEMxNC44Nzg5IDguNjMxNTEgMTUuMTE3NCA4Ljg2OTk5IDE1LjQxMTYgOC44Njk5OVoiIGZpbGw9IiMyRjQ0NUUiLz4KPHBhdGggZD0iTTEyLjMwOTcgOS44OTM4OUMxMS44MzQ4IDkuODkzODkgMTEuMzgxNyA5LjcyMzggMTAuOTk5OSA5LjQwMjMzQzEwLjg2NzggOS4yOTE1NCAxMC44NTExIDkuMDk0MzkgMTAuOTYxOSA4Ljk2Mjc5QzExLjA3MjcgOC44MzA2NyAxMS4yNjk5IDguODE0MDIgMTEuNDAxNSA4LjkyNDgyQzExLjY2OTQgOS4xNTA1NyAxMS45ODM1IDkuMjY5NjkgMTIuMzA5NyA5LjI2OTY5QzEyLjYzNTggOS4yNjk2OSAxMi45NDk1IDkuMTUwNTcgMTMuMjE3OSA4LjkyNDgyQzEzLjM1IDguODE0MDIgMTMuNTQ2NiA4LjgzMDY3IDEzLjY1NzQgOC45NjI3OUMxMy43NjgyIDkuMDk0OTEgMTMuNzUxNiA5LjI5MTU0IDEzLjYxOTUgOS40MDIzM0MxMy4yMzc3IDkuNzIzOCAxMi43ODQ2IDkuODkzMzcgMTIuMzA5NyA5Ljg5MzM3VjkuODkzODlaIiBmaWxsPSIjMkY0NDVFIi8+Cjwvc3ZnPgo=';
            add_menu_page(
                __( 'پرگار', PARGAR_TEXT_DOMAIN_NAME ),
                __( 'پرگار' , PARGAR_TEXT_DOMAIN_NAME ),
                'manage_options',
                PARGAR_UNIQUE_TEMPLATE_NAEM,
                array( $this, 'menuPage' ),
                $menu_icon,
                32
            );
        }
    }

    /**
     * Renders the contents of the admin settings page.
     *
     * Loads local and remote invoice templates and includes the main admin view.
     *
     * @since 1.0.0
     */
    public function menuPage(){
        include PARGAR_PATH.'helper.php';
        $obj_invoice_post_type = new InvoicePostType();
        $all_template = array();
        foreach ( $obj_invoice_post_type->getAllTemplates() as $template ){
            $all_template[$template->ID] = $template->post_title;
        }
        $all_template = apply_filters('pargar_invoice_templates', $all_template);
        $all_address_template = apply_filters('pargar_address_label_templates', array() );

        $obj_import_template = new ImportTemplate();
        $all_template_remote = $obj_import_template->getTemplateRemoteForApi();
        include PARGAR_PATH_TEMPLATE . '/admin/index.php';
    }

    /**
     * Enqueues styles and scripts required for the plugin's admin interface.
     *
     * Ensures the assets are only loaded on the plugin's admin page.
     *
     * @param string $hook The current admin page hook suffix
     *
     * @since 1.0.0
     */
    function adminScripts($hook) {
        if ($hook != 'toplevel_page_'.PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_style('magic-factor-style', PARGAR_URL . 'assets/admin/style.css',[],PARGAR_VERSION);
        wp_enqueue_script('magic-factor-script', PARGAR_URL . 'assets/admin/script.js', array('jquery','editor'), PARGAR_VERSION, true);
        wp_enqueue_style('magic-factor-font', PARGAR_URL . 'assets/fonts/iranSans/font.css', [], PARGAR_VERSION);
        wp_localize_script('magic-factor-script','magic_factor_parameter_backend',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce_setting' => wp_create_nonce('magic_factor_setting'),
                'ajax_nonce_import_template_remote' => wp_create_nonce(ImportTemplate::GetNonceImportRemote())
            )
        );
    }
}