<?php

namespace Pargar\Includes\Model;

use Pargar\Includes\Core\Utility;

/**
 * APITokenModel Class
 *
 * This abstract class provides methods for managing API tokens in the database.
 * It includes functionalities for inserting, updating, and retrieving tokens.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
abstract class APITokenModel extends Model
{
    private $str_time = "+5 minutes";
    /**
     * Insert a new API token for a user.
     *
     * @since 1.0.0
     * @param int $user_id The ID of the user.
     * @param string $api_token The API token to be stored.
     * @return int|false Returns the inserted row ID on success, or false on failure.
     */
    protected function insert( int $user_id, string $api_token )  {

        $result = $this->wpdb->Insert( $this->obj_data_base->TBL_API_TOKEN, array(
                'user_id' => $user_id,
                'api_token' => sanitize_text_field( $api_token ),
                'signature' => Utility::Hash( $api_token ),
                'expire_time' => strtotime( $this->str_time )
            )
        );

        if ( $result ){
            return $this->wpdb->insert_id;
        }
        return false;
    }

    /**
     * Update an existing API token for a user.
     *
     * @since 1.0.0
     * @param int $user_id The ID of the user.
     * @param string $api_token The new API token to be stored.
     * @return bool Returns true on successful update, or false on failure.
     */
    protected function update( int $user_id, string $api_token ){
        $result = $this->wpdb->update( $this->obj_data_base->TBL_API_TOKEN,
            array(
                'api_token' => sanitize_text_field( $api_token ) ,
                'signature' => Utility::Hash( $api_token ),
                'expire_time' => strtotime(  $this->str_time  )
            ),
            array(
                'user_id' => $user_id
            )
        );
        if ( $result ){
            return true;
        }
        return false;
    }

    /**
     * Retrieve an API token by user ID.
     *
     * @param int $user_id The ID of the user.
     * @return object|null Returns the token record as an object, or null if no record found.
     */
    protected function getByUserID( int $user_id )
    {
        $query = "SELECT * FROM %i WHERE user_id = %s";
        $prepare_query = $this->wpdb->prepare( $query, $this->obj_data_base->TBL_API_TOKEN, $user_id );
        return $this->wpdb->get_row( $prepare_query );
    }

    /**
     * Retrieve an API token by token string.
     *
     * @param string $api_token The API token string to search for.
     * @return object|null Returns the token record as an object, or null if no record found.
     */
    protected function getByToken( string $api_token )
    {
        $query = "SELECT * FROM %i WHERE api_token = %s";
        $prepare_query = $this->wpdb->prepare( $query, $this->obj_data_base->TBL_API_TOKEN, sanitize_text_field( $api_token ) );
        return $this->wpdb->get_row( $prepare_query );
    }

    /**
     * Retrieve an API token by Signature.
     *
     * @param string $signature The signature string to search for.
     * @return object|null Returns the token record as an object, or null if no record found.
     */
    protected function getBySignature( string $signature )
    {
        $query = "SELECT * FROM %i WHERE signature = %s";
        $prepare_query = $this->wpdb->prepare( $query, $this->obj_data_base->TBL_API_TOKEN, sanitize_text_field( $signature ) );
        return $this->wpdb->get_row( $prepare_query );
    }
}