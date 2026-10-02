<?php
if ( ! function_exists('pargar_svg_style') ) {
    function pargar_svg_style(string $path, bool $return = false )
    {
        $style = "mask-image: url(%s);-webkit-mask-image: url(%s);";
        $icon = esc_url( PARGAR_URL."assets/svg/".$path );
        if ( $return ){
            return sprintf( $style, $icon, $icon);
        }
        printf( $style, $icon, $icon);
    }
}

if ( ! function_exists('pargar_get_setting') ) {
    /**
     * Retrieves a setting value from the database.
     * This function retrieves the value of a setting from the database based on the provided key (`setting_key`).
     * If the setting is not found in the database, the provided default value (`default`) is returned.
     * @since 1.0.0
     * @param string $setting_key The key of the setting to retrieve.
     * @param bool $return_value If `true`, the raw setting value will be returned directly.
     *                            If `false`, an object containing the setting value will be returned.
     *                            Default: `false`.
     * @param mixed $default The default value to return if the setting with the specified key is not found in the database.
     *                        Default: `null`.
     * @return array|mixed|object|\stdClass|string|null
     *
     */
    function pargar_get_setting(string $setting_key, bool $return_value = false, $default = null )
    {
        return \Pargar\Includes\Processing\Admin\PargarSetting::instance()->get( $setting_key, $return_value, $default );
    }
}
if ( ! function_exists('pargar_get_setting_group') ) {
    /**
     * Retrieves multiple settings from the database as a group.
     *
     * This function retrieves multiple settings from the database based on the provided array of setting keys.
     * You can specify default values for settings that might not exist in the database.
     * @since 1.0.0
     * @param string $setting_key An array of setting keys to retrieve.
     * @param bool $pares_setting If `true`, the raw setting values will be returned directly.
     *                             If `false`, an object containing the setting values will be returned.
     *                             Default: `false`.
     * @param array $default An associative array of default values for settings not found in the database.
     *                        The keys of this array should match the setting keys. Default: An empty array.
     * @return array|object|\stdClass[]|null
     */
    function pargar_get_setting_group(array $setting_key, bool $pares_setting = false , array $default = array() )
    {
        return \Pargar\Includes\Processing\Admin\PargarSetting::instance()->getGroup(  $setting_key, $pares_setting , $default );
    }
}

if ( ! function_exists('pargar_view_popup_export_pdf') ) {
    function pargar_view_popup_export_pdf($data = array() ) {
        require_once PARGAR_PATH_TEMPLATE.'/popup-export-pdf/index.php';
    }
}

if ( ! function_exists('pargar_create_invoice_url') ) {
    function pargar_create_invoice_url($params = array() ) {
        return add_query_arg($params, home_url());
    }
}

if ( ! function_exists('pargar_pre_invoice_page_url') ) {
    function pargar_pre_invoice_page_url($attr = array() ) {
        if ( WC()->cart->is_empty() ){
            wp_redirect(get_permalink(get_page_by_path('404')));
            exit;
        }
        $post_id = pargar_get_setting( 'invoice_template_id', true );
        $new_params = array(
            'invoice_p' => $post_id,
            'print_pre_invoice' => "yes",
        );

        $new_params = array_merge( $new_params, $attr );
        return pargar_create_invoice_url( $new_params) ;
    }
}

if ( ! function_exists('pargar_invoice_page_url') ) {
    function pargar_invoice_page_url($order_id , $attr = array() ) {
        $post_id = pargar_get_setting( 'invoice_template_id', true );
        $new_params = array(
            'invoice_p' => $post_id,
            'order_id' => $order_id,
            'print_invoice' => "yes",
        );

        $new_params = array_merge( $new_params, $attr );
        return pargar_create_invoice_url( $new_params) ;
        //return esc_url( "http://127.0.0.1/wp4/magic_factor/elementor-3254/?order_id={$order_id}&print_invoice=yes" );
    }
}
if ( ! function_exists('pargar_invoice_page_url_short') ) {
    function pargar_invoice_page_url_short($order_id ) {
        $new_params = array(
            'o' => $order_id,
            'p' => "y"
        );
        return add_query_arg($new_params, home_url());
    }
}

if ( ! function_exists('pargar_class_export_screenshot_png') ) {
    function pargar_class_export_screenshot_png()
    {
        $value = pargar_get_setting( "screenshot_invoice_png", true, "off" );
        if ( $value == 'on' ){
            return "pargar_png_screenshot_in_79847845";
        }
        return "";
    }
}
if ( ! function_exists('pargar_active_export_screenshot_png') ) {
    function pargar_active_export_screenshot_png()
    {
        $value = pargar_get_setting( "screenshot_invoice_png", true, "off" );
        if ( $value == 'on' ){
            return true;
        }
        return false;
    }
}