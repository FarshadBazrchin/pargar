<?php

namespace Pargar\Includes\Processing;

use Pargar\Includes\Core\Core;
use Pargar\Includes\Core\Utility;
use function ElementorDeps\DI\add;

/**
 * The ExportTemplate class handles exporting templates in WordPress.
 * It provides functionalities to add export buttons, handle export actions,
 * and generate downloadable template files with post data and metadata.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class ExportTemplate
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
     * Registers WordPress hooks to integrate export functionality.
     * Includes filters for adding buttons and actions for handling export logic.
     */
    public function __construct()
    {
        add_filter( 'post_row_actions', array( $this, 'addBtnExportTable' ), 10, 2 );
        add_action( 'admin_init', array( $this, 'export' ) );
    }

    /**
     * Add an "Export" button to the post actions table.
     *
     * This method checks the post type and appends an export link to the row actions
     * in the WordPress admin panel.
     *
     * @since  1.0.0
     * @param array $actions Existing row actions for the post.
     * @param WP_Post $post The WordPress post object.
     * @return array Modified row actions with the export button.
     */
    public function addBtnExportTable( $actions , $post ) {
        if ( $post->post_type == PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            $actions['pargar_export'] = sprintf(
                '<a href="%1$s">%2$s</a>',
                $this->createUrlExport( $post->ID ),
                "برون بری"
            );
        }
        return $actions;
    }

    /**
     * Handle the export action in the admin panel.
     *
     * This method checks the URL parameters to determine if an export action
     * should be performed, and if so, calls the export handler function.
     *
     * @since 1.0.0
     */
    public function export( )
    {
        if ( is_admin() && isset( $_GET['pargar_action']) && isset( $_GET['post_id']) && $_GET['pargar_action'] == 'export_template'){
            $this->exportHandel( $_GET['post_id'] );
        }
    }

    /**
     * Process the export of template data.
     *
     * Reads the post and its metadata, formats the data, and generates a file
     * that can be downloaded. The file includes serialized and encrypted content
     * for secure storage.
     *
     * @since 1.0.0
     * @param int $post_id The ID of the post to export.
     */
    private function exportHandel( $post_id )
    {
        $post = get_post($post_id);
        if ( isset( $post->ID ) ) {
            $data_post = array(
                'post_title' => $post->post_title,
                'post_content' => $post->post_content,
                'post_status' => $post->post_status,
                'post_excerpt' => $post->post_excerpt,
            );

            $key_meta = array(
                '_wp_page_template',
                '_elementor_data',
                '_elementor_page_settings',
                '_elementor_template_type',
                '_elementor_controls_usage',
                '_elementor_page_assets',
                '_elementor_version',
                '_elementor_edit_mode',
            );
            $metas = array();

            foreach (  $key_meta as $item ){
                $value = get_post_meta( $post_id , $item ,true);

                if ( is_serialized( $value ) ){
                    $metas[$item] = array(
                        'value' => unserialize( $value ),
                        'str_type' => 'serialize'
                    );
                }elseif ( Utility::isJson( $value ) ){
                    $metas[$item] = array(
                        'value' => json_decode( $value ),
                        'str_type' => 'json'
                    );
                }else{
                    $metas[$item] = array(
                        'value' => $value,
                        'str_type' => 'str'
                    );
                }
            }

            $data = array(
                'post_data' => $data_post,
                'meta_data' => $metas,
            );

            $file_name = sprintf("assets/export/export-pargar-%s.txt", $post_id );
            $file_json = fopen(PARGAR_PATH.$file_name, "w");
            fwrite($file_json, serialize( Core::encryptAesGcm( json_encode($data) )) );
            fclose($file_json);
            Utility::downloadUrl( PARGAR_URL.$file_name );
        }
    }

    /**
     * Create a URL for exporting the template.
     *
     * Generates a URL with query parameters to trigger the export action
     * for a specific post in the admin panel.
     *
     * @since 1.0.0
     * @param int $post_id The ID of the post to export.
     * @return string The URL for the export action.
     */
    private function createUrlExport( $post_id )
    {
        $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $new_params = array(
            'pargar_action' => 'export_template',
            'post_id' => $post_id
        );
        return add_query_arg($new_params, $current_url);
    }
}