<?php

namespace Pargar\Includes\Widgets\MarketIcon;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class MarketIcon extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-market-icon-widget';
    }

    public function get_icon() {
        return 'pargar-factor store';
    }

    public function get_title() {
        return esc_html__( 'تصویر فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_categories() {
        return [ PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ];
    }

    protected function render() {
        if ( !is_preview() AND !\Elementor\Plugin::$instance->editor->is_edit_mode() ) {
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

        $settings = $this->get_settings_for_display();
        include "Templates/index.php";
    }

    protected function _register_controls() {
        $this->widget_main_settings();
    }

    private function widget_main_settings( ) {

        $this->start_controls_section(
            'widget_main_settings' ,
            [
                'label' => esc_html__( 'تنظیمات ویجت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB ,
            ]
        );

        $this->add_control(
            'market_icon_notice' ,
            [
                'type' => Controls_Manager::NOTICE ,
                'notice_type' => 'success' ,
                'heading' => esc_html__( 'نکته' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'content' => esc_html__( 'برای افزودن یا تغییر تصویر وارد تنظیمات فاکتور ساز شوید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'market_icon_size' ,
            [
                'label' => esc_html__( 'ابعاد تصویر' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::IMAGE_DIMENSIONS ,
                'default' => [
                    'width' => 125 ,
                    'height' => 125 ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .invoice-website-logo' => 'width : {{WIDTH}}px; height : {{HEIGHT}}px'
                ]
            ]
        );

        $this->end_controls_section();

    }

}
