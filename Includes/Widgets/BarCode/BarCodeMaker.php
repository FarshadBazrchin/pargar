<?php

namespace Pargar\Includes\Widgets\BarCode;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class BarCodeMaker extends Widget_Base {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_name() {
		return 'magic-factor-BarCode-maker';
	}

	public function get_icon() {
		return 'pargar-factor barcode';
	}

    public function get_script_depends() {
        return [ 'MAGIC_FACTOR_BARCODE_JS_SCRIPT' ];
    }

    public function get_title() {
		return esc_html__( 'بارکد ساز' , PARGAR_TEXT_DOMAIN_NAME );
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

                if ( $settings['BarCode_value'] !== 'custom' ) {
                    return;
                }else {
                    if ( empty( $settings['BarCode_custom_value'] ) ) {
                        return;
                    }
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

        $el_id = 'MGF_BARCode_' . $this->get_id();
		include PARGAR_PATH_TEMPLATE . '/widgets/BarCodeMaker/index.php';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'BarCode-setting-tab',
			[
				'label' => esc_html__('تنظیمات بارکد ساز',PARGAR_TEXT_DOMAIN_NAME ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'BarCode_type',
			[
				'label' => esc_html__( 'نوع بارکد' , PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'CODE128' => 'CODE 128',
					'EAN13' => esc_html__( 'EAN 13 (فقط عدد،محدودیت 13 کاراکتر)' , PARGAR_TEXT_DOMAIN_NAME ),
					'EAN8' => esc_html__( 'EAN 8 (فقط عدد،محدودیت 8 کاراکتر)' , PARGAR_TEXT_DOMAIN_NAME ),
					'UPC' => esc_html__( 'UPC (فقط عدد، نیازمند 12 کاراکتر باشد)' , PARGAR_TEXT_DOMAIN_NAME ),
					'CODE39' => 'CODE 39',
				],
				'default' => 'CODE128',
				'description' => esc_html__( 'اطلاعات دریافتی بارکد با توجه به نوع آن تغییر میکند! تعداد کاراکتر، نوع کاراکتر و ... با توجه به انتخاب نوع آن تغییر میکند.' , PARGAR_TEXT_DOMAIN_NAME )
			]
		);

        $this->add_control(
            'BarCode_value' ,
            [
                'label' => esc_html__( 'مقدار بارکد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'order_id' => esc_html__( 'آیدی سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_code' => esc_html__( 'کد سفارش سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'custom' => esc_html__( 'دلخواه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
                'default' => 'order_id' ,
            ]
        );

		$this->add_control(
			'BarCode_custom_value',
			[
				'label' => esc_html__( 'مقدار بارکد' , PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::TEXTAREA ,
				'dynamic' => [
					'active' => true,
					'categories' => [
						\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
						\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
					]
				],
                'condition' => [
                    'BarCode_value' => 'custom' ,
                ] ,
                'default' => 'UCL59-557' ,
				'description' => esc_html__( 'در صورت نمایش داده نشدن بارکد به معنای پشتیبانی نشدن از مقدار وارد شده است.' , PARGAR_TEXT_DOMAIN_NAME )
			]
		);

		$this->add_control(
			'BarCode_sub_text',
			[
				'label' => esc_html__( 'نمایش مقدار بارکد' , PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'بله', PARGAR_TEXT_DOMAIN_NAME ),
				'label_off' => esc_html__( 'خیر', PARGAR_TEXT_DOMAIN_NAME ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'BarCode_sub_text_position',
			[
				'label' => esc_html__( 'جایگاه' , PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'بالا' , PARGAR_TEXT_DOMAIN_NAME ),
					'bottom' => esc_html__( 'پایین' , PARGAR_TEXT_DOMAIN_NAME ),
				],
                'default' => 'top',
				'condition' => [
					'BarCode_sub_text' => 'yes'
				],
			]
		);

		$this->end_controls_section();

		$this->BarCode_style();

	}

	private function BarCode_style(){
		$this->start_controls_section(
			'BarCode_style_tab',
			[
				'label' => esc_html__('استایل بارکد ساز',PARGAR_TEXT_DOMAIN_NAME ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'barcode_position',
            [
                'label' => esc_html__('موقعیت بارکد', PARGAR_TEXT_DOMAIN_NAME ),
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
                    '{{WRAPPER}} .barcode-maker-wrapper' => 'justify-content: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
                'default' => 'center' ,
            ]
        );

		$this->add_control(
			'BarCode_sub_text_size',
			[
				'label' => esc_html__('سایز فونت', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px'=>[
						'min' => 1 ,
						'max' => 40
					]
				],
				'condition' => [
					'BarCode_sub_text' => 'yes'
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
			],
		);

		$this->add_control(
			'BarCode_sub_text_align',
			[
				'label' => esc_html__('موقعیت متن', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'right' => [
						'title' => esc_html__('راست', PARGAR_TEXT_DOMAIN_NAME ),
						'icon' => 'eicon-text-align-right',
					],
					'center' => [
						'title' => esc_html__('وسط', PARGAR_TEXT_DOMAIN_NAME ),
						'icon' => 'eicon-text-align-center',
					],
					'left' => [
						'title' => esc_html__('چپ', PARGAR_TEXT_DOMAIN_NAME ),
						'icon' => 'eicon-text-align-left',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'condition' => [
					'BarCode_sub_text' => 'yes'
				]
			]
		);

        $this->add_control(
            'BarCode_sub_text_margin' ,
            [
                'label' => esc_html__( 'فاصله متن از بارکد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SLIDER ,
                'default' => [
                    'size' => 3 ,
                    'unit' => 'px' ,
                ] ,
                'frontend_available' => true ,
                'condition' => [
                    'BarCode_sub_text' => 'yes'
                ]
            ]
        );

		$this->add_control(
			'BarCode_bar_width',
			[
				'label' => esc_html__('زخامت ستون ها', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px'=>[
						'min' => 1 ,
						'max' => 10
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
			]
		);

		$this->add_control(
			'BarCode_bar_height',
			[
				'label' => esc_html__('ارتفاع ستون ها', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px'=>[
						'min' => 1 ,
						'max' => 150
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
			]
		);

		$this->add_control(
			'BarCode_background',
			[
				'label' => esc_html__('رنگ پس زمینه', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'BarCode_bar_color',
			[
				'label' => esc_html__('رنگ ستون ها', PARGAR_TEXT_DOMAIN_NAME ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
			]
		);

	}

}
