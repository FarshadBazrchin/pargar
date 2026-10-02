<?php

namespace Pargar\Includes\Core;

use Elementor\Plugin;
use Pargar\Includes\DynamicTags\DynamicTagManager;
use Pargar\Includes\Processing\Admin\AdminPageSetting;
use Pargar\Includes\Processing\Admin\AjaxSetting;
use Pargar\Includes\Processing\Admin\PargarSetting;
use Pargar\Includes\Processing\Email;
use Pargar\Includes\Processing\ExportTemplate;
use Pargar\Includes\Processing\ImportTemplate;
use Pargar\Includes\Processing\InvoicePostType;
use Pargar\Includes\Processing\Sms;
use Pargar\Includes\Processing\WoocommerceOrderAction;
use Pargar\Includes\Widgets\WidgetManager;


class Core {

    private static $_instance;
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {

        add_action('admin_notices', [ $this, 'check_woocommerce_installed' ] );

        if ( $this->isCheckPluginWoo() ) {
            $this->Actions();
            $this->Filters();
            WidgetManager::instance();
            DynamicTagManager::instance();
            InvoicePostType::instance();
            ExportTemplate::instance();
            AdminPageSetting::instance();
            WoocommerceOrderAction::instance();
            Email::instance();
            Sms::instance();
            Ajax::instance();

            $obj_import_templated = new ImportTemplate();
            $obj_import_templated->ajax();
        }

    }

    public function check_woocommerce_installed() {
        if (! $this->isCheckPluginWoo() ) {
            echo '<div class="notice notice-error"><p>⚠️ افزونه ووکامرس نصب یا فعال نیست! لطفاً آن را نصب و فعال کنید.</p></div>';
        }
    }


    public function isCheckPluginWoo()
    {
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            return false;
        }
        return true;
    }


    public function Actions( ) {
        add_action( 'init' , [ $this , 'init' ] );
        add_action( 'woocommerce_checkout_order_created', [ $this , 'onNewOrder' ] , 11 , 1);
        add_action( 'woocommerce_order_add_product' , [ $this , 'adminCreateOrder' ] , 11 , 3 );
        add_action( 'woocommerce_proceed_to_checkout' , [ $this , 'WoocommerceCartPreInvoiceBtn' ] );
        add_action( 'woocommerce_review_order_before_submit' , [ $this , 'WoocommerceCheckoutPreInvoice'] , 20 );
        add_action( 'woocommerce_order_details_after_order_table' , [ $this , 'WoocommerceOrderInvoiceBtn' ] , 10 ,1 );

        add_action('admin_init', array( $this, 'updatePlugin' ) );
        add_action('admin_init', array( $this, 'elementorCptSupport' ) );
        add_action('admin_init', array( $this, 'deleteHtml' ) );

    }

    public function deleteHtml()
    {
        $folder = PARGAR_PATH . 'assets/handle';
        $files = glob("$folder/*.html");
        $now = time();
        foreach ($files as $file) {
            if ($now - filemtime($file) > 300) {
                unlink($file);
            }
        }
    }

    public function updatePlugin()
    {
        $get_ver = get_option( 'pargar_version_code' , "0" );
        if ( intval($get_ver) !== PARGAR_VERTION_CODE){
            DataBase::instance()->createTable();
            PargarSetting::instance()->default();
        }
        update_option( 'pargar_version_code', PARGAR_VERTION_CODE );
    }

    public function elementorCptSupport()
    {
        $current_support = get_option( 'elementor_cpt_support', array() );
        if ( ! in_array( PARGAR_UNIQUE_TEMPLATE_NAEM, $current_support ) ) {
            $current_support[] = PARGAR_UNIQUE_TEMPLATE_NAEM;
            update_option( 'elementor_cpt_support', $current_support );
        }
    }

    public function Filters( ) {
        add_filter( 'elementor/template-library/create_new_dialog_types',[$this,'CreateNewTemplateType'] , 10 , 2 );
        add_filter( 'woocommerce_order_item_get_formatted_meta_data', [ $this , 'RemoveSpecificMetaFromCart' ] , 20 , 2 );
        add_filter( 'pargar_invoice_templates' , [ $this , 'RegisterStaticTemplates' ] );
        add_filter( 'pargar_invoice_template_tm1' , [ $this , 'StaticTemplateTM1Path' ] );
        add_filter( 'pargar_invoice_template_pos' , [ $this , 'StaticTemplatePosPath' ] );
        add_filter( 'pargar_address_label_template_template1' , [ $this , 'AddressLabelTemplate1' ] , 11 ,1 );
        add_filter( 'pargar_address_label_template_template2' , [ $this , 'AddressLabelTemplate2' ] , 11 ,1 );
        add_filter( 'pargar_product_label_template' , [ $this , 'ProductLabelTemplate' ] , 11 ,1 );
        add_filter( 'pargar_default_invoice_template' , [ $this , 'PargarDefaultTemplate' ] );
        add_filter( 'pargar_address_label_templates' , [ $this , 'PargarSetAddressLabelTemplates' ] );
    }

    public function init() {
        if (version_compare($GLOBALS['wp_version'], '6.7', '<')) {
            load_plugin_textdomain(PARGAR_TEXT_DOMAIN_NAME, dirname(plugin_basename(__FILE__) ) . '/languages' );
        } else {
            global $l10n, $wp_textdomain_registry;
            $domain = PARGAR_TEXT_DOMAIN_NAME;
            $locale = get_locale();
            $wp_textdomain_registry->set($domain, $locale, PARGAR_PATH . '/languages');
            if (isset($l10n[$domain])) {
                unset($l10n[$domain]);
            }
            load_theme_textdomain($domain, PARGAR_PATH . '/languages');
        }
    }

    public function onNewOrder( $order ) {
        foreach ( $order->get_items() as $item ) {
            $product = $item->get_product();

            $item->update_meta_data('pargar_was_on_sale', $product->is_on_sale() );
            $item->update_meta_data('pargar_regular_price', $product->get_regular_price());
            $item->update_meta_data('pargar_sale_price', $product->get_sale_price());

        }
        $order->save();
    }

    public function adminCreateOrder( $order_id, $item_id, $product ) {
        if ( !is_admin() ) {
            return;
        }

        wc_update_order_item_meta($item_id, 'pargar_was_on_sale', $product->is_on_sale() );
        wc_update_order_item_meta($item_id, 'pargar_regular_price', $product->get_regular_price() );
        wc_update_order_item_meta($item_id, 'pargar_sale_price', $product->get_sale_price() );

        $order = wc_get_order($order_id);
        $order->save();
    }

    public function CreateNewTemplateType( $a , $b ){
        $a[PARGAR_UNIQUE_TEMPLATE_NAEM] = esc_html__( "فاکتور" , PARGAR_TEXT_DOMAIN_NAME ) ;
        return $a;
    }

    public function RemoveSpecificMetaFromCart( $formatted_meta, $item ) {
        $keys = [
            'pargar_regular_price' ,
            'pargar_sale_price' ,
            'pargar_was_on_sale' ,
        ];
        foreach( $formatted_meta as $key => $meta ){
            if( in_array( $meta->key, $keys ) )
                unset($formatted_meta[$key]);
        }
        return $formatted_meta;
    }

    public static function encryptDecrypt( string $action, string $string )
    {
        $output = false;
        $encrypt_method = "aes-256-gcm";
        $secret_key = "1njfd88JHhk/-R!@VBp_kajcveb7uy329!bu@if#eojqw2-p";
        $secret_iv = "JK(UIb@ec12v9Cu&!gh932*_01H2+UP0-3V*BV#WS";
        $key = hash('sha256', $secret_key , true);
        $iv = substr(hash('sha256', $secret_iv), 0, 12);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, OPENSSL_RAW_DATA, $iv);
        } else {
            if ($action == 'decrypt') {
                $output = openssl_decrypt($string, $encrypt_method, $key,OPENSSL_RAW_DATA, $iv);
            }
        }
        return $output;
    }

    public static function encryptAesGcm( $data_to_encrypt )
    {
        $secret_key = "1njfd88JHhkVBp_kajcveb7uy329!bu@if#eojqw2-p";
        $secret_iv = "JK(UIb@ecv9Cu&!gh932_01H2+UP0-3BV#WS";
        $key = hash('sha256', $secret_key, true);
        $iv = substr(hash('sha256', $secret_iv), 0, 12);
        $cipher = 'aes-256-gcm';
        $tag = '';
        $encrypted_data = openssl_encrypt($data_to_encrypt, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return [
            'encrypted_data' => $encrypted_data,
            'tag' => $tag,
        ];
    }

    public static function decryptAesGcm( $encrypted_data,$tag )
    {
        $secret_key = "1njfd88JHhkVBp_kajcveb7uy329!bu@if#eojqw2-p";
        $secret_iv = "JK(UIb@ecv9Cu&!gh932_01H2+UP0-3BV#WS";
        $key = hash('sha256', $secret_key, true);
        $iv = substr(hash('sha256', $secret_iv), 0, 12);
        $cipher = 'aes-256-gcm';
        return openssl_decrypt($encrypted_data, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
    }

    public function WoocommerceCheckoutPreInvoice( ) {
        if ( pargar_get_setting( 'pre_invoice_show_in_cart_checkout' , true , 'off' ) === 'on' ) {
            echo '<a style="margin-bottom:8px;" class="button'. esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" target="_blank" href="'. pargar_pre_invoice_page_url() .'"> '. esc_html__( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME ) .' </a>';
        }
    }

    public function WoocommerceCartPreInvoiceBtn( ) {
        if ( pargar_get_setting( 'pre_invoice_show_in_cart_status' , true , 'off' ) === 'on' ) {
            echo '<a style="margin-bottom:8px;" class="button'. esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" target="_blank" href="'. pargar_pre_invoice_page_url() .'"> '. esc_html__( 'پیش فاکتور' , PARGAR_TEXT_DOMAIN_NAME ) .' </a>';
        }
    }

    public function WoocommerceOrderInvoiceBtn( $order ) {
        if ( is_account_page() ) {
            if ( pargar_get_setting( 'print_invoice_for_user_panel' , true , 'off' ) != 'on' ) {
                return;
            }
        }
        echo '<a style="margin-bottom:8px;" class="button'. esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" target="_blank" href="'. pargar_invoice_page_url( $order->get_id() ) .'"> '. esc_html__( 'فاکتور سفارش' , PARGAR_TEXT_DOMAIN_NAME ) .' </a>';
    }

    public function RegisterStaticTemplates( $templates ) {
        $templates['tm1'] = esc_html__( 'طرح شماره 1' , PARGAR_TEXT_DOMAIN_NAME );
        $templates['pos'] = esc_html__( 'pos' );
        return $templates;
    }

    public function StaticTemplateTM1Path( $templates ) {
        return PARGAR_PATH_TEMPLATE . '/invoices/tm1/index.php';
    }

    public function StaticTemplatePosPath( $templates ) {
        return PARGAR_PATH_TEMPLATE . '/invoices/pos/index.php';
    }

    public function AddressLabelTemplate1 ( ) {
        return PARGAR_PATH . 'Includes/templates/address-label/template1/index.php';
    }

    public function AddressLabelTemplate2 ( ) {
        return PARGAR_PATH . 'Includes/templates/address-label/template2/index.php';
    }

    public function ProductLabelTemplate( $template ) {
        return PARGAR_PATH . '/Includes/templates/product-label/template1/index.php';
    }

    public function PargarDefaultTemplate( ) {
        return 'tm1';
    }

    public function PargarSetAddressLabelTemplates( $default ) {
        $default['template1'] = esc_html__( 'قالب پیشفرض' , PARGAR_TEXT_DOMAIN_NAME );
        $default['template2'] = esc_html__( 'طرح دوم' , PARGAR_TEXT_DOMAIN_NAME );
        return $default;
    }

}
