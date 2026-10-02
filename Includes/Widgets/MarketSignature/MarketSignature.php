<?php

namespace Pargar\Includes\Widgets\MarketSignature;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class MarketSignature extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-market-signature-widget';
    }

    public function get_icon() {
        return 'pargar-factor store';
    }

    public function get_title() {
        return esc_html__( 'امضاء فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
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
            'get_signature_from' ,
            [
                'label' => esc_html__( 'دریافت امضا از' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'from_setting' => esc_html__( 'دریافت از تنظیمات' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'manuel' => esc_html__( 'آپلود دستی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
                'default' => 'from_setting'
            ]
        );

        $this->add_control(
            'media_signature' ,
            [
                'label' => esc_html__( 'انتخاب فایل' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::MEDIA ,
                'condition' => [
                    'get_signature_from' => 'manuel'
                ]
            ]
        );

        $this->add_control(
            'signature_image_size' ,
            [
                'label' => esc_html__( 'ابعاد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::IMAGE_DIMENSIONS ,
                'default' => [
                    'width' => 125 ,
                    'height' => 125
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .market-signature-img' => 'width : {{WIDTH}}; height: {{HEIGHT}}' ,
                ]
            ]
        );

        $this->add_control(
            'object_fit_type' ,
            [
                'label' => esc_html__( 'object-fit' ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'contain' => esc_html__( 'contain' ) ,
                    'cover' => esc_html__( 'cover' )  ,
                    'fill' => esc_html__( 'fill' ) ,
                    'scale-down' => esc_html__( 'scale-down' ) ,
                    'unset' => esc_html__( 'unset' ) ,
                ] ,
                'default' => 'contain' ,
                'selectors' => [
                    '{{WRAPPER}} .market-signature-img' => 'object-fit : {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('چینش', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('راست', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => esc_html__('وسط', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('چپ', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'toggle' => true ,
                'selectors' => [
                    '{{WRAPPER}} .market-signature-widget' => 'text-align: {{VALUE}};',
                ] ,
                'default' => 'start'
            ]
        );

        $this->end_controls_section();
    }

}
