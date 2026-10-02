<?php

namespace Pargar\Includes\DynamicTags;

use Elementor\Plugin;

class DynamicTagManager {

    private static $_instance;
    public static function instance() {
        if ( is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'elementor/dynamic_tags/register' , [$this,'registerDynamicTags'] , 10 , 1 );
        add_action( 'elementor/dynamic_tags/register' , [$this,'registerNewDynamicTagGroup'] , 10 , 1 );
    }

    public function registerDynamicTags( $dynamic_tags_manager  ){
        global $post;
        if ($post->post_type === PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            $dynamic_tags_manager->register( CustomerEmail::instance() );
            $dynamic_tags_manager->register( OrderCode::instance() );
            $dynamic_tags_manager->register( OrderID::instance() );
            $dynamic_tags_manager->register( AllProductsCount::instance() );
            $dynamic_tags_manager->register( AllProductsDiscount::instance() );
            $dynamic_tags_manager->register( FinalOrderPrice::instance() );
            $dynamic_tags_manager->register( CustomerName::instance() );
            $dynamic_tags_manager->register( CustomerFirstName::instance() );
            $dynamic_tags_manager->register( CustomerLastName::instance() );
            $dynamic_tags_manager->register( CustomerPhone::instance() );
            $dynamic_tags_manager->register( MarketAddress::instance() );
            $dynamic_tags_manager->register( MarketEmail::instance() );
            $dynamic_tags_manager->register( MarketPhone::instance() );
            $dynamic_tags_manager->register( CustomerNote::instance() );
            $dynamic_tags_manager->register( CustomerPostCode::instance() );
            $dynamic_tags_manager->register( CustomerAddress::instance() );
            $dynamic_tags_manager->register( CustomerAddress2::instance() );
            $dynamic_tags_manager->register( CustomerCountry::instance() );
            $dynamic_tags_manager->register( CustomerState::instance() );
            $dynamic_tags_manager->register( CustomerCity::instance() );
            $dynamic_tags_manager->register( CustomerOnlyAddress1::instance() );
            $dynamic_tags_manager->register( CustomerOnlyAddress2::instance() );
            $dynamic_tags_manager->register( CustomerPaymentMethod::instance() );
            $dynamic_tags_manager->register( CustomerShippingMethod::instance() );
            $dynamic_tags_manager->register( ShippingPrice::instance() );
            $dynamic_tags_manager->register( TaxPrice::instance() );
            $dynamic_tags_manager->register( OrderCreatedDate::instance() );
            $dynamic_tags_manager->register( OrderCompletedDate::instance() );
            $dynamic_tags_manager->register( PrintDate::instance() );
            $dynamic_tags_manager->register( OrderStatus::instance() );
            $dynamic_tags_manager->register( MarketUrl::instance() );
            $dynamic_tags_manager->register( MarketTitle::instance() );
            $dynamic_tags_manager->register( MarketPostCode::instance() );
            $dynamic_tags_manager->register( MarketRegistrationNumber::instance() );
            $dynamic_tags_manager->register( MarketNationalNumber::instance() );
            $dynamic_tags_manager->register( MarketEconomical::instance() );
            $dynamic_tags_manager->register( MarketIcon::instance() );
            $dynamic_tags_manager->register( Coupons::instance() );
            $dynamic_tags_manager->register( CouponTotalDiscount::instance() );
            $dynamic_tags_manager->register( OrderFee::instance() );
        }
    }

    public function registerNewDynamicTagGroup( $dynamic_tags_manager ) {
        global $post;
        if ($post->post_type === PARGAR_UNIQUE_TEMPLATE_NAEM ) {
            $dynamic_tags_manager->register_group(
                PARGAR_DYNAMIC_TAG_GROUP_NAME ,
                [
                    'title' => esc_html__( 'تگ های فاکتور ساز', PARGAR_TEXT_DOMAIN_NAME )
                ]
            );
        }
    }

}
