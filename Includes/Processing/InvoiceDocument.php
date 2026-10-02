<?php

namespace Pargar\Includes\Processing;

/**
 * The InvoiceDocument class extends Elementor's PageBase and is tailored for
 * managing invoice settings within Elementor's interface. It adds custom properties
 * and controls specific to invoices.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class InvoiceDocument extends \Elementor\Core\DocumentTypes\PageBase
{

    /**
     * Retrieve the document properties.
     *
     * This method overrides the parent method to add custom properties related to
     * invoice settings. It sets the document title and specifies the custom post type (CPT)
     * associated with invoices.
     *
     * @return array The properties of the document, including title and CPT.
     */
    public static function get_properties()
    {
        $properties = parent::get_properties();

        $properties['title'] = "تنظیمات فاکتور";
        $properties['cpt'] = [InvoicePostType::getPostType()];

        return $properties;
    }

    /**
     * Register custom controls for the invoice settings.
     *
     * This method defines the UI elements and input options used to customize invoices.
     * It includes sections for page size, padding, invoice type, and scaling options,
     * all integrated seamlessly into Elementor's interface.
     */

    protected function register_controls()
    {

        parent::register_controls();

        $this->start_controls_section(
            'factor_settings_section',
            [
                'label' => __('تنظیمات فاکتور', 'your-text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'size_page_factor',
            [
                'label' => __('سایز صفحه', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2600,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-factor' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding_page_factor',
            [
                'label' => esc_html__('فاصله داخلی', PARGAR_TEXT_DOMAIN_NAME),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 16,
                    'right' => 32,
                    'bottom' => 16,
                    'left' => 32,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-factor' => 'padding : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_control(
            'type_page_factor',
            [
                'label' => esc_html__('نوع فاکتور', ''),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'printed',
                'options' => [
                    'digital' => esc_html__('دیجیتالی', ''),
                    'printed' => esc_html__('چاپی', ''),
                ]
            ],
        );
        $this->add_control(
            'scale',
            [
                'label' => esc_html__('scale'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['custom'],
                'range' => [
                    'custom' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 65,
                ]
            ]
        );

        $this->add_control(
            'background_color_2',
            [
                'label' => esc_html__( 'رنگ پس زمینه فاکتور' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => "#111111",
                'selectors' => [
                    'body' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section();
    }

}

