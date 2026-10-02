<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class PrintDate extends Tag {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ,
        ];
    }

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

    public function get_title() {
        return esc_html__( 'تاریخ چاپ' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_name() {
        return 'magic-factor-printed-date';
    }

    public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $locale = get_locale();
        $format = pargar_get_setting( 'invoice_date_format' , true , 'Y/m/d' );
        if ( $is_editor_mode OR is_preview() ) {
            esc_html_e( '1404/3/13' , PARGAR_TEXT_DOMAIN_NAME );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( $format === 'from_wb' ) {
            $format = get_option('date_format');
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            if ($locale === 'fa_IR') {
                require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
                echo jdate( $format , time() );
            } else {
                echo date( $format , time() );
            }
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if (empty($_GET['order_id'])) {
                return;
            }
            $order = wc_get_order($_GET['order_id']);
            if (empty($order)) {
                return;
            }

            if ($locale === 'fa_IR') {
                require_once PARGAR_PATH.'Includes/Processing/Jalali/jdf.php';
                echo jdate( $format , time() );
            } else {
                echo date( $format , time() );
            }
        }
    }

}
