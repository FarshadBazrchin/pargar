<?php

namespace Pargar\Includes\Processing\API;

use Pargar\Includes\Core\Utility;
use Pargar\Includes\Model\APITokenModel;

/**
 * APIToken
 * Manages API token authentication and signature lifecycle using the Singleton pattern.
 *
 * This class extends APITokenModel and is responsible for creating, validating,
 * and storing API token signatures tied to WordPress users.
 *
 * Responsibilities:
 * - Generate new secure tokens tied to user sessions.
 * - Verify incoming signatures for validity and expiration.
 * - Ensure a single instance via Singleton to manage token consistency.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */

class APIToken extends APITokenModel
{

    /**
     * Holds the Singleton instance
     *
     * @var self|null $_instance Stores the instance of the class
     */
    protected static $_instance = null;

    /**
     * Creates or retrieves the Singleton instance of the class
     * @return self
     * @since 1.0.0
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Validates a signature
     *
     * This method checks the provided signature and determines its validity.
     *
     * @since 1.0.0
     * @param string $signature The signature to validate
     * @return bool True if the signature is valid, false otherwise
     */

    public function verify( string $signature )
    {
        $result = $this->getBySignature( $signature );

        if ( !isset( $result->id ) ){
            return false ;
        }

        if ( Utility::VerifyHash( $signature, $result->api_token ) ){
            if ( time() > $result->expire_time ){
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Creates a new signature for the current user
     *
     * This method generates a random string and associates it with the current user.
     *
     * @since 1.0.0
     * @return string The hashed string of the signature
     */
    public function createSignature()
    {
        $api_token = Utility::GenerateRandomString();

        $user_id = get_current_user_id();
        $result = $this->getByUserID( $user_id );

        if ( !isset( $result->id ) ){
            while ( true ){
                $result = $this->getByToken( $api_token );
                if ( !isset( $result->id ) ){
                    $this->insert( $user_id, $api_token );
                    break;
                }
            }
        }else{
            $this->update( $user_id, $api_token );
        }

        return Utility::hash( $api_token );
    }
}