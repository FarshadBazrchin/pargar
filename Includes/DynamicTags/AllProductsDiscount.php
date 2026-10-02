<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class AllProductsDiscount extends Tag {

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
		return esc_html__( 'مجموع تخفیفات سفارش' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-all-products-discount';
	}

	public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            echo wc_price( floatval( 150000 ) );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            $this->pre_order_discount_calculation();
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if ( empty( $_GET['order_id'] ) ) {
                return;
            }
            $this->order_discount_calculation();
        }
	}

    private function pre_order_discount_calculation( ) {
        if ( WC()->cart->is_empty() ) {
            return;
        }
        $items = WC()->cart->get_cart();
        $total = 0;
        foreach( $items as $values ) {
            $product = $values['data'];
            if ( !$product->is_on_sale() ) {
                continue;
            }
            $regular = $product->get_regular_price();
            $discount = $product->get_sale_price();
            $total += floatval( $regular ) - floatval( $discount );
        }
        if ( $total <= 0 ) :
            echo '-';
        else:
            echo wc_price( floatval( $total ) );
        endif;
    }

    private function order_discount_calculation( ) {
        $order = wc_get_order( $_GET['order_id'] );
        if ( empty( $order ) ) {
            return;
        }
        $total = 0;
        foreach ( $order->get_items() as $item ) {
            $was_on_sale = $item->get_meta( 'pargar_was_on_sale' , true );
            $discount = 0;
            $regular = 0;
            if ( $was_on_sale ) {
                $regular = $item->get_meta( 'pargar_regular_price' , true );
                $discount = $item->get_meta( 'pargar_sale_price' , true );
            }
            $total += floatval( $regular ) - floatval( $discount );
        }

        if ( $total <= 0 ) {
            echo '-';
            return;
        }
        echo wc_price( floatval( $total ) );
    }

}
