<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class OrderStatus extends Tag {

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
        return esc_html__( 'وضعیت سفارش' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_name() {
        return 'magic-factor-order-status';
    }

    public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            esc_html_e( 'در حال انجام' , PARGAR_TEXT_DOMAIN_NAME );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            if ( WC()->cart->is_empty() ) {
                return;
            }
            echo '-';
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if ( empty( $_GET['order_id'] ) ) {
                return;
            }
            $order = wc_get_order( $_GET['order_id'] );
            if ( empty( $order ) ) {
                return;
            }
            if ( empty( $order->get_date_created() ) ) {
                echo '-';
                return;
            }

            if ( isset( wc_get_order_statuses()[ 'wc-' . $order->get_status() ] ) ) {
                echo wc_get_order_statuses()['wc-' . $order->get_status()];
            } elseif(isset(wc_get_order_statuses()[$order->get_status()])) {
                echo wc_get_order_statuses()[$order->get_status()];
            }
        }
    }

}
