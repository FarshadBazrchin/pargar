<?php

namespace Pargar\Includes\Widgets\TotalsTable;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class TotalsTable extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-totals-table-widget';
    }

    public function get_icon() {
        return 'pargar-factor totals-table';
    }

    public function get_title() {
        return esc_html__( 'جدول مجموع ها' , PARGAR_TEXT_DOMAIN_NAME );
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
            if ( isset( $_GET['print_pre_invoice'] ) ) {
                if ( WC()->cart->is_empty() ) {
                    return;
                }
            }

            if ( isset( $_GET['print_invoice'] ) ) {
                if ( empty( $_GET['order_id'] ) ) {
                    return;
                }
                $order = wc_get_order( $_GET['order_id'] );
                if ( empty( $order ) ) {
                    return;
                }
            }
        }

        include "Templates/invoice/table.php";
    }

    protected function _register_controls() {
        $this->thead_items();

        $this->t_head_style_section();
        $this->t_head_icons_style();
        $this->t_body_style_section();
    }

    private function thead_items(){
        $this->start_controls_section(
            'content_section2',
            [
                'label' => esc_html__('سطرهای جدول', PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title',
            [
                'label' => esc_html__( 'انتخاب فیلد', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'final_price' => esc_html__( 'مبلغ نهایی سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_price' => esc_html__( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'discount_price' => esc_html__( 'مبلغ تخفیف' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'shipping_price' => esc_html__( 'مبلغ حمل و نقل' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'salary' => esc_html__( 'دستمزد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
            ]
        );

        $repeater->add_control(
            'thead_custom_title',
            [
                'label' => esc_html__( 'عنوان دلخواه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $repeater->add_control(
            'thead_custom_title_value',
            [
                'label' => esc_html__( 'عنوان', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::TEXTAREA ,
                'default' => '' ,
                'description' => esc_html__( 'در صورتی که میخواهید عنوان دلخواه بجای عنوان فیلد انتخاب شده در فاکتور نمایش داده شود، میتوانید از این فیلد استفاده کنید!' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'condition' => [
                    'thead_custom_title' => 'yes'
                ]
            ],
        );

        $repeater->add_control(
            'thead_custom_icon_status' ,
            [
                'label' => esc_html__( 'اعمال آیکن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'no' ,
            ]
        );

        $repeater->add_control(
            'tab_icon',[
                'label' => esc_html__( 'انتخاب آیکن', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::ICONS ,
                'condition' => [
                    'thead_custom_icon_status' => 'yes' ,
                ]
            ]
        );

        $repeater->add_control(
            'thead_icon_color',
            [
                'label' => esc_html__( 'رنگ آیکن', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr svg path' => 'fill: {{VALUE}}'
                ] ,
                'condition' => [
                    'thead_custom_icon_status' => 'yes' ,
                ] ,
                'default' => '#000'
            ]
        );

        $repeater->add_control(
            'font_color',
            [
                'label' => esc_html__( 'رنگ متن', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR ,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr .holder' => 'color: {{VALUE}}'
                ] ,
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'table_thead_typo' ,
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr .holder' ,
            ]
        );

        $repeater->add_control(
            'background_color',
            [
                'label' => esc_html__( 'رنگ پس زمنیه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr' => 'background-color: {{VALUE}}'
                ] ,
            ]
        );

        $repeater->add_control(
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr .holder' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} table {{CURRENT_ITEM}} .thead-instead-tr',
            ]
        );

        $this->add_control(
            'list_aa',
            [
                'label' => esc_html__('عناوین', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ list_title }}}',
                'default' => [
                    [
                        'list_title' => 'order_price',
                        'text_align' => 'center',
                    ],
                    [
                        'list_title' => 'discount_price',
                        'text_align' => 'center',
                    ],
                    [
                        'list_title' => 'shipping_price',
                        'text_align' => 'center',
                    ],
                    [
                        'list_title' => 'final_price',
                        'text_align' => 'center',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

    }

    private function t_head_style_section(){

        $this->start_controls_section(
            'thead_style',
            [
                'label' => esc_html__('استایل های هدر جدول', PARGAR_TEXT_DOMAIN_NAME ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'thead_background_color',
            [
                'label' => esc_html__( 'رنگ پس زمینه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr' => 'background-color: {{VALUE}}' ,
                ],
                'default' => '#EBEBEB'
            ]
        );

        $this->add_control(
            'thead_font_color',
            [
                'label' => esc_html__( 'رنگ متن ها', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr' => 'color: {{VALUE}}' ,
                ],
                'default' => '#000'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'thead_font_typo',
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table .thead-instead-tr' ,
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
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr .holder' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'thead_padding',
            [
                'label' => esc_html__( 'فاصله داخلی', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tfoot_border_all',
                'selector' => '{{WRAPPER}} table .thead-instead-tr',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#C4C4C4',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

    }

    private function t_head_icons_style( ) {
        $this->start_controls_section(
            't_head_icons_style' ,
            [
                'label' => esc_html__( 'استایل آیکن های هدر' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB_STYLE ,
            ]
        );

        $this->add_control(
            't_head_icons_notice' ,
            [
                'type' => Controls_Manager::NOTICE ,
                'notice_type' => 'danger' ,
                'dismissible' => false ,
                'heading' => esc_html__( 'توجه!' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'content' => esc_html__( 'چنانچه در هدر جدول از آیکن استفاده نمیکنید، میتوانید از این بخش گذر کنید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'thead_direction',
            [
                'label' => esc_html__('چینش محتوا', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'unset' => [
                        'title' => esc_html__('ارث بری از صفحه', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-ban',
                    ] ,
                    'ltr' => [
                        'title' => esc_html__('چپ به راست', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-order-end',
                    ],
                    'rtl' => [
                        'title' => esc_html__('راست به چپ', PARGAR_TEXT_DOMAIN_NAME ),
                        'icon' => 'eicon-order-start',
                    ] ,
                ],
                'default' => 'unset',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr' => 'direction: {{VALUE}};',
                    '{{WRAPPER}} table .tbody-instead-tr' => 'direction: {{VALUE}};',
                ] ,
            ]
        );

        $this->add_control(
            'icon_width',
            [
                'label' => esc_html__( 'ابعاد آیکن', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 0 ,
                        'max' => 100
                    ]
                ],
                'default' => [
                    'unit' => 'px' ,
                    'size' => 18 ,
                ],
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} table .thead-instead-tr i' => 'font-size: {{SIZE}}{{UNIT}};',
                ] ,
            ]
        );

        $this->add_control(
            'icon_gap_with_title' ,
            [
                'label' => esc_html__( 'فاصله از متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SLIDER ,
                'default' => [
                    'size' => 8 ,
                    'unit' => 'px' ,
                ] ,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr svg' => 'margin-inline-end : {{SIZE}}{{UNIT}};' ,
                    '{{WRAPPER}} table .thead-instead-tr i' => 'margin-inline-end : {{SIZE}}{{UNIT}};' ,
                ] ,
            ]
        );

        $this->add_control(
            'thead_icon_color',
            [
                'label' => esc_html__( 'رنگ آیکن ها', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR ,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table .thead-instead-tr i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} table .thead-instead-tr svg path' => 'fill: {{VALUE}}'
                ],
                'default' => '#000'
            ]
        );

        $this->end_controls_section();
    }

    private function t_body_style_section(){

        $this->start_controls_section(
            'tbody_style',
            [
                'label' => esc_html__('استایل های بدنه جدول', PARGAR_TEXT_DOMAIN_NAME ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tbody_font_color',
            [
                'label' => esc_html__( 'رنگ متن ها', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table .tbody-instead-tr' => 'color: {{VALUE}}' ,
                    '{{WRAPPER}} table .tbody-instead-tr .amount' => 'color: {{VALUE}} !important' ,
                ],
                'default' => '#000'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array (
                'name'     => 'tbody_font_typo' ,
                'label'    =>  esc_html__( 'تاپو گرافی عنوان' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table .tbody-instead-tr' ,
            )
        );

        $this->add_control(
            'tbody_background_color_even',
            [
                'label' => esc_html__( 'رنگ بدنه جدول (ردیف زوج)', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table tr:nth-child(even) .tbody-instead-tr' => 'background-color: {{VALUE}}'
                ] ,
                'description' => esc_html__( 'این رنگ در ردیف های زوج بدنه جدول اعمال خواهد شد' , PARGAR_TEXT_DOMAIN_NAME ),
            ]
        );

        $this->add_control(
            'tbody_background_color_odd',
            [
                'label' => esc_html__( 'رنگ بدنه جدول (ردیف فرد)', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR ,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table tr:nth-child(odd) .tbody-instead-tr' => 'background-color: {{VALUE}}'
                ],
                'description' => esc_html__( 'این رنگ در ردیف های فرد بدنه جدول اعمال خواهد شد' , PARGAR_TEXT_DOMAIN_NAME ),
            ]
        );

        $this->add_control(
            'tbody_text_align',
            [
                'label' => esc_html__('چینش متن بدنه', PARGAR_TEXT_DOMAIN_NAME ),
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
                'default' => 'center',
                'toggle' => true ,
                'frontend_available' => true ,
                'selectors' => [
                    '{{WRAPPER}} table .tbody-instead-tr' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tbody_padding',
            [
                'label' => esc_html__( 'فاصله داخلی', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} table .tbody-instead-tr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'default' => [
                    'top' => 9,
                    'right' => 9,
                    'bottom' => 9,
                    'left' => 9,
                    'unit' => 'px'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tbody_border_all',
                'selector' => '{{WRAPPER}} tbody .tbody-instead-tr',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#C4C4C4',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

    }

}
