<?php

namespace Pargar\Includes\Widgets\OrderMeta;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class OrderMeta extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_name() {
        return 'magic-factor-order-meta-widget';
    }

    public function get_icon() {
        return 'pargar-factor custom';
    }

    public function get_title() {
        return esc_html__( 'متای سفارش' , PARGAR_TEXT_DOMAIN_NAME );
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
                if ( $settings['meta_order_empty_status'] !== 'yes' ) {
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

                if ( $settings['meta_order_empty_status'] !== 'yes' ) {
                    if ( !$order->meta_exists( $settings['meta_order_key'] ) ) {
                        return;
                    }
                }
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
                'label' => esc_html__( 'تنظیمات افزونه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab'   => Controls_Manager::TAB ,
            ]
        );

        $this->add_control(
            'meta_order_notice' ,
            [
                'type'  => Controls_Manager::NOTICE ,
                'heading' => esc_html__( 'نکته' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'content' => esc_html__( 'در صورتی که قصد نمایش اطلاعاتی از متای سفارش خود را دارید، میتوانید از این ویجت استفاده کنید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'notice_type' => 'success' ,
            ]
        );

        $this->add_control(
            'meta_order_pre_value' ,
            [
                'label' => esc_html__( 'پیش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
                'default' => esc_html__( 'اجرت طلا :' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'meta_order_key' ,
            [
                'label' => esc_html__( 'کلید متا' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
            ]
        );

        $this->add_control(
            'meta_order_is_price' ,
            [
                'label' => esc_html__( 'آیا مقدار یک قیمت است؟' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'در صورتی که مقدار برگشتی متا، قیمت باشد این گزینه را فعال کنید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'meta_order_show_currency_symbol' ,
            [
                'label' => esc_html__( 'نمایش واحد ارزی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'meta_order_is_price' => 'yes' ,
                ]
            ]
        );

        $this->add_control(
            'meta_order_empty_status' ,
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
            'meta_order_empty_replacement' ,
            [
                'label' => esc_html__( 'متن جایگزین' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::TEXTAREA ,
                'default' => '-' ,
                'description' => esc_html__( 'در صورت خالی بودن مقدار متا، این مقدار چاپ خواهد شد.' ,PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->end_controls_section();

    }

    private function widget_main_styles( ) {

    }

}
