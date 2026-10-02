<?php

namespace Pargar\Includes\Widgets\AllProductsCount;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class AllProductsCount extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-all-products-count-widget';
    }

    public function get_icon() {
        return 'pargar-factor products-count';
    }

    public function get_title() {
        return esc_html__( 'تعداد محصولات سفارش' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_categories() {
        return [ PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( !is_preview() AND !\Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            if ( !isset( $_GET['order_id'] ) AND !isset( $_GET['print_pre_invoice'] ) AND !isset( $_GET['print_invoice'] ) ) {
                return;
            }

            $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            if ( $is_editor_mode OR is_preview() ) {
                include 'Templates/editor.php';
                return;
            }

            if ( isset( $_GET['print_pre_invoice'] ) ) {
                if ( WC()->cart->is_empty() ) {
                    return;
                }

                include 'Templates/pre-invoice.php';
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
                include "Templates/index.php";
            }
        }else {
            include 'Templates/editor.php';
        }

    }

    protected function _register_controls() {
        $this->widget_main_settings();
        $this->widget_styles();
    }

    private function widget_main_settings( ) {
        $this->start_controls_section(
            'widget_main_settings' ,
            [
                'label' => esc_html__( 'تنظیمات' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB ,
            ]
        );

        $this->add_control(
            'pre_value_text' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXT ,
                'default' => esc_html__( 'تعداد محصولات :' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->end_controls_section();
    }

    private function widget_styles( ) {
        $this->start_controls_section(
            'widget_styles' ,
            [
                'label' => esc_html__( 'استایل ویجت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB_STYLE ,
            ]
        );

        $this->add_control(
            'value_space_from_pre_value' ,
            [
                'label' => esc_html__( 'فاصله از پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SLIDER ,
                'default' => [
                    'size' => 3 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .all-products-count-value' => 'margin-inline-start : {{SIZE}}{{UNIT}}' ,
                ]
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('چینش متن', PARGAR_TEXT_DOMAIN_NAME ),
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
                    '{{WRAPPER}} .all-products-count-widget' => 'text-align: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
            ]
        );

        $this->start_controls_tabs(
            'all_products_count_style_tabs'
        );

        $this->start_controls_tab(
            'all_products_count_pre_value_tab' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'pre_value_text_color' ,
            [
                'label' => esc_html__( 'رنگ متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::COLOR ,
                'default' => '#000' ,
                'selectors' => [
                    '{{WRAPPER}} .pre-value-txt' => 'color : {{VALUE}}' ,
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'pre_value_text_typo',
                'selector' => '{{WRAPPER}} .pre-value-txt' ,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'all_products_count_value_tab' ,
            [
                'label' => esc_html__( 'تعداد' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'all_product_count_color' ,
            [
                'label' => esc_html__( 'رنگ' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::COLOR ,
                'default' => '#000' ,
                'selectors' => [
                    '{{WRAPPER}} .all-products-count-value' => 'color : {{VALUE}}' ,
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'all_product_count_typo',
                'selector' => '{{WRAPPER}} .all-products-count-value' ,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

}
