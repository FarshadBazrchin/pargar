<?php

namespace Pargar\Includes\Widgets\ProductsTable;

use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class ProductsTable extends Widget_Base {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    public function get_name() {
        return 'magic-factor-products-table-widget';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_title() {
        return esc_html__( 'جدول محصولات' , PARGAR_TEXT_DOMAIN_NAME );
    }

    public function get_categories() {
        return [ PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ];
    }

    public function get_script_depends() {
        return [
            'MAGIC_FACTOR_BARCODE_JS_SCRIPT' ,
            'MAGIC_FACTOR_QRCODE_JS_SCRIPT' ,
        ];
    }

    protected function _register_controls() {
        $this->thead_items();
        $this->tfoot_items();

        $this->t_head_style_section();
        $this->t_head_icons_style();
        $this->t_body_style_section();
        $this->t_foot_style_section();
    }

    private function get_fake_products( ) {
        $args = array(
            'orderby'  => 'id',
            'limit' => 10,
            'status' => 'publish'
        );
        return [
            'products' => wc_get_products( $args )
        ];
    }

    private function get_order_data( ) {
        $order = wc_get_order( $_GET['order_id'] );
        foreach ( $order->get_items() as $item ) {
            if ( empty( $item->get_variation_id() ) ) {
                $products['products'][] = wc_get_product( $item->get_product_id() );
                $products['order_data'][$item->get_product_id()] = $item;
            }else {
                $products['products'][] = wc_get_product( $item->get_variation_id() );
                $products['order_data'][$item->get_variation_id()] = $item;
            }
        }
        return [
            'products' => $products['products'] ,
            'order_data' => $products['order_data'] ,
            'order' => $order ,
        ];
    }

    private function get_user_cart_data( ) {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        $data = [];
        foreach( $items as $values ) {
            $data[] = $values;
        }
        return $data;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
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

        $products = [];
        if ( $is_editor_mode OR is_preview() ) {
            $products = $this->get_fake_products();
            include PARGAR_PATH_TEMPLATE . '/widgets/ProductsTable/invoice/table.php';
            return;
        }

        if ( isset( $_GET['print_pre_invoice'] ) ) {
            $data = $this->get_user_cart_data();
            include PARGAR_PATH_TEMPLATE . '/widgets/ProductsTable/pre-invoice/table.php';
            return;
        }

        if ( isset( $_GET['print_invoice'] ) ) {
            $products = $this->get_order_data();
            if ( empty( $products['order'] ) OR empty( $products['order_data'] ) ) {
                return;
            }
            $order = $products['order'];
            include PARGAR_PATH_TEMPLATE . '/widgets/ProductsTable/invoice/table.php';
        }

    }

    private function thead_items(){
        $this->start_controls_section(
            'content_section2',
            [
                'label' => esc_html__('سطرهای جدول', PARGAR_TEXT_DOMAIN_NAME ) ,
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'table_preview_notice' ,
            [
                'heading' => esc_html__( 'توجه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::NOTICE ,
                'notice_type' => 'info' ,
                'content' => esc_html__( 'برای مشاهده تغییرات خود حتما به صورت پیشنمایش صفحه را باز کنید.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title',
            [
                'label' => esc_html__( 'انتخاب فیلد', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row_number' => esc_html__( 'ردیف', PARGAR_TEXT_DOMAIN_NAME ),
                    'product_name' => esc_html__( 'نام محصول', PARGAR_TEXT_DOMAIN_NAME ),
                    'product_id' => esc_html__( 'آیدی محصول', PARGAR_TEXT_DOMAIN_NAME ),
                    'product_sku' => esc_html__( 'کد محصول(sku)', PARGAR_TEXT_DOMAIN_NAME ),
                    'product_gtin' => esc_html__( 'کد محصول(gtin)', PARGAR_TEXT_DOMAIN_NAME ),
                    'product_image' => esc_html__( 'تصویر محصول', PARGAR_TEXT_DOMAIN_NAME ) ,
                    'item_price' => esc_html__( 'قیمت' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'item_price_no_symbol' => esc_html__( 'قیمت(بدون نماد ارزی)' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'discount' => esc_html__( 'مبلغ تخفیف' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'discount_no_symbol' => esc_html__( 'مبلغ تخفیف(بدون نماد ارزی)' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'product_count' => esc_html__( 'تعداد', PARGAR_TEXT_DOMAIN_NAME ),
                    'item_final_price' => esc_html__( 'مبلغ کل' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'item_final_price_no_symbol' => esc_html__( 'مبلغ کل(بدون نماد ارزی)' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
            ]
        );

        $repeater->add_control(
            'product_id_sku_gtin_print_type' ,
            [
                'label' => esc_html__( 'نوع چاپ' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'code_only' => esc_html__( 'پیشفرض' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'barcode' => esc_html__( 'به صورت بارکد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'Qrcode' => esc_html__( 'به صورت QR کد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
                'default' => 'code_only' ,
                'condition' => [
                    'list_title' => [ 'product_id' , 'product_sku' , 'product_gtin' ]
                ]
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
                'label' => esc_html__( 'عنوان دلخواه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::TEXT,
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
                    '{{WRAPPER}} table thead {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} table thead {{CURRENT_ITEM}} svg path' => 'fill: {{VALUE}}'
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
                    '{{WRAPPER}} thead {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
                ] ,
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'table_thead_typo' ,
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} thead {{CURRENT_ITEM}}' ,
            ]
        );

        $repeater->add_control(
            'background_color',
            [
                'label' => esc_html__( 'رنگ پس زمنیه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} thead {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}'
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
                'toggle' => true ,
                'selectors' => [
                    '{{WRAPPER}} table {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .holder' => 'justify-content: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
            ]
        );

        $repeater->add_control(
            'tbody_inherit_text_align_from_thead' ,
            [
                'label' => esc_html__( 'ارث بری چینش متن' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type' => \Elementor\Controls_Manager::SWITCHER ,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ) ,
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ) ,
                'return_value' => 'yes' ,
                'default' => 'no' ,
                'description' => esc_html__( 'با فعال سازی این گزینه، دیف مورد نظر چینش متن را از هدر به ارث خواهد برد.' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
            ]
        );

        $repeater->add_control(
            'space_filling',
            [
                'label' => esc_html__( 'میزان پر کردن فضا', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('پیش فرض',PARGAR_TEXT_DOMAIN_NAME ),
                    'colspan' => esc_html__('بر اساس تعداد ستون',PARGAR_TEXT_DOMAIN_NAME ),
                    'custom' => esc_html__('دلخواه',PARGAR_TEXT_DOMAIN_NAME ),
                ],
                'default' => 'auto'
            ]
        );

        $repeater->add_control(
            'colspan',
            [
                'label' => esc_html__( 'تعداد ستون', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::NUMBER ,
                'default' => 2,
                'condition' => [
                    'space_filling' => 'colspan'
                ],
                'description' => esc_html__('بر اساس تعداد ستون های جدول میتواند مقدار بگیرد.',PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $repeater->add_control(
            'custom_space',
            [
                'label' => esc_html__( 'میزان پر کردن فضا', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px'=>[
                        'min' => 0 ,
                        'max' => 1000
                    ]
                ],
                'condition' => [
                    'space_filling' => 'custom',
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} table thead {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                        'list_title' => 'row_number',
                        'space_filling' => 'custom',
                        'custom_space' =>[
                            'unit' => 'px',
                            'size' => 50
                        ]
                    ],
                    [
                        'list_title' => 'product_name',
                        'space_filling' => 'colspan',
                        'colspan' => 2
                    ],
                    [
                        'list_title' => 'product_count',
                        'space_filling' => 'custom',
                        'custom_space' =>[
                            'unit' => 'px',
                            'size' => 50
                        ]
                    ],
                    [
                        'list_title' => 'item_price',
                        'space_filling' => 'auto',
                    ],
                    [
                        'list_title' => 'discount',
                        'space_filling' => 'auto',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

    }

    private function tfoot_items(){

        $this->start_controls_section(
            'tfoot_content_section',
            [
                'label' => esc_html__('فوتر جدول', PARGAR_TEXT_DOMAIN_NAME ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tfoot_list_title',
            [
                'label' => esc_html__( 'مقدار/عنوان', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'order_items_count' => esc_html__( 'مجموع تعداد سفارشات' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_items_price' => esc_html__( 'قیمت کل محصولات' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_items_price_no_symbol' => esc_html__( 'قیمت کل محصولات(بدون شناسه ارزی)' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_items_discount' => esc_html__( 'مجموع تخفیفات' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_items_discount_no_symbol' => esc_html__( 'مجموع تخفیفات(بدون شناسه ارزی)' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'custom' => esc_html__( 'دلخواه', PARGAR_TEXT_DOMAIN_NAME ),
                ],
                'default' => 'custom' ,
            ]
        );

        $repeater->add_control(
            'tfoot_custom_title_value',
            [
                'label' => esc_html__( 'عنوان دلخواه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'description' => esc_html__( 'در صورتی که میخواهید عنوان دلخواه بجای عنوان فیلد انتخاب شده در فاکتور نمایش داده شود، میتوانید از این فیلد استفاده کنید!' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'condition' => [
                    'tfoot_list_title' => 'custom'
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
                        \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
                    ]
                ]
            ],
        );

        $repeater->add_control(
            'tfoot_font_color',
            [
                'label' => esc_html__( 'رنگ متن', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
                ],
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'table_tfoot_typo' ,
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table .tfoot {{CURRENT_ITEM}}' ,
            ]
        );

        $repeater->add_control(
            'tfoot_background_color',
            [
                'label' => esc_html__( 'رنگ پس زمنیه', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}'
                ],
                'default' => ''
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
                    '{{WRAPPER}} table {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} table {{CURRENT_ITEM}} .holder' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tfoot_border',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
            ]
        );

        $repeater->add_control(
            'tfoot_space_filling',
            [
                'label' => esc_html__( 'میزان پر کردن فضا (افقی)', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'auto'=> esc_html__('پیش فرض',PARGAR_TEXT_DOMAIN_NAME ),
                    'colspan' => esc_html__('بر اساس تعداد ستون',PARGAR_TEXT_DOMAIN_NAME ),
                ],
                'default' => 'auto'
            ]
        );

        $repeater->add_control(
            'colspan',
            [
                'label' => esc_html__( 'تعداد ستون', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::NUMBER ,
                'default' => 2,
                'condition' => [
                    'tfoot_space_filling' => 'colspan'
                ],
                'description' => esc_html__('بر اساس تعداد ستون های جدول میتواند مقدار بگیرد.',PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'include_tfoot',
            [
                'label' => esc_html__( 'اعمال فوتر', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
                'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tfoot_list',
            [
                'label' => esc_html__('عناوین', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ tfoot_list_title }}}',
                'condition' => [
                    'include_tfoot' => 'yes',
                ],
                'default' => [
                    [
                        'tfoot_list_title' => 'custom',
                        'tfoot_custom_title_value' => esc_html__('مجموع کل' , PARGAR_TEXT_DOMAIN_NAME ),
                        'tfoot_space_filling' => 'colspan',
                        'colspan' => 3 ,
                    ] ,
                    [
                        'tfoot_list_title' => 'order_items_count',
                        'tfoot_space_filling' => 'colspan',
                        'colspan' => 1 ,
                    ] ,
                    [
                        'tfoot_list_title' => 'order_items_price',
                        'tfoot_space_filling' => 'colspan',
                        'colspan' => 1 ,
                    ] ,
                    [
                        'tfoot_list_title' => 'order_items_discount',
                        'tfoot_space_filling' => 'colspan',
                        'colspan' => 1 ,
                    ] ,
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

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'thead_background_color',
                'selector' => '{{WRAPPER}} table thead tr' ,
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ] ,
                    'color' => [
                        'default' => '#EBEBEB' ,
                    ] ,
                ],
            ]
        );

        $this->add_control(
            'thead_font_color',
            [
                'label' => esc_html__( 'رنگ متن ها', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table thead tr' => 'color: {{VALUE}}'
                ],
                'default' => '#000'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'thead_font_typo',
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table thead tr' ,
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
                'default' => 'center' ,
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} table thead td' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} table thead .holder' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} table thead td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                'selector' => '{{WRAPPER}} thead tr td',
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
                    '{{WRAPPER}} .holder' => 'direction: {{VALUE}};',
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
                    '{{WRAPPER}} table thead tr svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} table thead tr i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} table thead tr svg' => 'margin-inline-end : {{SIZE}}{{UNIT}};' ,
                    '{{WRAPPER}} table thead tr i' => 'margin-inline-end : {{SIZE}}{{UNIT}};' ,
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
                    '{{WRAPPER}} table thead tr i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} table thead tr svg path' => 'fill: {{VALUE}}'
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
					'{{WRAPPER}} table tbody tr' => 'color: {{VALUE}}' ,
					'{{WRAPPER}} table tbody tr .amount' => 'color: {{VALUE}} !important'
				],
				'default' => '#000' ,
                'frontend_available' => true ,
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array (
                'name'     => 'tbody_font_typo' ,
                'label'    =>  esc_html__( 'تاپو گرافی عنوان' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table tbody tr' ,
            )
        );

        $this->add_control(
            'tbody_background_color_even',
            [
                'label' => esc_html__( 'رنگ بدنه جدول (ردیف زوج)', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table tbody tr:nth-child(even)' => 'background-color: {{VALUE}}'
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
                    '{{WRAPPER}} table tbody tr:nth-child(odd)' => 'background-color: {{VALUE}}'
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
                    '{{WRAPPER}} table tbody tr' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} table tbody tr td' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                'selector' => '{{WRAPPER}} tbody tr td',
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

    private function t_foot_style_section(){

        $this->start_controls_section(
            'tfoot_style',
            [
                'label' => esc_html__('استایل های فوتر جدول', PARGAR_TEXT_DOMAIN_NAME ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tfoot_font_color',
            [
                'label' => esc_html__( 'رنگ متون فوتر', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'show_label' => true,
                'selectors' => [
                    '{{WRAPPER}} table tfoot tr' => 'color: {{VALUE}}'
                ],
                'default' => '#000'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type() ,
            [
                'name' => 'tfoot_font_typo',
                'label' => esc_html__( 'تایپو گرافی' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'selector' => '{{WRAPPER}} table tfoot tr' ,
            ]
        );

        $this->add_control(
            'tfoot_text_align',
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
                'default' => 'center' ,
                'toggle' => true ,
                'selectors' => [
                    '{{WRAPPER}} table tfoot td' => 'text-align: {{VALUE}};',
                ] ,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tfoot_background_color',
                'selector' => '{{WRAPPER}} table tfoot tr' ,
            ]
        );

        $this->add_control(
            'tfoot_padding',
            [
                'label' => esc_html__( 'فاصله داخلی', PARGAR_TEXT_DOMAIN_NAME ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} table tfoot td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tfoot_border_all2',
                'selector' => '{{WRAPPER}} tfoot tr td',
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
