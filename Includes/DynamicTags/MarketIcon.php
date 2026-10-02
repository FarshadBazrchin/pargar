<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Core\DynamicTags\Tag;

class MarketIcon extends Tag {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ,
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY ,
		];
	}

    public function get_group() {
        return [ PARGAR_DYNAMIC_TAG_GROUP_NAME ];
    }

	public function get_title() {
		return esc_html__( 'تصویر فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
	}

	public function get_name() {
		return 'magic-factor-market-icon';
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

		$id = pargar_get_setting( 'website_logo' , true );
        if ( empty( $id ) ) {
            return;
        }
        echo wp_get_attachment_image_url( $id , 'Large' );
	}

}