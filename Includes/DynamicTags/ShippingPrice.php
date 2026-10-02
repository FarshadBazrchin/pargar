<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class ShippingPrice extends Tag {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

    public function get_title() {
        return esc_html__( 'مبلغ حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_name() {
        return 'magic-factor-shipping-price';
    }

    public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            echo wc_price( 15000 );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            $this->pre_order_get_shipping_price();
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if ( empty( $_GET['order_id'] ) ) {
                return;
            }
            $this->order_get_shipping_price();
        }
    }

    private function pre_order_get_shipping_price( ) {
        if ( WC()->cart->is_empty() ) {
            return;
        }

        if ( !WC()->cart->needs_shipping() ) {
            echo '-';
            return;
        }

        echo wc_price( WC()->cart->get_shipping_total() );
    }

    private function order_get_shipping_price( ) {
        $order = wc_get_order( $_GET['order_id'] );
        if ( empty( $order ) ) {
            return;
        }

        if ( empty( $order->get_shipping_total() ) ) {
            return;
        }

        echo wc_price( floatval( $order->get_shipping_total() ) );
    }

}
