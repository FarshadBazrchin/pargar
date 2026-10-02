<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class MarketRegistrationNumber extends Tag {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
		];
	}

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

	public function get_title() {
		return esc_html__( 'شماره ثبت فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-market-registration-number';
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
            $vendor_id = 0;
            if (isset($_GET['print_invoice'])) {
                $order = wc_get_order($_GET['order_id']);
                $vendor_id = get_post_meta($order->get_id(), '_dokan_vendor_id', true);
            } elseif (isset($_GET['print_pre_invoice']) and !WC()->cart->is_empty()) {
                $cart = WC()->cart->get_cart();
                $first_item = reset($cart);
                $product_id = $first_item['product_id'];
                $vendor_id = get_post_field('post_author', $product_id);
            }

            if ($vendor_id) {
                $reg_number = '';
                $possible_keys = [
                    'dokan_company_reg_number',
                    'company_registration_number',
                    'registration_number',
                    'shop_reg_number',
                    'cr_number'
                ];
                foreach ($possible_keys as $key) {
                    if (!empty($store_info[$key])) {
                        $reg_number = sanitize_text_field($store_info[$key]);
                        break;
                    }
                }

                if (!empty($reg_number)) {
                    echo esc_html($reg_number);
                } else {
                    echo pargar_get_setting('market_registration_code', true, '-');
                }
            } else {
                echo pargar_get_setting('market_registration_code', true, '-');
            }

        } else {
            echo pargar_get_setting('market_registration_code', true, '-');
        }

	}


}
