<?php

namespace Pargar\Includes\Core;

use Elementor\Plugin;

/**
 * A collection of static utility functions for use throughout the Pargar plugin.
 *
 * Provides helper methods for:
 * - JSON validation
 * - Secure file download
 * - Meta setting normalization
 * - Elementor state detection
 * - Secure hashing and verification
 * - Random string generation
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class Utility
{
    /**
     * Determines if a given string is valid JSON.
     *
     * @param mixed $string The input to check.
     * @return bool True if the input is a valid JSON string, false otherwise.
     */
    public static function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * Downloads a remote file via its URL and streams it to the browser.
     *
     * This method performs basic sanitization and validation
     * before initiating a file transfer with appropriate headers.
     *
     * @param string $file_url The direct URL to the file.
     * @return void Terminates script execution after sending the file.
     * @since 1.0.0
     */
    public static function downloadUrl($file_url)
    {
        $file_url = filter_var($file_url, FILTER_SANITIZE_URL);
        if (filter_var($file_url, FILTER_VALIDATE_URL) === false) {
            die('URL معتبر نیست!');
        }
        $file_content = @file_get_contents($file_url);
        if ($file_content === false) {
            die('فایل قابل دسترسی نیست یا وجود ندارد.');
        }
        $filename = basename($file_url);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($file_content));
        echo $file_content;
    }

    /**
     * Converts a list of setting objects into an associative array.
     *
     * Automatically decodes JSON values and fills in missing keys
     * using provided defaults.
     *
     * @param array $list_setting Array of objects with `meta_key` and `meta_value`.
     * @param array $meta_keys List of expected keys to ensure are present in output.
     * @param array $default Associative array of fallback values.
     * @return array Cleaned and complete settings array.
     * @since 1.0.0
     */
    public static function listSetting(array $list_setting, $meta_keys, array $default = array()): array
    {
        $new_list_setting = array();
        foreach ($list_setting as $item) {
            $value = $item->meta_value;
            if (is_string($value) && self::isJson($value)) {
                $value = json_decode($value, true);
            }
            $new_list_setting[$item->meta_key] = $value;
            if (in_array($item->meta_key, $meta_keys)) {
                $key = array_search($item->meta_key, $meta_keys);
                unset($meta_keys[$key]);
            }
        }
        $meta_keys = array_values($meta_keys);
        foreach ($meta_keys as $meta_key) {
            if (isset($default[$meta_key])) {
                $new_list_setting[$meta_key] = $default[$meta_key];
            }
        }
        return $new_list_setting;
    }

    /**
     * Checks whether a given page was built using Elementor.
     *
     * Evaluates both metadata and page body classes to determine Elementor usage.
     *
     * @param int $page_id The WordPress page ID to inspect.
     * @return bool True if the page uses Elementor, false otherwise.
     * @since 1.0.0
     */
    public static function CheckElementorUsePage(int $page_id)
    {
        if (empty($page_id) || !is_numeric($page_id)) {
            return false;
        }
        $is_elementor_meta = get_post_meta($page_id, '_elementor_edit_mode', true);
        $classes = get_body_class($page_id);
        $is_elementor_class = in_array('elementor-page', $classes);
        return !empty($is_elementor_meta) || $is_elementor_class;
    }

    /**
     * Checks if the current view is in Elementor's live editor.
     *
     * @return bool True if the current session is in Elementor edit mode.
     * @since 1.0.0
     */
    public static function IsElementorEditor()
    {
        if (Plugin::$instance->editor->is_edit_mode()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates a random alphanumeric string.
     *
     * Useful for tokens, keys, or unique identifiers.
     *
     * @param int $length Desired length of the string. Default is 14.
     * @return string Randomly generated string.
     * @since 1.0.0
     */
    public static function GenerateRandomString($length = 14)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Creates a secure HMAC hash using the plugin’s secret key.
     *
     * @param string $str The input string to hash.
     * @return string The hashed value.
     * @since 1.0.0
     */
    public static function Hash( $str )
    {
        $secretKey = SECURE_AUTH_SALT;
        return hash_hmac('sha256', $str, $secretKey);
    }

    /**
     * Verifies that a hash matches the expected value for a given string.
     *
     * @param string $hash The hash to check.
     * @param string $str The original string to verify.
     * @return bool True if the hash is valid and matches.
     * @since 1.0.0
     */
    public static function VerifyHash( $hash, $str )
    {
        return hash_equals( $hash, self::Hash($str) );
    }
}