<?php

namespace Pargar\Includes\Widgets;

use Pargar\Includes\Widgets\AllProductsCount\AllProductsCount;
use Pargar\Includes\Widgets\AllProductsDiscount\AllProductsDiscount;
use Pargar\Includes\Widgets\BarCode\BarCodeMaker;
use Pargar\Includes\Widgets\Coupons\Coupons;
use Pargar\Includes\Widgets\CouponsTotalDiscount\CouponsTotalDiscount;
use Pargar\Includes\Widgets\CustomerAddress\CustomerAddress;
use Pargar\Includes\Widgets\CustomerAddress2\CustomerAddress2;
use Pargar\Includes\Widgets\CustomerCity\CustomerCity;
use Pargar\Includes\Widgets\CustomerCountry\CustomerCountry;
use Pargar\Includes\Widgets\CustomerEmail\CustomerEmail;
use Pargar\Includes\Widgets\CustomerFirstName\CustomerFirstName;
use Pargar\Includes\Widgets\CustomerFullName\CustomerFullName;
use Pargar\Includes\Widgets\CustomerLastName\CustomerLastName;
use Pargar\Includes\Widgets\CustomerMeta\CustomerMeta;
use Pargar\Includes\Widgets\CustomerNote\CustomerNote;
use Pargar\Includes\Widgets\CustomerPaymentMethod\CustomerPaymentMethod;
use Pargar\Includes\Widgets\CustomerPhone\CustomerPhone;
use Pargar\Includes\Widgets\CustomerPostCode\CustomerPostCode;
use Pargar\Includes\Widgets\CustomerState\CustomerState;
use Pargar\Includes\Widgets\FinalOrderPrice\FinalOrderPrice;
use Pargar\Includes\Widgets\MarketAddress\MarketAddress;
use Pargar\Includes\Widgets\MarketEconomical\MarketEconomical;
use Pargar\Includes\Widgets\MarketEmail\MarketEmail;
use Pargar\Includes\Widgets\MarketIcon\MarketIcon;
use Pargar\Includes\Widgets\MarketNationalNumber\MarketNationalNumber;
use Pargar\Includes\Widgets\MarketPhone\MarketPhone;
use Pargar\Includes\Widgets\MarketPostCode\MarketPostCode;
use Pargar\Includes\Widgets\MarketRegistrationNumber\MarketRegistrationNumber;
use Pargar\Includes\Widgets\MarketSignature\MarketSignature;
use Pargar\Includes\Widgets\MarketTitle\MarketTitle;
use Pargar\Includes\Widgets\MarketUrl\MarketUrl;
use Pargar\Includes\Widgets\OrderCode\OrderCode;
use Pargar\Includes\Widgets\OrderCompletedDate\OrderCompletedDate;
use Pargar\Includes\Widgets\OrderCreatedDate\OrderCreatedDate;
use Pargar\Includes\Widgets\OrderFee\OrderFee;
use Pargar\Includes\Widgets\OrderMeta\OrderMeta;
use Pargar\Includes\Widgets\OrderStatus\OrderStatus;
use Pargar\Includes\Widgets\PrintDate\PrintDate;
use Pargar\Includes\Widgets\ProductsTable\ProductsTable;
use Pargar\Includes\Widgets\QrCode\QrCodeMaker;
use Pargar\Includes\Widgets\SellerNote\SellerNote;
use Pargar\Includes\Widgets\ShippingMethod\ShippingMethod;
use Pargar\Includes\Widgets\ShippingPrice\ShippingPrice;
use Pargar\Includes\Widgets\TaxPrice\TaxPrice;
use Pargar\Includes\Widgets\TotalsTable\TotalsTable;
use Pargar\Includes\Widgets\WaterMark\WaterMark;

class WidgetManager {

    private static $_instance = null;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'elementor/widgets/register' , [ $this , 'registerWidgets' ] , 10 , 1 );
        add_action( 'elementor/elements/categories_registered' , [$this,'registerWidgetsCategory'] , 10 , 1 );
        add_action( 'elementor/frontend/before_register_styles', [ $this, 'registerStyles' ] );
    }

    public function registerWidgets( $widget_manager ){
        global $post;
        if ( $post->post_type === PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            $widget_manager->register( ProductsTable::instance() );
            $widget_manager->register( TotalsTable::instance() );
            $widget_manager->register( QrCodeMaker::instance() );
            $widget_manager->register( BarCodeMaker::instance() );
            $widget_manager->register( AllProductsCount::instance() );
            $widget_manager->register( AllProductsDiscount::instance() );
            $widget_manager->register( CustomerAddress::instance() );
            $widget_manager->register( CustomerAddress2::instance() );
            $widget_manager->register( CustomerCountry::instance() );
            $widget_manager->register( CustomerCity::instance() );
            $widget_manager->register( CustomerEmail::instance() );
            $widget_manager->register( CustomerFirstName::instance() );
            $widget_manager->register( CustomerLastName::instance() );
            $widget_manager->register( CustomerFullName::instance() );
            $widget_manager->register( CustomerNote::instance() );
            $widget_manager->register( SellerNote::instance() );
            $widget_manager->register( CustomerPaymentMethod::instance() );
            $widget_manager->register( CustomerPhone::instance() );
            $widget_manager->register( CustomerPostCode::instance() );
            $widget_manager->register( ShippingMethod::instance() );
            $widget_manager->register( ShippingPrice::instance() );
            $widget_manager->register( CustomerState::instance() );
            $widget_manager->register( FinalOrderPrice::instance() );
            $widget_manager->register( OrderCode::instance() );
            $widget_manager->register( OrderCompletedDate::instance() );
            $widget_manager->register( OrderCreatedDate::instance() );
            $widget_manager->register( PrintDate::instance() );
            $widget_manager->register( OrderStatus::instance() );
            $widget_manager->register( OrderFee::instance() );
            $widget_manager->register( TaxPrice::instance() );
            $widget_manager->register( WaterMark::instance() );
            $widget_manager->register( Coupons::instance() );
            $widget_manager->register( CouponsTotalDiscount::instance() );
            //$widget_manager->register( OrderMeta::instance() );
            $widget_manager->register( CustomerMeta::instance() );
            $widget_manager->register( MarketTitle::instance() );
            $widget_manager->register( MarketAddress::instance() );
            $widget_manager->register( MarketEconomical::instance() );
            $widget_manager->register( MarketEmail::instance() );
            $widget_manager->register( MarketIcon::instance() );
            $widget_manager->register( MarketSignature::instance() );
            $widget_manager->register( MarketNationalNumber::instance() );
            $widget_manager->register( MarketPhone::instance() );
            $widget_manager->register( MarketPostCode::instance() );
            $widget_manager->register( MarketRegistrationNumber::instance() );
            $widget_manager->register( MarketUrl::instance() );
        }
    }

    public function registerWidgetsCategory( $elements_manager ) {
        global $post;
        if ( $post->post_type === PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            $elements_manager->add_category(
                PARGAR_ELEMENTOR_WIDGET_GROUP_NAME ,
                [
                    'title' => esc_html__( 'فاکتور ساز', PARGAR_TEXT_DOMAIN_NAME ),
                    'icon' => 'fa fa-plug',
                ]
            );
        }
    }

    public function registerStyles( ) {
        wp_register_script( 'MAGIC_FACTOR_BARCODE_JS_SCRIPT' , PARGAR_URL . 'assets/js/BarCode.js' , [] , PARGAR_VERSION , false );
        wp_register_script( 'MAGIC_FACTOR_QRCODE_JS_SCRIPT' , PARGAR_URL . 'assets/js/QR-maker.js' , [] , PARGAR_VERSION , false );
    }

}
