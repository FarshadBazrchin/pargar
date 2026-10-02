<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class OrderID extends Tag {

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
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ,
		];
	}

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

	public function get_title() {
		return esc_html__( 'ID سفارش' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-order-id';
	}

	public function render() {
        $pre_code = pargar_get_setting( 'pre_value_order_code' , true , '' );
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            echo $pre_code . '47845';
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
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
            echo $pre_code . $order->get_id();
        }
	}

}
