<?php
namespace Pargar\Includes\Model;

use Pargar\Includes\Core\Utility;

/**
 *
 * PargarSettingModel
 *
 * This class is specifically for connecting to the database
 * and all information related to the settings is handled in this class.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
abstract class PargarSettingModel extends Model
{

    /**
     * In this function, you update the settings
     * @since 1.0.0
     * @param string $setting_key Pass the key you set in the database or in the controls to this parameter
     * @param mixed $value_key  Pass the value you want to update to this parameter
     * @return bool If updated, the return value is true, otherwise false.
     */
    protected function update( string $setting_key, $value_key )  {
        if ( is_array($value_key) || is_object($value_key) ){
            $value_key = json_encode( $value_key );
        }
        $result = $this->wpdb->update( $this->obj_data_base->TBL_SETTING,
            array(
                'setting_value' => $value_key ,
            ),
            array(
                'setting_key' => $setting_key
            )
        );
        if ( $result ){
            return true;
        }
        return false;
    }

    /**
     * In this function, you insert the settings
     * @since 1.0.0
     * @param string $setting_key Pass the key you set in the database or in the controls to this parameter
     * @param mixed $value_key  Pass the value you want to insert to this parameter
     * @return bool If inserted, the return value is true, otherwise false.
     */
    protected function insert( string $setting_key, $value_key ){
        if ( is_array( $value_key ) or is_object( $value_key) ) {
            $value_key = json_encode( $value_key );
        }

        $result = $this->wpdb->Insert( $this->obj_data_base->TBL_SETTING, array(
                'setting_key' => $setting_key,
                'setting_value' => $value_key,
            )
        );

        if ( $result ){
            return  $this->wpdb->insert_id;
        }
        return false;
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
    protected function getSingle( string $setting_key, bool $return_value = false, $default = "" )
    {
        $query = "SELECT * FROM %i WHERE setting_key = %s";
        $prepare_query = $this->wpdb->prepare( $query, $this->obj_data_base->TBL_SETTING, $setting_key );
        $result = $this->wpdb->get_row( $prepare_query );

        $value = $result ;
        if ( isset( $result->setting_value ) && $return_value ){
            if ( Utility::isJson($result->setting_value)) {
                $value = json_decode($result->setting_value, true);
            }else{
                $value = $result->setting_value;
            }
        }
        if( is_null( $value ) ){
            $value =  $default;
        }
        return $value;
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
    protected function getListSetting( array $setting_key, bool $pares_setting = false ,array $default = array()) {
        $keys_prepare = implode( ', ', array_fill( 0 , count( $setting_key ) ,  '%s' ) );
        $query = "SELECT * FROM %i WHERE setting_key IN ({$keys_prepare})";
        $prepare_query = $this->wpdb->prepare( $query, $this->obj_data_base->TBL_SETTING, ...$setting_key );

        if ( $pares_setting ){
            $result = $this->wpdb->get_results( $prepare_query );
            if ( is_object( $result ) OR is_array( $result ) ){
                return Utility::listSetting( $result, $setting_key, $default  );
            }else{
                $result = array();
                foreach ( $setting_key as  $value ){
                    if ( isset( $default[$value] ) ){
                        $result[$value] = $default[$value];
                    }
                }
                return $result;
            }
        }
        return $this->wpdb->get_results( $prepare_query );
    }

}