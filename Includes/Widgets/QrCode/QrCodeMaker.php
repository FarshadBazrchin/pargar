<?php

namespace Pargar\Includes\Widgets\QrCode;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class QrCodeMaker extends Widget_Base {

	private static $_instance = null;
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_name() {
		return 'magic-factor-QrCode-maker';
	}

	public function get_icon() {
		return 'pargar-factor qrcode';
	}

	public function get_title() {
		return esc_html__( 'QR code ساز' , PARGAR_TEXT_DOMAIN_NAME );
	}

    public function get_categories() {
        return [ PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ];
    }

    public function get_script_depends() {
        return [ 'MAGIC_FACTOR_QRCODE_JS_SCRIPT' ];
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

                if ( $settings['QR_value'] !== 'custom' ) {
                    return;
                }else {
                    if ( empty( $settings['QR_tag_value'] ) ) {
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

        $el_id = 'MGF_QRCODE_' . $this->get_id();
        include PARGAR_PATH_TEMPLATE . '/widgets/QrCodeMaker/index.php';
    }

    protected function _register_controls() {

		$this->start_controls_section(
			'QR_settings',
			[
				'label' => esc_html__('تنظیمات QR کد', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'QR_value' ,
            [
                'label' => esc_html__( 'مقدار' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type'  => Controls_Manager::SELECT ,
                'options' => [
                    'order_id' => esc_html__( 'آیدی سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'order_code' => esc_html__( 'کد سفارش' , PARGAR_TEXT_DOMAIN_NAME ) ,
                    'custom' => esc_html__( 'دلخواه' , PARGAR_TEXT_DOMAIN_NAME ) ,
                ] ,
                'default' => 'order_id' ,
            ]
        );

		$this->add_control(
			'QR_tag_value',
			[
				'label' => esc_html__('مقدار دلخواه', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Code Art' , PARGAR_TEXT_DOMAIN_NAME ) ,
				'dynamic' => [
					'active' => true,
					'categories' => [
						\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
						\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
					]
				] ,
                'condition' =>[
                    'QR_value' => 'custom' ,
                ]
			]
		);

        $this->add_control(
            'QR_level',
            [
                'label' => esc_html__('تراکم اطلاعات', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 2 ,
                        'max' => 10
                    ]
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'px'
                ],
            ]
        );

		$this->add_control(
			'QR_has_icon',
			[
				'label' => esc_html__('نمایش آیکن در بارکد', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'بله', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'label_off' => esc_html__( 'خیر', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'QR_icon_img',
			[
				'label' => esc_html__('انتخاب نمادک', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'QR_has_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'QR_image_size',
			[
				'label' => esc_html__('سایز تصویر', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%'=>[
						'min' => 0 ,
						'max' => 100
					]
				],
				'default' => [
					'size' => 20,
					'unit' => '%'
				],
				'condition' => [
					'QR_has_icon' => 'yes'
				],
			]
		);

		$this->add_control(
			'QR_image_margin',
			[
				'label' => esc_html__('فاصله تصویر از اطراف', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px'=>[
						'min' => 0 ,
						'max' => 20
					]
				],
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'condition' => [
					'QR_has_icon' => 'yes'
				],
			]
		);

		$this->add_control(
			'QR_corner_type',
			[
				'label' => esc_html__('نوع گوشه ها', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					"" => esc_html__('ساده', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'square' => esc_html__('مربع', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'dot' => esc_html__('دایره', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'extra-rounded' => esc_html__('خمیده', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				],
				'default' => 'square',
			]
		);

		$this->add_control(
			'QR_corner_dot_type',
			[
				'label' => esc_html__('نوع مربع درون گوشه ها', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					"" => esc_html__('ساده', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'square' => esc_html__('مربع', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'dot' => esc_html__('دایره', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				],
				'default' => 'square',
			]
		);

		$this->add_control(
			'QR_middle_dot_type',
			[
				'label' => esc_html__('نوع کدهای درون کادر', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'square' => esc_html__('مربع', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'dots' => esc_html__('نقطه چین', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'rounded' => esc_html__('گوشه گرد', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'extra-rounded' => esc_html__('گوشه بیشتر گرد', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'classy' => esc_html__('درجه ای', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
					'classy-rounded' => esc_html__('درجه ای گرد شده', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
				],
				'default' => 'square',
			]
		);

		$this->end_controls_section();

		$this->QR_style_tab();

	}

	private function QR_style_tab() {

		$this->start_controls_section(
			'QR_style_tab',
			[
				'label' => esc_html__('تنظیمات ظاهری', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
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
                    '{{WRAPPER}} .qrcode-maker-wrapper' => 'justify-content: {{VALUE}};',
                ] ,
                'refresh' => true ,
                'render_type' => 'template' ,
                'default' => 'center' ,
            ]
        );

        $this->add_control(
            'QR_tag_size' ,
            [
                'label' => esc_html__( 'ابعاد بارکد' , PARGAR_TEXT_DOMAIN_NAME ) ,
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 0 ,
                        'max' => 400
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 125,
                ],
            ]
        );

        $this->start_controls_tabs(
            'QR_code_color_picker_tabs'
        );

        $this->start_controls_tab(
            'QR_code_body_color_tab' ,
            [
                'label' => esc_html__( 'بدنه' , PARGAR_TEXT_DOMAIN_NAME )
            ]
        );

        $this->add_control(
            'QR_body_color_type',
            [
                'label' => esc_html__('نوع رنگ بدنه تگ',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'single' => esc_html__( 'تک رنگ', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'linear' => esc_html__( 'دو رنگ خطی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'radial' => esc_html__( 'دو رنگ چرخشی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                ],
                'default' => 'single'
            ]
        );

        $this->add_control(
            'QR_body_color1',
            [
                'label' => esc_html__('رنگ بدنه',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000'
            ]
        );

        $this->add_control(
            'QR_body_color2',
            [
                'label' => esc_html__('رنگ دوم بدنه',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'QR_body_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->add_control(
            'QR_body_color_rotation',
            [
                'label' => esc_html__('میزان چرخش رنگ',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 0,
                        'max' => 180
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'QR_body_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'QR_code_corners_color_tab' ,
            [
                'label' => esc_html__( 'گوشه ها' , PARGAR_TEXT_DOMAIN_NAME ) ,
            ]
        );

        $this->add_control(
            'QR_corner_color_type',
            [
                'label' => esc_html__('نوع رنگ گوشه ها',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'single' => esc_html__( 'تک رنگ', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'linear' => esc_html__( 'دو رنگ خطی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'radial' => esc_html__( 'دو رنگ چرخشی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                ],
                'default' => 'single'
            ]
        );

        $this->add_control(
            'QR_corner_color1',
            [
                'label' => esc_html__('رنگ اول',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000'
            ]
        );

        $this->add_control(
            'QR_corner_color2',
            [
                'label' => esc_html__('رنگ دوم',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'QR_corner_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->add_control(
            'QR_corner_color_rotation',
            [
                'label' => esc_html__('چرخش رنگ',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 0,
                        'max' => 180
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'QR_corner_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'QR_code_inner_corners_color_tab' ,
            [
                'label' => esc_html__( 'اشکال گوشه' , PARGAR_TEXT_DOMAIN_NAME ),
            ]
        );

        $this->add_control(
            'QR_dot_color_type',
            [
                'label' => esc_html__('نوع رنگ',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'single' => esc_html__( 'تک رنگ', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'linear' => esc_html__( 'دو رنگ خطی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                    'radial' => esc_html__( 'دو رنگ چرخشی', PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                ],
                'default' => 'single'
            ]
        );

        $this->add_control(
            'QR_dot_color1',
            [
                'label' => esc_html__('رنگ اول',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000'
            ]
        );

        $this->add_control(
            'QR_dot_color2',
            [
                'label' => esc_html__('رنگ دوم',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'QR_dot_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->add_control(
            'QR_dot_color_rotation',
            [
                'label' => esc_html__('چرخش رنگ',PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px'=>[
                        'min' => 0,
                        'max' => 180
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'QR_dot_color_type' => ['linear' , 'radial'],
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->end_controls_section();

	}

}