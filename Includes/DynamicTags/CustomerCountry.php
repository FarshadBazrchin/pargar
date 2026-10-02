<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use WC_Countries;

class CustomerCountry extends Tag {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    public function get_name() {
        return 'magic-factor-customer-country';
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
		return esc_html__( 'کشور خریدار' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            esc_html_e( 'ایران' , PARGAR_TEXT_DOMAIN_NAME );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            $this->pre_order_get_country();
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if ( empty( $_GET['order_id'] ) ) {
                return;
            }
            $this->order_get_country();
        }
	}

    private function pre_order_get_country( ) {
        if ( WC()->cart->is_empty() ) {
            return;
        }
        $countries = new WC_Countries();
        $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];

        echo $country_name;
    }

    private function order_get_country( ) {
        $order = wc_get_order( $_GET['order_id'] );
        if ( empty( $order ) ) {
            return;
        }
        $countries = new WC_Countries();
        $country_name = $countries->get_countries()[$order->get_billing_country()];

        echo $country_name;
    }

}
