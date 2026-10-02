<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class MarketPhone extends Tag {

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
		return esc_html__( 'شماره تماس فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-market-phone';
	}

	public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( !is_preview() AND !$is_editor_mode ) {

            if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
                return;
            }

            if ( isset( $_GET['packing_invoice'] ) ) {
                return;
            }

            if ( isset( $_GET['print_pre_invoice'] ) ) {
                if ( WC()->cart->is_empty() ) {
                    return;
                }
            }

            if ( isset( $_GET['print_invoice'] ) ) {
                if ( empty( $_GET['order_id'] ) ) {
                    return;
                }
            }
        }

        $dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info", true, "off");

        if ( function_exists( 'dokan_get_store_info' ) &&  $dokan_show_vendor_info == "on"  ) {
            $store_phone = '';
            $items = [];
            if (isset($_GET['print_pre_invoice'])) {
                $items = WC()->cart->get_cart();
            } elseif (isset($_GET['print_invoice'])) {
                $order = wc_get_order($_GET['order_id']);
                $items = $order->get_items();
            }

            foreach ($items as $item) {
                if (isset($_GET['print_pre_invoice'])) {
                    $product_id = $item['product_id'];
                } elseif (isset($_GET['print_invoice'])) {
                    $product_id = $item->get_product_id();
                }

                $seller_id = get_post_field('post_author', $product_id);
                $store_info = dokan_get_store_info($seller_id);

                if (!empty($store_info['phone'])) {
                    $store_phone = $store_info['phone'];
                    break;
                }

            }

            if (!empty($store_phone)) {
                echo $store_phone;
            } else {
                echo pargar_get_setting('market_phone_number', true, '-');
            }

        } else {
            echo pargar_get_setting('market_phone_number', true, '-');
        }

	}

}
