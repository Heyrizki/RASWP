<?php
/**
 * Customizer Options
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function kabarlagi_customize_register( $wp_customize ) {
    
    // Panel: Theme Colors
    $wp_customize->add_panel( 'kabarlagi_colors_panel', array(
        'title'       => __( 'Theme Colors', 'kabarlagi' ),
        'priority'    => 30,
        'description' => __( 'Customize theme colors', 'kabarlagi' ),
    ) );
    
    // Primary Color
    $wp_customize->add_setting( 'kabarlagi_primary_color', array(
        'default'           => '#c8102e',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kabarlagi_primary_color', array(
        'label'    => __( 'Primary Color', 'kabarlagi' ),
        'section'  => 'colors',
        'settings' => 'kabarlagi_primary_color',
        'priority' => 10,
    ) ) );
    
    // Secondary Color
    $wp_customize->add_setting( 'kabarlagi_secondary_color', array(
        'default'           => '#1a1a1a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kabarlagi_secondary_color', array(
        'label'    => __( 'Secondary Color', 'kabarlagi' ),
        'section'  => 'colors',
        'settings' => 'kabarlagi_secondary_color',
        'priority' => 11,
    ) ) );
    
    // Accent Color
    $wp_customize->add_setting( 'kabarlagi_accent_color', array(
        'default'           => '#f39c12',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kabarlagi_accent_color', array(
        'label'    => __( 'Accent Color', 'kabarlagi' ),
        'section'  => 'colors',
        'settings' => 'kabarlagi_accent_color',
        'priority' => 12,
    ) ) );
    
    // Dark Mode Background
    $wp_customize->add_setting( 'kabarlagi_dark_bg', array(
        'default'           => '#1a1a1a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kabarlagi_dark_bg', array(
        'label'    => __( 'Dark Mode Background', 'kabarlagi' ),
        'section'  => 'colors',
        'settings' => 'kabarlagi_dark_bg',
        'priority' => 13,
    ) ) );
    
    // Dark Mode Text
    $wp_customize->add_setting( 'kabarlagi_dark_text', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kabarlagi_dark_text', array(
        'label'    => __( 'Dark Mode Text', 'kabarlagi' ),
        'section'  => 'colors',
        'settings' => 'kabarlagi_dark_text',
        'priority' => 14,
    ) ) );
    
    // Panel: Typography
    $wp_customize->add_panel( 'kabarlagi_typography_panel', array(
        'title'       => __( 'Typography', 'kabarlagi' ),
        'priority'    => 35,
        'description' => __( 'Customize fonts and typography', 'kabarlagi' ),
    ) );
    
    // Font Family Base
    $wp_customize->add_setting( 'kabarlagi_font_family', array(
        'default'           => 'Inter, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_font_family', array(
        'label'    => __( 'Base Font Family', 'kabarlagi' ),
        'section'  => 'title_tagline',
        'settings' => 'kabarlagi_font_family',
        'type'     => 'select',
        'choices'  => array(
            'Inter, sans-serif'      => 'Inter',
            'Roboto, sans-serif'     => 'Roboto',
            'Open Sans, sans-serif'  => 'Open Sans',
            'Lato, sans-serif'       => 'Lato',
            'Montserrat, sans-serif' => 'Montserrat',
            'Poppins, sans-serif'    => 'Poppins',
            'Merriweather, serif'    => 'Merriweather',
            'Playfair Display, serif'=> 'Playfair Display',
        ),
        'priority' => 10,
    ) );
    
    // Font Size Base
    $wp_customize->add_setting( 'kabarlagi_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_font_size', array(
        'label'       => __( 'Base Font Size (px)', 'kabarlagi' ),
        'section'     => 'title_tagline',
        'settings'    => 'kabarlagi_font_size',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
        'priority' => 11,
    ) );
    
    // Section: Layout Settings
    $wp_customize->add_section( 'kabarlagi_layout', array(
        'title'    => __( 'Layout Settings', 'kabarlagi' ),
        'priority' => 40,
    ) );
    
    // Site Layout
    $wp_customize->add_setting( 'kabarlagi_site_layout', array(
        'default'           => 'full-width',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_site_layout', array(
        'label'    => __( 'Site Layout', 'kabarlagi' ),
        'section'  => 'kabarlagi_layout',
        'settings' => 'kabarlagi_site_layout',
        'type'     => 'radio',
        'choices'  => array(
            'full-width'   => __( 'Full Width', 'kabarlagi' ),
            'boxed'        => __( 'Boxed', 'kabarlagi' ),
            'wide'         => __( 'Wide', 'kabarlagi' ),
        ),
        'priority' => 10,
    ) );
    
    // Homepage Layout
    $wp_customize->add_setting( 'kabarlagi_homepage_layout', array(
        'default'           => 'layout-1',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_homepage_layout', array(
        'label'    => __( 'Homepage Layout', 'kabarlagi' ),
        'section'  => 'kabarlagi_layout',
        'settings' => 'kabarlagi_homepage_layout',
        'type'     => 'select',
        'choices'  => array(
            'layout-1' => __( 'Layout 1 - Classic', 'kabarlagi' ),
            'layout-2' => __( 'Layout 2 - Modern', 'kabarlagi' ),
            'layout-3' => __( 'Layout 3 - Magazine', 'kabarlagi' ),
        ),
        'priority' => 11,
    ) );
    
    // Post Style
    $wp_customize->add_setting( 'kabarlagi_post_style', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_post_style', array(
        'label'    => __( 'Default Post Style', 'kabarlagi' ),
        'section'  => 'kabarlagi_layout',
        'settings' => 'kabarlagi_post_style',
        'type'     => 'select',
        'choices'  => array(
            'grid'       => __( 'Grid', 'kabarlagi' ),
            'list'       => __( 'List', 'kabarlagi' ),
            'carousel'   => __( 'Carousel', 'kabarlagi' ),
            'background' => __( 'Background Image', 'kabarlagi' ),
        ),
        'priority' => 12,
    ) );
    
    // Section: Dark Mode
    $wp_customize->add_section( 'kabarlagi_dark_mode', array(
        'title'    => __( 'Dark Mode', 'kabarlagi' ),
        'priority' => 45,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_dark_mode', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_dark_mode', array(
        'label'    => __( 'Dark Mode Setting', 'kabarlagi' ),
        'section'  => 'kabarlagi_dark_mode',
        'settings' => 'kabarlagi_dark_mode',
        'type'     => 'radio',
        'choices'  => array(
            'light' => __( 'Light Mode', 'kabarlagi' ),
            'dark'  => __( 'Dark Mode', 'kabarlagi' ),
            'auto'  => __( 'Auto (System)', 'kabarlagi' ),
        ),
        'priority' => 10,
    ) );
    
    // Section: Breaking News
    $wp_customize->add_section( 'kabarlagi_breaking_news', array(
        'title'    => __( 'Breaking News', 'kabarlagi' ),
        'priority' => 50,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_breaking_news_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_breaking_news_enable', array(
        'label'    => __( 'Enable Breaking News', 'kabarlagi' ),
        'section'  => 'kabarlagi_breaking_news',
        'settings' => 'kabarlagi_breaking_news_enable',
        'type'     => 'checkbox',
        'priority' => 10,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_breaking_news_title', array(
        'default'           => __( 'Breaking News', 'kabarlagi' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_breaking_news_title', array(
        'label'    => __( 'Breaking News Title', 'kabarlagi' ),
        'section'  => 'kabarlagi_breaking_news',
        'settings' => 'kabarlagi_breaking_news_title',
        'type'     => 'text',
        'priority' => 11,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_breaking_news_category', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_breaking_news_category', array(
        'label'       => __( 'Breaking News Category', 'kabarlagi' ),
        'section'     => 'kabarlagi_breaking_news',
        'settings'    => 'kabarlagi_breaking_news_category',
        'type'        => 'dropdown-categories',
        'priority'    => 12,
    ) );
    
    // Section: Social Media
    $wp_customize->add_section( 'kabarlagi_social', array(
        'title'    => __( 'Social Media', 'kabarlagi' ),
        'priority' => 55,
    ) );
    
    $social_networks = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter/X',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
        'tiktok'    => 'TikTok',
        'linkedin'  => 'LinkedIn',
        'telegram'  => 'Telegram',
        'whatsapp'  => 'WhatsApp',
    );
    
    foreach ( $social_networks as $network => $label ) {
        $wp_customize->add_setting( 'kabarlagi_social_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'kabarlagi_social_' . $network, array(
            'label'    => sprintf( __( '%s URL', 'kabarlagi' ), $label ),
            'section'  => 'kabarlagi_social',
            'settings' => 'kabarlagi_social_' . $network,
            'type'     => 'url',
            'priority' => 10 + array_search( $network, array_keys( $social_networks ) ),
        ) );
    }
    
    // Section: Advertisement
    $wp_customize->add_section( 'kabarlagi_ads', array(
        'title'    => __( 'Advertisement', 'kabarlagi' ),
        'priority' => 60,
    ) );
    
    // Header Ad
    $wp_customize->add_setting( 'kabarlagi_header_ad', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_header_ad', array(
        'label'    => __( 'Header Ad Image URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_header_ad',
        'type'     => 'text',
        'priority' => 10,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_header_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_header_ad_link', array(
        'label'    => __( 'Header Ad Link URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_header_ad_link',
        'type'     => 'text',
        'priority' => 11,
    ) );
    
    // Below Header Ad
    $wp_customize->add_setting( 'kabarlagi_below_header_ad', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_below_header_ad', array(
        'label'    => __( 'Below Header Ad Image URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_below_header_ad',
        'type'     => 'text',
        'priority' => 12,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_below_header_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_below_header_ad_link', array(
        'label'    => __( 'Below Header Ad Link URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_below_header_ad_link',
        'type'     => 'text',
        'priority' => 13,
    ) );
    
    // Floating Left Ad
    $wp_customize->add_setting( 'kabarlagi_floating_left_ad', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_floating_left_ad', array(
        'label'    => __( 'Floating Left Ad Image URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_floating_left_ad',
        'type'     => 'text',
        'priority' => 14,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_floating_left_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_floating_left_ad_link', array(
        'label'    => __( 'Floating Left Ad Link URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_floating_left_ad_link',
        'type'     => 'text',
        'priority' => 15,
    ) );
    
    // Floating Right Ad
    $wp_customize->add_setting( 'kabarlagi_floating_right_ad', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_floating_right_ad', array(
        'label'    => __( 'Floating Right Ad Image URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_floating_right_ad',
        'type'     => 'text',
        'priority' => 16,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_floating_right_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_floating_right_ad_link', array(
        'label'    => __( 'Floating Right Ad Link URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_floating_right_ad_link',
        'type'     => 'text',
        'priority' => 17,
    ) );
    
    // Parallax Ad
    $wp_customize->add_setting( 'kabarlagi_parallax_ad', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_parallax_ad', array(
        'label'    => __( 'Parallax Ad Image URL (Inside Article)', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_parallax_ad',
        'type'     => 'text',
        'priority' => 18,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_parallax_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_parallax_ad_link', array(
        'label'    => __( 'Parallax Ad Link URL', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_parallax_ad_link',
        'type'     => 'text',
        'priority' => 19,
    ) );
    
    // AdSense Code
    $wp_customize->add_setting( 'kabarlagi_adsense_code', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_adsense_code', array(
        'label'    => __( 'Google AdSense Code', 'kabarlagi' ),
        'section'  => 'kabarlagi_ads',
        'settings' => 'kabarlagi_adsense_code',
        'type'     => 'textarea',
        'priority' => 20,
    ) );
    
    // Section: Footer Settings
    $wp_customize->add_section( 'kabarlagi_footer', array(
        'title'    => __( 'Footer Settings', 'kabarlagi' ),
        'priority' => 65,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_footer_text', array(
        'default'           => __( '&copy; 2024 Kabar Lagi. All rights reserved.', 'kabarlagi' ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_footer_text', array(
        'label'    => __( 'Footer Copyright Text', 'kabarlagi' ),
        'section'  => 'kabarlagi_footer',
        'settings' => 'kabarlagi_footer_text',
        'type'     => 'textarea',
        'priority' => 10,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_footer_layout', array(
        'default'           => '3-columns',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_footer_layout', array(
        'label'    => __( 'Footer Widget Layout', 'kabarlagi' ),
        'section'  => 'kabarlagi_footer',
        'settings' => 'kabarlagi_footer_layout',
        'type'     => 'select',
        'choices'  => array(
            '1-column'  => __( '1 Column', 'kabarlagi' ),
            '2-columns' => __( '2 Columns', 'kabarlagi' ),
            '3-columns' => __( '3 Columns', 'kabarlagi' ),
            '4-columns' => __( '4 Columns', 'kabarlagi' ),
        ),
        'priority' => 11,
    ) );
    
    // Section: Single Post Settings
    $wp_customize->add_section( 'kabarlagi_single_post', array(
        'title'    => __( 'Single Post Settings', 'kabarlagi' ),
        'priority' => 70,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_show_views', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_show_views', array(
        'label'    => __( 'Show Post Views Count', 'kabarlagi' ),
        'section'  => 'kabarlagi_single_post',
        'settings' => 'kabarlagi_show_views',
        'type'     => 'checkbox',
        'priority' => 10,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_show_reading_time', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_show_reading_time', array(
        'label'    => __( 'Show Reading Time', 'kabarlagi' ),
        'section'  => 'kabarlagi_single_post',
        'settings' => 'kabarlagi_show_reading_time',
        'type'     => 'checkbox',
        'priority' => 11,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_show_breadcrumb', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_show_breadcrumb', array(
        'label'    => __( 'Show Breadcrumb Navigation', 'kabarlagi' ),
        'section'  => 'kabarlagi_single_post',
        'settings' => 'kabarlagi_show_breadcrumb',
        'type'     => 'checkbox',
        'priority' => 12,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_show_related_posts', array(
        'label'    => __( 'Show Related Posts', 'kabarlagi' ),
        'section'  => 'kabarlagi_single_post',
        'settings' => 'kabarlagi_show_related_posts',
        'type'     => 'checkbox',
        'priority' => 13,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_inline_related_count', array(
        'default'           => 2,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_inline_related_count', array(
        'label'       => __( 'Inline Related Posts Count', 'kabarlagi' ),
        'section'     => 'kabarlagi_single_post',
        'settings'    => 'kabarlagi_inline_related_count',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'priority' => 14,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_show_share_buttons', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_show_share_buttons', array(
        'label'    => __( 'Show Share Buttons', 'kabarlagi' ),
        'section'  => 'kabarlagi_single_post',
        'settings' => 'kabarlagi_show_share_buttons',
        'type'     => 'checkbox',
        'priority' => 15,
    ) );
    
    // Section: Push Notification
    $wp_customize->add_section( 'kabarlagi_notification', array(
        'title'    => __( 'Push Notifications', 'kabarlagi' ),
        'priority' => 75,
    ) );
    
    $wp_customize->add_setting( 'kabarlagi_push_notification_enable', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( 'kabarlagi_push_notification_enable', array(
        'label'    => __( 'Enable Push Comment Notification', 'kabarlagi' ),
        'section'  => 'kabarlagi_notification',
        'settings' => 'kabarlagi_push_notification_enable',
        'type'     => 'checkbox',
        'priority' => 10,
    ) );
}
add_action( 'customize_register', 'kabarlagi_customize_register' );

// Custom CSS output
function kabarlagi_custom_css() {
    $primary_color   = get_theme_mod( 'kabarlagi_primary_color', '#c8102e' );
    $secondary_color = get_theme_mod( 'kabarlagi_secondary_color', '#1a1a1a' );
    $accent_color    = get_theme_mod( 'kabarlagi_accent_color', '#f39c12' );
    $dark_bg         = get_theme_mod( 'kabarlagi_dark_bg', '#1a1a1a' );
    $dark_text       = get_theme_mod( 'kabarlagi_dark_text', '#ffffff' );
    $font_family     = get_theme_mod( 'kabarlagi_font_family', 'Inter, sans-serif' );
    $font_size       = get_theme_mod( 'kabarlagi_font_size', '16' );
    
    $css = ":root {
        --kl-primary-color: {$primary_color};
        --kl-secondary-color: {$secondary_color};
        --kl-accent-color: {$accent_color};
        --kl-font-family: {$font_family};
        --kl-font-size: {$font_size}px;
    }
    
    body {
        font-family: var(--kl-font-family);
        font-size: var(--kl-font-size);
    }
    
    .dark-mode, [data-theme='dark'] {
        --kl-bg-color: {$dark_bg};
        --kl-text-color: {$dark_text};
    }";
    
    return $css;
}
