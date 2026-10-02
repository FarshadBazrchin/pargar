<?php

namespace Pargar\Includes\Widgets\SellerNote;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class SellerNote extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-seller-note-widget';
    }

    public function get_icon() {
        return 'pargar-factor customer-note';
    }

    public function get_title() {
        return esc_html__( 'یادداشت فروشنده' , PARGAR_TEXT_DOMAIN_NAME );
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

                $notes = $this->get_notes();
                if ( $settings['seller_note_hide_empty_status'] !== 'yes' ) {
                    if ( empty( $notes ) ) {
                        return;
                    }
                }
                include "Templates/index.php";
            }
        }else {
            include 'Templates/editor.php';
        }
    }

    protected function _register_controls() {
        $this->widget_main_settings();
        $this->widget_main_styles();
    }

    private function get_notes( ) {
        return wc_get_order_notes([
            'order_id' => $_GET['order_id'] ,
            'type' => 'customer' ,
        ]);
    }

    private function widget_main_settings( ) {
        $this->start_controls_section(
            'widget_main_settings' ,
            [
                'label' => esc_html__( 'تنظیمات اصلی' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'seller_note_pre_value_text' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
                'default' => esc_html__( 'یادداشت های فروشنده :' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'seller_note_hide_empty_status' ,
            [
                'label' => esc_html__( 'نمایش در صورت خالی بودن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'seller_note_replacement' ,
            [
                'label' => esc_html__( 'متن جایگزین' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
                'default' => '-' ,
            ]
        );

        $this->end_controls_section();
    }

    private function widget_main_styles( ) {
        $this->start_controls_section(
            'widget_main_styles' ,
            [
                'label' => esc_html__( 'استایل های ویجت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB_STYLE ,
            ]
        );

        $this->add_control(
            'pre_value_space' ,
            [
                'label' => esc_html__( 'فاصله از پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SLIDER ,
                'default' => [
                    'size' => 0 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .seller-note-value' => 'margin-inline-start : {{SIZE}}{{UNIT}}' ,
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
                    '{{WRAPPER}} .seller-note-widget' => 'text-align: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
            ]
        );

        $this->start_controls_tabs(
            'seller_note_value_pre_value_style_tabs'
        );

        $this->start_controls_tab(
            'seller_note_pre_value_style_tab' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'seller_note_pre_value_color' ,
            [
                'label' => esc_html__( 'رنگ' , PARGAR_TEXT_DOMAIN_NAME ) ,
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
                'name' => 'seller_note_pre_value_typo',
                'selector' => '{{WRAPPER}} .pre-value-txt' ,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'seller_note_value_style_tab' ,
            [
                'label' => esc_html__( 'یادداشت' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'seller_note_value_color' ,
            [
                'label' => esc_html__( 'رنگ' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::COLOR ,
                'default' => '#000' ,
                'selectors' => [
                    '{{WRAPPER}} .seller-note-value' => 'color : {{VALUE}}' ,
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'seller_note_value_typo',
                'selector' => '{{WRAPPER}} .seller-note-value' ,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

}
