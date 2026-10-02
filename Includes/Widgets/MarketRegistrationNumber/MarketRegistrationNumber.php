<?php

namespace Pargar\Includes\Widgets\MarketRegistrationNumber;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class MarketRegistrationNumber extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-market-registration-number-widget';
    }

    public function get_icon() {
        return 'pargar-factor store';
    }

    public function get_title() {
        return esc_html__( 'شماره ثبت فروشگاه' , PARGAR_TEXT_DOMAIN_NAME );
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
            }

            if ( isset( $_GET['print_invoice'] ) ) {
                if ( empty( $_GET['order_id'] ) ) {
                    return;
                }
            }
        }

        $reg_number = $this->getRegistrationNumber();
        if ( $settings['market_register_empty_status'] !== 'yes' ) {
            if (empty($reg_number)) {
                return;
            }
        }

        include "Templates/index.php";
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
            'pre_value_text' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
                'default' => esc_html__( 'شماره ثبت :' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'market_register_empty_status' ,
            [
                'label' => esc_html__( 'نمایش در صورت خالی بودن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    private function widget_main_styles( ) {
        $this->start_controls_section(
            'widget_main_styles' ,
            [
                'label' => esc_html__( 'تنظیمات ویجت' , PARGAR_TEXT_DOMAIN_NAME ) ,
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
                    '{{WRAPPER}} .market-registration-number-value' => 'margin-inline-start : {{SIZE}}{{UNIT}}' ,
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
                    '{{WRAPPER}} .market-registration-number-widget' => 'text-align: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
            ]
        );

        $this->start_controls_tabs(
            'market_registration_number_value_pre_value_style_tabs'
        );

        $this->start_controls_tab(
            'market_registration_number_pre_value_style_tab' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'market_registration_number_pre_value_color' ,
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
                'name' => 'market_registration_number_pre_value_typo',
                'selector' => '{{WRAPPER}} .pre-value-txt' ,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'market_registration_number_value_style_tab' ,
            [
                'label' => esc_html__( 'شماره' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'market_registration_number_value_color' ,
            [
                'label' => esc_html__( 'رنگ' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::COLOR ,
                'default' => '#000' ,
                'selectors' => [
                    '{{WRAPPER}} .market-registration-number-value' => 'color : {{VALUE}}' ,
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'market_registration_number_value_typo',
                'selector' => '{{WRAPPER}} .market-registration-number-value' ,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    private function getRegistrationNumber( ) {
        $dokan_show_vendor_info = pargar_get_setting("dokan_show_vendor_info",true , "off");

        if ( function_exists( 'dokan_get_store_info' ) &&  $dokan_show_vendor_info == "on"  ) {
            $vendor_id = 0;
            if (isset($_GET['print_invoice'])) {
                $order = wc_get_order($_GET['order_id']);
                $vendor_id = get_post_meta($order->get_id(), '_dokan_vendor_id', true);
            } elseif (isset($_GET['print_pre_invoice']) and !WC()->cart->is_empty()) {
                $cart = WC()->cart->get_cart();
                $first_item = reset($cart);
                $product_id = $first_item['product_id'];
                $vendor_id = get_post_field('post_author', $product_id);
            }

            if ($vendor_id) {
                $reg_number = '';
                $possible_keys = [
                    'dokan_company_reg_number',
                    'company_registration_number',
                    'registration_number',
                    'shop_reg_number',
                    'cr_number'
                ];
                foreach ($possible_keys as $key) {
                    if (!empty($store_info[$key])) {
                        $reg_number = sanitize_text_field($store_info[$key]);
                        break;
                    }
                }

                if (empty($reg_number)) {
                    $reg_number = pargar_get_setting('market_registration_code', true, '-');
                }

            } else {
                $reg_number = pargar_get_setting('market_registration_code', true, '-');
            }

        } else {
            $reg_number = pargar_get_setting('market_registration_code', true, '-');
        }

        return $reg_number;
    }

}
