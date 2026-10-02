<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use WC_Countries;

class CustomerAddress2 extends Tag {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    public function get_name() {
        return 'magic-factor-customer-address-2';
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
		return esc_html__( 'آدرس دوم خریدار' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function render() {
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $is_editor_mode OR is_preview() ) {
            esc_html_e( 'ایران - خوزستان - شوشتر' , PARGAR_TEXT_DOMAIN_NAME );
            return;
        }

        if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            $this->pre_order_get_address();
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            if ( empty( $_GET['order_id'] ) ) {
                return;
            }
            $this->order_get_address();
        }
	}

    private function pre_order_get_address( ) {
        if ( WC()->cart->is_empty() ) {
            return;
        }
        $countries = new WC_Countries();
        $country_states = $countries->get_states( WC()->customer->get_billing_country() );
        $state_name = $country_states[WC()->customer->get_billing_state()];
        $country_name = $countries->get_countries()[WC()->customer->get_billing_country()];
        $city_name = WC()->customer->get_billing_city();

        echo "{$country_name} - {$state_name} - {$city_name} - " . WC()->customer->get_billing_address_2();
    }

    private function order_get_address( ) {
        $order = wc_get_order( $_GET['order_id'] );
        if ( empty( $order ) ) {
            return;
        }
        $countries = new WC_Countries();
        $country_states = $countries->get_states( $order->get_billing_country() );
        $state_name = $country_states[$order->get_billing_state()];
        $country_name = $countries->get_countries()[$order->get_billing_country()];
        $city_name = $order->get_billing_city();

        echo "{$country_name} - {$state_name} - {$city_name} - {$order->get_billing_address_2()}";
    }

}
