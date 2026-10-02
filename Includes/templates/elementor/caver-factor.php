<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<style>
    .content-factor span,.content-factor h1,.content-factor h2,
    .content-factor h3,.content-factor h4,.content-factor h5,
    .content-factor h6,.content-factor p ,.content-factor td{
        font-family: "IRANSans" !important;
    }
    .content-factor {
        background: #fff;
        margin: 16px auto;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
    }

    @media print {
        body {
            background-color: transparent !important;
            direction: <?php echo is_rtl() ? 'rtl' : 'ltr' ?> !important;
        }

        html{
            direction: unset !important;
        }
        .content-factor {
            margin: 0;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
        }
        .content-factor{
            width: auto;
        }
        @page {
            margin: 0 !important;
        }
    }
</style>
<?php
if (have_posts()):
    while (have_posts()) : ?>
        <?php the_post(); ?>
        <?php
        $setting_popup = array();
        if (defined('ELEMENTOR_VERSION')) {
            $document = \Elementor\Plugin::$instance->documents->get(get_the_ID());
            $setting_popup['type_page_factor'] = $document->get_settings('type_page_factor');
            $setting_popup['scale'] = $document->get_settings('scale');
        }
        ?>
        <div class="content-factor <?php echo pargar_class_export_screenshot_png()?>">
            <?php the_content(); ?>
        </div>
    <?php
        pargar_view_popup_export_pdf( $setting_popup );
    endwhile;
endif; ?>
<div style="display: none !important;">
    <?php wp_footer(); ?>
</div>

</body>
</html>
