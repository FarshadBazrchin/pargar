<?php
namespace Pargar\Includes\Processing\Admin;

use Pargar\Includes\Model\PargarSettingModel;

/**
 *
 * AjaxSetting
 *
 * This class is for settings panels.
 * That is, the requests that are submitted in the WordPress Hooks are being developed here
 * And all the settings panel Ajax requests are processed in this class.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class AjaxSetting extends PargarSettingModel
{

    /**
     * Inherits and initializes the parent constructor.
     *
     * Ensures the base class setup is executed when the child class is instantiated.
     */
    public function __construct() {
        parent::__construct();
    }


    /**
     * hook ajax
     * @since 1.0.0
     * @return void
     */
    public function hookAjax()
    {
        add_action("wp_ajax_magic_factor_save_setting", array( $this, 'saveSetting' ) );
        add_action("wp_ajax_pargar_default_setting", array( $this, 'defaultSetting' ) );
    }


    /**
     * In this function, save the settings sent by ajax in the database.
     * We access the database using the functions of the parent class.
     *
     * @since 1.0.0
     * @return void
     */
    public function saveSetting()
    {
        if ( !isset( $_POST['setting'] ) ) {
            wp_send_json_error(
                array(
                    'type' => "not_found_setting"
                )
            );
        }

        if ( wp_verify_nonce( $_POST['nonce'], 'magic_factor_setting' ) ) {
            if (  $_POST['setting']['access_level']['administrator'] == "off" ) {
                $_POST['setting']['access_level']['administrator'] = "on";
            }
            foreach ( $_POST['setting'] as $key => $value ) {
                $setting = $this->getSingle( $key );
                if ( isset( $setting->id ) ){
                    $this->update( $key, $value );
                }else{
                    $this->insert( $key, $value );
                }
            }

            wp_send_json_success();
        }else{
            wp_send_json_error(
                array(
                    'type' => "nonce_error"
                )
            );
        }
    }

    /**
     * Restores the plugin's settings to their default values.
     *
     * Validates the security nonce before executing the reset. Delegates
     * the default restoration logic to the PargarSetting singleton instance.
     *
     * Sends a JSON success or error response based on nonce verification.
     *
     * @since 1.0.0
     * @return void
     */
    public function defaultSetting()
    {
        if ( wp_verify_nonce( $_POST['nonce'], 'magic_factor_setting' ) ) {
            PargarSetting::instance()->default();
            wp_send_json_success();
        }else{
            wp_send_json_error(
                array(
                    'type' => "nonce_error"
                )
            );
        }
    }
}