<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class MarketUrl extends Tag {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
		];
	}

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

	public function get_title() {
		return esc_html__( 'url فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-market-url-address';
	}

	public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( !is_preview() AND !$is_editor_mode ) {

            if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
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

        echo pargar_get_setting( 'market_address' , true , '-' );
	}

}
