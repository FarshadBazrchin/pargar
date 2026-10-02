<?php

namespace Pargar\Includes\Processing;

use Pargar\Includes\Core\Core;
use Pargar\Includes\Core\Utility;
use function ElementorDeps\DI\add;

/**
 * The ImportTemplate class is responsible for managing the import of remote templates.
 * It provides methods for interacting with APIs, handling AJAX requests, and processing imported data.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class ImportTemplate
{

    /**
     * Generate a nonce string for remote template imports.
     *
     * This nonce is used for securing requests during the import of demo templates
     * from remote locations.
     *
     * @since 1.0.0
     * @return string The nonce string.
     */
    public static function GetNonceImportRemote()
    {
        return 'import_demo_template_remote';
    }

    /**
     * Register AJAX actions for importing remote templates.
     *
     * This method links AJAX requests to the corresponding handler function,
     * allowing secure interactions for template import operations.
     *
     * @since 1.0.0
     */
    public function ajax()
    {
        add_action("wp_ajax_pargar_import_demo_remote", array( $this, 'extractRemoteTemplateDemo' ) );
    }

    /**
     * Retrieve templates from a remote API.
     *
     * This method makes a GET request to an external API to fetch available templates.
     * It includes headers for authorization and handles potential errors during the connection.
     *
     * @since 1.0.0
     * @return array|string The decoded API response or an error message if the connection fails.
     */
    public function getTemplateRemoteForApi()
    {
        $response = wp_remote_get( 'https://code-art.ir/wp-json/mf/v1/templates', array(
            'headers' => array(
                'Authorization' => 'Bearer 1Q3f4Vf6b09JRe0Q1vUaPblK',
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            return 'خطا در اتصال به API';
        }

        $body = wp_remote_retrieve_body( $response );
        return json_decode( $body, true );
    }

    /**
     * Handle the remote template demo extraction process.
     *
     * This method validates the provided nonce and file URL, ensuring secure processing.
     * If the file URL and nonce are valid, it initiates the template import process.
     *
     * @since 1.0.0
     */
    public function extractRemoteTemplateDemo()
    {
        if ( !isset( $_POST['file_url'] ) ) {
            wp_send_json_error(
                array(
                    'type' => "not_found_file"
                )
            );
        }

        if ( wp_verify_nonce( $_POST['nonce'], self::GetNonceImportRemote() ) ) {
            $a = $this->import( $_POST['file_url'] );

            if (  $a ){
                wp_send_json_success();
            }
            wp_send_json_error();
        }else{
            wp_send_json_error(
                array(
                    'type' => "nonce_error"
                )
            );
        }
    }

    /**
     * Import a remote template from a file path.
     *
     * This method processes the file by reading and decrypting its contents,
     * validating its structure, and storing the data into a WordPress post.
     *
     * @since 1.0.0
     * @param string $file_path The path to the file containing the template data.
     * @return bool|\WP_Error True on success or WP_Error on failure.
     */
    public function import($file_path) {
        $json_data = file_get_contents($file_path);

        if (!$json_data) {
            return new \WP_Error('txt_read_error', 'خطا در خواندن فایل txt.');
        }
        $json_data = unserialize( $json_data);
        $json_data = Core::decryptAesGcm($json_data['encrypted_data'],$json_data['tag']);


        $data = json_decode($json_data,true);
        if (!$data) {
            return new \WP_Error('json_decode_error', 'خطا در تبدیل JSON به آرایه.');
        }

        $data_post = $data['post_data'];
        $meta_data = $data['meta_data'];

        $post_args = array(
            'post_title'    => isset($data_post['post_title']) ? wp_strip_all_tags($data_post['post_title']) : 'عنوان جدید',
            'post_content'  => $data_post['post_content'] ?? '',
            'post_excerpt'  => $data_post['post_excerpt'] ?? '',
            'post_type'     => \Pargar\Includes\Processing\InvoicePostType::getPostType(),
            'post_status'   => $data_post['post_status'] ?? 'publish',
            'post_author'   => get_current_user_id(),
        );

        $post_id = wp_insert_post($post_args);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        foreach ( $meta_data as $meta_key => $meta_value ) {
            if ($meta_value['str_type'] == "json"){
                update_post_meta($post_id, $meta_key, json_encode($meta_value['value'], JSON_UNESCAPED_UNICODE));
            }else{
                update_post_meta($post_id, $meta_key, $meta_value['value']);
            }
        }

        return true;
    }
}