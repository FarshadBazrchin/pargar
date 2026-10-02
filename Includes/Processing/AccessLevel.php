<?php

namespace Pargar\Includes\Processing;


use Pargar\Includes\Processing\Admin\PargarSetting;

/**
 * Class AccessLevel
 *
 * This class handles the access level checks for users in the system.
 * It determines if the current user has the required permissions based on their role.
 *
 * Key functionalities:
 * - Verifies the current user's existence.
 * - Checks if the user is an administrator.
 * - Validates the user's role against a custom list of access levels.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class AccessLevel
{
    /**
     * A static method to validate whether the current user has the necessary access level.
     * It returns true for administrators or users explicitly allowed in the access level settings.
     *
     * @since 1.0.0
     * @return bool True if the user has access, false otherwise.
     */
    public static function CheckUser()
    {
        $current_user = wp_get_current_user();
        if (!isset($current_user->ID)) {
            return false;
        }

        if (in_array('administrator', $current_user->roles)) {
            return true;
        }

        $list_roles = PargarSetting::instance()->get('access_level', true );

        if ( isset( $list_roles[ $current_user->roles[0] ] ) && $list_roles[ $current_user->roles[0] ] == "on" ) {
            return true;
        }
        return false;
    }

    /**
     * Checks whether a given key (e.g. "invoice") is allowed for admin action display.
     *
     * This method validates whether a specific button or UI element related to
     * WooCommerce invoice functionality should be shown based on current settings.
     * The `"invoice"` key is always allowed. Other keys depend on the
     * `"invoice_issuance_modes"` configuration in plugin settings.
     *
     * @since 1.0.0
     * @param string $key The feature key to check (e.g. "packing_invoice")
     * @return bool True if the key is enabled; false otherwise.
     */
    public static function CheckButtonAdminWc( $key )
    {
        if ( in_array( $key, array( 'invoice' ) ) ){
            return true;
        }

        $list_roles = PargarSetting::instance()->get('invoice_issuance_modes', true );

        if ( isset( $list_roles[ $key ] ) && $list_roles[ $key ] == "on" ) {
            return true;
        }

        return false;
    }
}