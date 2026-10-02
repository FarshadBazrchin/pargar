<?php

namespace Pargar\Includes\Widgets\WaterMark;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class WaterMark extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-water-mark-widget';
    }

    public function get_icon() {
        return 'pargar-factor water-mark';
    }

    public function get_title() {
        return esc_html__( 'واترمارک' , PARGAR_TEXT_DOMAIN_NAME );
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
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( is_preview() OR $is_editor_mode ) {
            include 'Templates/preview.php';
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

            if ( !isset( $_GET['order_id'] ) ) {
                return;
            }

            $order = wc_get_order($_GET['order_id']);
            if (empty($order)) {
                return;
            }

            include "Templates/index.php";
        }
    }

    protected function _register_controls() {
        $this->widget_main_settings();
        $this->widget_main_styles();
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
            'water_mark_status_notice' ,
            [
                'type' => Controls_Manager::NOTICE ,
                'notice_type' => 'success' ,
                'heading' => esc_html__( 'نکته' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'content' => esc_html__( 'این ویجت وضعیت سفارش را به صورت واتر مارک برمیگرداند' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $options = [];
        foreach ( wc_get_order_statuses() as $key => $status ) {
            $options[$key] = $status;
        }
        $options['pre_invoice'] = esc_html__( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME );

        $this->add_control(
            'editor_mode_show_status' ,
            [
                'label' => esc_html__( 'نمایش وضعیت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => $options ,
                'default' => 'pre_invoice' ,
                'description' => esc_html__( 'این ویژگی روی خروجی تاثیری ندارد. فقط جهت ویرایش بهتر و نمایش تغییرات در حالت ویرایش  صفحه است.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'show_wc_status' ,
            [
                'label' => esc_html__( 'نمایش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__( 'در صورتی که نمیخواهید واتر مارک در این وضعیت به نمایش در بیاید، این گزینه را غیرفعال کنید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $repeater->add_control(
            'water_mark_type' ,
            [
                'label' => esc_html__( 'نوع واتر مارک' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'text' => esc_html__( 'تگ html' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'image' => esc_html__( 'تصویر' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
                'default' => 'text' ,
                'condition' => [
                    'show_wc_status' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'water_mark_image' ,
            [
                'label' => esc_html__( 'انتخاب تصویر' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::MEDIA ,
                'condition' => [
                    'water_mark_type' => 'image' ,
                    'show_wc_status' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'water_mark_image_size' ,
            [
                'label' => esc_html__( 'ابعاد تصویر' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::IMAGE_DIMENSIONS ,
                'default' => [
                    'width' => 200 ,
                    'height' => 150 ,
                ] ,
                'condition' => [
                    'water_mark_type' => 'image' ,
                    'show_wc_status' => 'yes'
                ] ,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .water-mark-image' => 'max-width :{{WIDTH}}px; max-height : {{HEIGHT}}px' ,
                ]
            ]
        );

        $repeater->add_control(
            'water_mark_font_color' ,
            [
                'label' => esc_html__( 'رنگ متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::COLOR ,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .water-mark-value' => 'color : {{VALUE}}' ,
                ] ,
                'condition' => [
                    'water_mark_type' => 'text' ,
                    'show_wc_status' => 'yes'
                ]
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'water_mark_background',
                'label' => esc_html__( 'رنگ پس زمینه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}' ,
                'condition' => [
                    'water_mark_type' => 'text' ,
                    'show_wc_status' => 'yes'
                ]
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'water_mark_border',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}' ,
                'condition' => [
                    'water_mark_type' => 'text' ,
                    'show_wc_status' => 'yes'
                ]
            ]
        );

        $default = $this->set_defaults();

        $this->add_control(
            'water_mark_font_repeater' ,
            [
                'label' => esc_html__( 'وضعیت های سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::REPEATER ,
                'fields' => $repeater->get_controls(),
                'default' => $default ,
                'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => false,
                ] ,
                'title_field' => '{{{ field_label }}}' ,
            ]
        );

        $this->end_controls_section();
    }

    private function set_defaults ( ) {
        $default = [
            [
                'status_key' => 'pre_invoice' ,
                'ch_color' => '#4FA1CA' ,
                'show_wc_status' => 'yes' ,
                'field_label' => esc_html__( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'water_mark_font_color' => '#4FA1CA' ,
                'water_mark_background' => [
                    'background' => [
                        'default' => 'classic',
                    ] ,
                    'color' => [
                        'default' => 'transparent' ,
                    ] ,
                ] ,
                'water_mark_border_border' => 'solid' ,
                'water_mark_border_width' => [
                    'top' => 8 ,
                    'right' => 8 ,
                    'bottom' => 8 ,
                    'left' => 8 ,
                    'unit' => 'px' ,
                    'isLinked' => true,
                ] ,
                'water_mark_border_color' => '#4FA1CA' ,
            ]
        ];
        foreach ( wc_get_order_statuses() as $key => $status ) {
            switch ( $key ) {
                case 'wc-pending' :
                case 'wc-processing' :
                    $color_code = '#4FA1CA';
                break;
                case 'wc-on-hold' :
                    $color_code = '#ffa630';
                break;
                case 'wc-completed' :
                    $color_code = '#4ddf40';
                break;
                case 'wc-cancelled' :
                case 'wc-refunded' :
                case 'wc-failed' :
                    $color_code = '#ec2929';
                break;
                default :
                    $color_code = '#0180C0';
                break;

            }

            $default[] = [
                'status_key' => $key ,
                'ch_color' => $color_code ,
                'show_wc_status' => 'yes' ,
                'field_label' => $status ,
                'water_mark_font_color' => $color_code ,
                'water_mark_background' => [
                    'background' => [
                        'default' => 'classic',
                    ] ,
                    'color' => [
                        'default' => 'transparent' ,
                    ] ,
                ] ,
                'water_mark_border_border' => 'solid' ,
                'water_mark_border_width' => [
                    'top' => 8 ,
                    'right' => 8 ,
                    'bottom' => 8 ,
                    'left' => 8 ,
                    'unit' => 'px' ,
                    'isLinked' => true,
                ] ,
                'water_mark_border_color' => $color_code ,
            ];
        }
        return $default;
    }

    private function widget_main_styles( ) {
        $this->start_controls_section(
            'widget_main_styles' ,
            [
                'label' => esc_html__( 'استایل های ویجت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'  => Controls_Manager::TAB_STYLE ,
            ]
        );

        $this->add_control(
            'water_mark_opacity' ,
            [
                'label' => esc_html__( 'میزان شفافیت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SLIDER ,
                'range' => [
                    'px' => [
                        'min' => 0 ,
                        'max' => 1 ,
                        'step' => 0.1 ,
                    ]
                ] ,
                'default' => [
                    'size' => 0.8 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .water-mark-parent' => 'opacity : {{SIZE}}' ,
                ]
            ]
        );

        $this->add_control(
            'water_mark_padding' ,
            [
                'label' => esc_html__( 'فاصله داخلی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::DIMENSIONS ,
                'default' => [
                    'top' => 8 ,
                    'right' => 25 ,
                    'bottom' => 8 ,
                    'left' => 25 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .water-mark-parent' => 'padding : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control(
            'water_mark_radius' ,
            [
                'label' => esc_html__( 'انحنای حاشیه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::DIMENSIONS ,
                'default' => [
                    'top' => 8 ,
                    'right' => 8 ,
                    'bottom' => 8 ,
                    'left' => 8 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} .water-mark-parent' => 'border-radius : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'water_mark_typo',
                'selector' => '{{WRAPPER}} .water-mark-value' ,
            ]
        );

        $this->end_controls_section();
    }

}
