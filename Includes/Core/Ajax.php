<?php

namespace Pargar\Includes\Core;

/**
 * Handles AJAX requests related to PDF generation for the plugin.
 *
 * This class:
 * - Registers AJAX actions for logged-in and non-logged-in users.
 * - Coordinates PDF generation flow, including HTML handling, parameter sanitization,
 *   signature generation, and file streaming.
 * - Utilizes the Singleton pattern for consistent global access.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class Ajax{

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
     * Registers the `printPDF` method as the handler for
     * `wp_ajax_magic_factor_print_pdf` and `wp_ajax_nopriv_magic_factor_print_pdf` actions.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action("wp_ajax_magic_factor_print_pdf", array( $this, 'printPDF' ) );
        add_action("wp_ajax_nopriv_magic_factor_print_pdf", array( $this, 'printPDF' ) );
    }

    /**
     * Handles the AJAX request for generating and downloading a PDF.
     *
     * Workflow:
     * - Validates login and nonce.
     * - Optionally saves custom HTML to a temporary file.
     * - Prepares API parameters like page size, scaling, and signature.
     * - Invokes the PDF generation API and returns the resulting file.
     *
     * If the generation fails, it returns a JSON error. Otherwise,
     * streams the file directly to the user's browser.
     *
     * @return void
     * @since 1.0.0
     */
    public function printPDF()
    {
        $is_preview = apply_filters("pargar_preview_enabled", false);

        if ( !$is_preview  && !is_user_logged_in() ){
            wp_send_json_error(
                array(
                    'type' => "error_login"
                )
            );
        }

        if (wp_verify_nonce($_POST['nonce'], 'print_pdf')) {


            $signature = \Pargar\Includes\Processing\API\APIToken::instance()->createSignature();



            $url = home_url( sanitize_url(  $_POST['url'] ) );;

            if ( isset( $_POST['html'] ) && $_POST['html'] != "" ) {
                $url = $this->handleHtml( md5($signature) );
            }

            $page_size = sanitize_text_field(  $_POST['page_size'] );
            $page_orientation = sanitize_text_field(  $_POST['page_orientation'] );
            $page_scale = intval(  $_POST['page_scale'] );
            $page_scale = ( $page_scale / 100 );
            $page_size_auto = false;
            if ( $page_size == "custom" ){
                $page_size_auto = true;
            }

            $obj_api  = new \Pargar\Includes\Processing\API\APIPrintPdf();
            $obj_api->setUrl( $url );
            $obj_api->setPageSize( $page_size );
            $obj_api->setScale( $page_scale );
            $obj_api->setPageSizeAuto( $page_size_auto );
            $obj_api->setPageOrientation( $page_orientation );
            $obj_api->setSignature( $signature );
            $result = $obj_api->printPDF();

            if ( isset( $_POST['html'] ) && $_POST['html'] != "" ) {
                $name = PARGAR_PATH . 'assets/handle/' . $signature . '.html';
                if (file_exists($name)) {
                    unlink($name);
                }
            }

            if ( $result != false ) {
                $file_url = $result['download_link'];
                $file_name = basename($file_url);

                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"$file_name\"");
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile($file_url);

            }else{
                wp_send_json_error();
            }
        }else{
            wp_send_json_error(
                array(
                    'type' => "nonce_error"
                )
            );
        }
    }

    /**
     * Saves submitted HTML into a temporary file for PDF rendering.
     *
     * Cleans the HTML from quotes and slashes, then stores it under the
     * plugin's `assets/handle` directory.
     *
     * @param string $name Unique file name (typically derived from the PDF signature)
     * @return string URL to the saved HTML file
     * @since  1.0.0
     */
    public function handleHtml( $name  )
    {
        $html = $_POST['html'];
        $cleaned_html = htmlspecialchars_decode($html, ENT_QUOTES);
        $cleaned_html = stripslashes($cleaned_html);
        file_put_contents(PARGAR_PATH . 'assets/handle/' . $name . '.html', $cleaned_html);
        return PARGAR_URL.'assets/handle/' . $name . '.html';
    }


}
