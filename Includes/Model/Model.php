<?php

namespace Pargar\Includes\Model;

use Pargar\Includes\Core\DataBase;

/**
 * Model
 *
 * Abstract base model providing database access for child classes.
 *
 * This class initializes the primary database connection and WordPress `$wpdb` instance.
 * It's meant to be extended by other models in the Pargar plugin and offers a common data layer.
 *
 * Responsibilities:
 * - Sets up access to custom DataBase wrapper class.
 * - Provides protected `$wpdb` for direct WordPress database operations.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
abstract class Model
{
    /**
     * Instance of the custom database wrapper class.
     *
     * @var DataBase
     */
    protected DataBase $obj_data_base;

    /**
     * Native WordPress database object.
     *
     * @var \wpdb
     */
    protected \wpdb $wpdb;

    /**
     * Protected constructor to initialize database objects.
     *
     * This ensures that extending model classes automatically receive access to both
     * the custom `DataBase` instance and the native WordPress `$wpdb` object.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->obj_data_base = DataBase::instance();
        $this->wpdb = DataBase::instance()->WP_DATA_BASES;
    }


}