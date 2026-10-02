<?php
/**
 * Plugin Name: افزونه پرگار
 * Description: پرگار، بهترین افزونه صدور فاکتور برای سفارش در ووکامرس
 * Version: 1.0.2
 * Author: Code Art
 * Author URI: https://www.rtl-theme.com/author/code_art/
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Text Domain: magical-factor
 * Domain Path: /languages/
 * Requires Plugins: woocommerce
 */

defined('ABSPATH') || exit;

define('PARGAR_PATH', plugin_dir_path(__FILE__) );
define('PARGAR_URL', plugin_dir_url(__FILE__) );
define('PARGAR_VERSION', '1.0.2' );
define('PARGAR_VERTION_CODE', 3 );
define('PARGAR_PATH_TEMPLATE', plugin_dir_path(__FILE__) . "Includes/templates" );
define('PARGAR_TEXT_DOMAIN_NAME', 'pargar-textdomain' );
define('PARGAR_UNIQUE_TEMPLATE_NAEM', 'pargar_factor' );
define('PARGAR_DYNAMIC_TAG_GROUP_NAME', 'pargar_dt_group' );
define('PARGAR_ELEMENTOR_WIDGET_GROUP_NAME', 'pargar_ew_group' );

require 'pargar_autoloader.php';
require 'helper.php';

\Pargar\Includes\Core\Core::instance();


function update_elementor_cpt_support() {
    $current_support = get_option( 'elementor_cpt_support', array() );
    if ( ! in_array( 'magic_factor', $current_support ) ) {
        $current_support[] = 'magic_factor';
        update_option( 'elementor_cpt_support', $current_support );
    }
}

add_action( 'init', 'update_elementor_cpt_support' );