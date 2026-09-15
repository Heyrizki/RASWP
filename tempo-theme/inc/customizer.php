<?php
/**
 * Customizer additions for Tempo News theme
 *
 * @package Tempo_News
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description
 */
function tempo_news_customize_register($wp_customize) {
    
    // ============================================
    // THEME COLORS PANEL
    // ============================================
    $wp_customize->add_panel('tempo_colors_panel', array(
        'title'       => __('Theme Colors', 'tempo-news'),
        'description' => __('Customize theme colors', 'tempo-news'),
        'priority'    => 30,
    ));
    
    // Primary Color
    $wp_customize->add_setting('tempo_primary_color', array(
        'default'           => '#c90f0f',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tempo_primary_color', array(
        'label'    => __('Primary Color', 'tempo-news'),
        'section'  => 'colors',
        'settings' => 'tempo_primary_color',
    )));
    
    // ============================================
    // TYPOGRAPHY SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_typography', array(
        'title'    => __('Typography', 'tempo-news'),
        'priority' => 35,
    ));
    
    // Heading Font
    $wp_customize->add_setting('tempo_font_heading', array(
        'default'           => 'Georgia',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tempo_font_heading', array(
        'label'   => __('Heading Font', 'tempo-news'),
        'section' => 'tempo_typography',
        'type'    => 'select',
        'choices' => array(
            'Georgia'   => 'Georgia',
            'Arial'     => 'Arial',
            'Verdana'   => 'Verdana',
            'Times'     => 'Times New Roman',
            'Courier'   => 'Courier New',
        ),
    ));
    
    // Body Font
    $wp_customize->add_setting('tempo_font_body', array(
        'default'           => 'Arial',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tempo_font_body', array(
        'label'   => __('Body Font', 'tempo-news'),
        'section' => 'tempo_typography',
        'type'    => 'select',
        'choices' => array(
            'Arial'     => 'Arial',
            'Verdana'   => 'Verdana',
            'Georgia'   => 'Georgia',
            'Times'     => 'Times New Roman',
            'Helvetica' => 'Helvetica',
        ),
    ));
    
    // ============================================
    // HOMEPAGE LAYOUT SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_homepage', array(
        'title'    => __('Homepage Settings', 'tempo-news'),
        'priority' => 36,
    ));
    
    // Homepage Layout
    $wp_customize->add_setting('tempo_homepage_layout', array(
        'default'           => 'layout-1',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tempo_homepage_layout', array(
        'label'   => __('Homepage Layout', 'tempo-news'),
        'section' => 'tempo_homepage',
        'type'    => 'select',
        'choices' => array(
            'layout-1' => __('Layout 1 - Classic', 'tempo-news'),
            'layout-2' => __('Layout 2 - Modern', 'tempo-news'),
            'layout-3' => __('Layout 3 - Magazine', 'tempo-news'),
        ),
    ));
    
    // ============================================
    // DARK MODE SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_dark_mode', array(
        'title'    => __('Dark Mode Settings', 'tempo-news'),
        'priority' => 37,
    ));
    
    // Default Dark Mode
    $wp_customize->add_setting('tempo_dark_mode_default', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('tempo_dark_mode_default', array(
        'label'   => __('Enable Dark Mode by Default', 'tempo-news'),
        'section' => 'tempo_dark_mode',
        'type'    => 'checkbox',
    ));
    
    // ============================================
    // ADVERTISEMENT SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_ads', array(
        'title'    => __('Advertisement Settings', 'tempo-news'),
        'priority' => 38,
    ));
    
    // Parallax Ad Image
    $wp_customize->add_setting('tempo_parallax_ad_image', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tempo_parallax_ad_image', array(
        'label'   => __('Parallax Ad Background Image', 'tempo-news'),
        'section' => 'tempo_ads',
    )));
    
    // Parallax Ad Content
    $wp_customize->add_setting('tempo_parallax_ad_content', array(
        'default'           => '<h3>Special Advertisement</h3>',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('tempo_parallax_ad_content', array(
        'label'   => __('Parallax Ad Content (HTML)', 'tempo-news'),
        'section' => 'tempo_ads',
        'type'    => 'textarea',
    ));
    
    // Header Ad Code
    $wp_customize->add_setting('tempo_header_ad_code', array(
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('tempo_header_ad_code', array(
        'label'   => __('Header Ad Code (728x90)', 'tempo-news'),
        'section' => 'tempo_ads',
        'type'    => 'textarea',
    ));
    
    // Sidebar Ad Code
    $wp_customize->add_setting('tempo_sidebar_ad_code', array(
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('tempo_sidebar_ad_code', array(
        'label'   => __('Sidebar Ad Code (300x250)', 'tempo-news'),
        'section' => 'tempo_ads',
        'type'    => 'textarea',
    ));
    
    // Footer Ad Code
    $wp_customize->add_setting('tempo_footer_ad_code', array(
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('tempo_footer_ad_code', array(
        'label'   => __('Footer Ad Code (970x90)', 'tempo-news'),
        'section' => 'tempo_ads',
        'type'    => 'textarea',
    ));
    
    // ============================================
    // SOCIAL MEDIA SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_social', array(
        'title'    => __('Social Media', 'tempo-news'),
        'priority' => 39,
    ));
    
    // Facebook
    $wp_customize->add_setting('tempo_facebook_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('tempo_facebook_url', array(
        'label'   => __('Facebook URL', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'url',
    ));
    
    // Twitter
    $wp_customize->add_setting('tempo_twitter_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('tempo_twitter_url', array(
        'label'   => __('Twitter URL', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'url',
    ));
    
    // Instagram
    $wp_customize->add_setting('tempo_instagram_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('tempo_instagram_url', array(
        'label'   => __('Instagram URL', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'url',
    ));
    
    // YouTube
    $wp_customize->add_setting('tempo_youtube_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('tempo_youtube_url', array(
        'label'   => __('YouTube URL', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'url',
    ));
    
    // WhatsApp
    $wp_customize->add_setting('tempo_whatsapp_number', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tempo_whatsapp_number', array(
        'label'   => __('WhatsApp Number', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'text',
    ));
    
    // TikTok
    $wp_customize->add_setting('tempo_tiktok_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('tempo_tiktok_url', array(
        'label'   => __('TikTok URL', 'tempo-news'),
        'section' => 'tempo_social',
        'type'    => 'url',
    ));
    
    // ============================================
    // BREAKING NEWS SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_breaking_news', array(
        'title'    => __('Breaking News Settings', 'tempo-news'),
        'priority' => 40,
    ));
    
    // Enable Breaking News
    $wp_customize->add_setting('tempo_enable_breaking_news', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('tempo_enable_breaking_news', array(
        'label'   => __('Enable Breaking News Bar', 'tempo-news'),
        'section' => 'tempo_breaking_news',
        'type'    => 'checkbox',
    ));
    
    // Breaking News Category
    $wp_customize->add_setting('tempo_breaking_news_category', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('tempo_breaking_news_category', array(
        'label'   => __('Breaking News Category', 'tempo-news'),
        'section' => 'tempo_breaking_news',
        'type'    => 'dropdown_categories',
    ));
    
    // ============================================
    // FOOTER SETTINGS
    // ============================================
    $wp_customize->add_section('tempo_footer', array(
        'title'    => __('Footer Settings', 'tempo-news'),
        'priority' => 41,
    ));
    
    // Footer Copyright Text
    $wp_customize->add_setting('tempo_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('tempo_footer_copyright', array(
        'label'   => __('Footer Copyright Text', 'tempo-news'),
        'section' => 'tempo_footer',
        'type'    => 'textarea',
    ));
}
add_action('customize_register', 'tempo_news_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function tempo_news_customize_preview_js() {
    wp_enqueue_script('tempo-news-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), TEMPO_NEWS_VERSION, true);
}
add_action('customize_preview_init', 'tempo_news_customize_preview_js');
