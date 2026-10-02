<?php

namespace Pargar\Includes\Processing\Admin;

use Pargar\Includes\Model\PargarSettingModel;

/**
 * PargarSetting
 * Manages global plugin settings using a Singleton pattern.
 *
 * This class extends PargarSettingModel and provides:
 * - Centralized access to single or grouped settings.
 * - Default value restoration for initial setup or reset.
 * - Simplified retrieval with optional fallbacks.
 *
 * Common Use Cases:
 * - Fetching or updating user-defined configuration.
 * - Resetting the plugin to factory defaults.
 * - Managing conditional UI behavior or invoice output settings.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class PargarSetting extends PargarSettingModel
{

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
     * Resets all plugin settings to their default values.
     *
     * This method defines a list of default configuration values related to invoices,
     * access levels, appearance, and system behavior. It checks if each setting exists,
     * and either updates or inserts it into the database accordingly.
     *
     * Used when restoring factory settings or initializing plugin state.
     *
     * @since 1.0.0
     * @return void
     */
    public function default()
    {
        $list = array(
            'website_logo' => '',
            'website_title' => get_bloginfo('name'),
            'market_address' => '',
            'market_economical_code' => '',
            'market_national_code' => '',
            'market_registration_code' => '',
            'website_email' => '',
            'market_phone_number' => '',
            'market_postcode' => '',
            'website_url' => get_site_url() ,
            'seller_signature' => '',
            'include_invoice_link_in_email' => 'off',
            'print_invoice_for_user_panel' => 'off',
            'invoice_issuance_modes' => [
                'packing_invoice' => 'on',
                'pre_invoice' => 'on',
                'product_label_invoice' => 'on',
                'address_label_invoice' => 'on',
            ],
            'access_level' => array(
                'administrator' => 'on',
                'editor' => 'off',
                'author' => 'off',
                'contributor' => 'off',
                'subscriber' => 'off',
                'customer' => 'off',
                'shop_manager' => 'off',
            ),
            'invoice_template_id' => apply_filters('pargar_default_invoice_template', 'tm1' ),
            'invoice_include_product_id' => 'on',
            'invoice_product_image' => 'on',
            'invoice_product_discount' => 'off',
            'invoice_order_code_type' => 'id',
            'invoice_method_address' => 'billing',
            'invoice_date_format' => 'Y/m/d H:i',
            'pre_value_order_code' => '',
            'invoice_water_mark' => 'off',
            'success_water_mark' => '',
            'restitution_water_mark' => '',
            'canceled_water_mark' => '',
            'seller_notice_text' => '',
            'invoice_custom_css' => '',
            'pre_invoice_show_in_cart_status' => 'on',
            'pre_invoice_show_in_cart_checkout' => 'on',
            'pre_invoice_show_payment_url' => 'off' ,
            'pre_invoice_water_mark' => '',
            'pre_invoice_footer_text' => '',
            'pre_invoice_custom_css' => '',
            'output_type_pdf' => 'browser',
            'dokan_show_vendor_info' => "off",
            'dokan_show_vendor_btn_invoice' => "off",
            'market_logo_size' => "500",
            'address_label_logo_size' => "180",

            'address_label_custom_css' =>  apply_filters('pargar_default_address_label_template', 'template1' ),
            'address_label_template_id' => "",

            'screenshot_invoice_png' => "off"
        );

        foreach ( $list as $key => $value ) {
            $setting = $this->getSingle( $key );
            if (!isset( $setting->id ) ){
                $this->insert( $key, $value );
            }
        }
    }

    /**
     * Retrieves a setting value from the database.
     * This function retrieves the value of a setting from the database based on the provided key (`setting_key`).
     * If the setting is not found in the database, the provided default value (`default`) is returned.
     * This function utilizes the `getSingle` function to fetch the setting value.
     * @since 1.0.0
     * @param string $setting_key The key of the setting to retrieve.
     * @param bool $return_value If `true`, the raw setting value will be returned directly.
     *                            If `false`, an object containing the setting value will be returned.
     *                            Default: `false`.
     * @param mixed $default The default value to return if the setting with the specified key is not found in the database.
     *                        Default: `null`.
     * @return array|mixed|object|\stdClass|string|null
     *
     */
    public function get( string $setting_key, bool $return_value = false, $default = null)
    {
        return $this->getSingle(  $setting_key, $return_value , $default  );
    }

    /**
     * Retrieves multiple settings from the database as a group.
     *
     * This function retrieves multiple settings from the database based on the provided array of setting keys.
     * You can specify default values for settings that might not exist in the database.
     * @since 1.0.0
     * @param string $setting_key An array of setting keys to retrieve.
     * @param bool $pares_setting If `true`, the raw setting values will be returned directly.
     *                             If `false`, an object containing the setting values will be returned.
     *                             Default: `false`.
     * @param array $default An associative array of default values for settings not found in the database.
     *                        The keys of this array should match the setting keys. Default: An empty array.
     * @return array|object|\stdClass[]|null
     */
    public function getGroup( array $setting_key, bool $pares_setting = false ,array $default = array())
    {
        return $this->getListSetting(  $setting_key, $pares_setting , $default  );
    }
}