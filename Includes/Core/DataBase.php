<?php

namespace Pargar\Includes\Core;

defined('ABSPATH') || exit;

/**
 * Handles custom database operations and table management for the plugin.
 *
 * This class:
 * - Provides access to native WordPress database through `$wpdb`
 * - Initializes custom table names used across the plugin
 * - Supports table creation and deletion through dbDelta and raw queries
 * - Implements Singleton pattern for global consistency
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class DataBase
{
    public $WP_DATA_BASES;
    public $TBL_SETTING;
    public $TBL_API_TOKEN;

    /**
     * Holds the Singleton instance
     *
     * @var self|null $_instance Stores the instance of the class
     */
    protected static $_instance = null;

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
     * Initializes WordPress database object and custom table names.
     *
     * Assigns global `$wpdb` to internal property and sets prefixed table names
     * used by plugin settings and API token logic.
     * @since 1.0.0
     */
    public function __construct()
    {
        global $wpdb;

        $this->WP_DATA_BASES = $wpdb;
        $this->TBL_SETTING = $wpdb->prefix . "pargar_factor_setting";
        $this->TBL_API_TOKEN = $wpdb->prefix . "pargar_factor_api_token";
    }

    /**
     * Creates plugin-specific database tables if they do not exist.
     *
     * Uses WordPress `dbDelta()` to safely create or update the following tables:
     * - pargar_factor_setting
     * - pargar_factor_api_token
     *
     * Table structure includes unique constraints to prevent duplication.
     *
     * @return void
     * @since 1.0.0
     */
    public function createTable()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $this->WP_DATA_BASES->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS {$this->TBL_SETTING} (
                `id` int(255)  NOT NULL AUTO_INCREMENT,
                `setting_key` VARCHAR(100)  NOT NULL,
                `setting_value` text  NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`setting_key`)
            ) {$charset_collate};";
        dbDelta($sql);
        $charset_collate = $this->WP_DATA_BASES->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS {$this->TBL_API_TOKEN} (
                `id` int(255)  NOT NULL AUTO_INCREMENT,
                `user_id` int(255)  NOT NULL,
                `api_token` text  NOT NULL,
                `signature` text  NOT NULL,
                `expire_time` VARCHAR(50)  NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`user_id`),
                UNIQUE (`api_token`),
                UNIQUE (`signature`)
            ) {$charset_collate};";
        dbDelta($sql);
    }

    /**
     * Drops the plugin's settings table from the database.
     *
     * Useful during uninstall processes or manual cleanups.
     *
     * @return void
     * @since 1.0.0
     */
    public function deleteTable()
    {
        $this->WP_DATA_BASES->query("DROP TABLE IF EXISTS {$this->TBL_SETTING}");
    }
}