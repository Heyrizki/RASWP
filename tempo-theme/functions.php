<?php
/**
 * Tempo News Theme Functions
 *
 * @package Tempo_News
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('TEMPO_NEWS_VERSION', '1.0.0');
define('TEMPO_NEWS_DIR', get_template_directory());
define('TEMPO_NEWS_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function tempo_news_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 675, true);
    add_image_size('tempo-headline', 800, 450, true);
    add_image_size('tempo-thumbnail', 400, 250, true);
    add_image_size('tempo-related', 300, 180, true);

    // Register nav menus (9 positions)
    register_nav_menus(array(
        'primary'      => __('Primary Menu', 'tempo-news'),
        'secondary'    => __('Secondary Menu', 'tempo-news'),
        'top-bar'      => __('Top Bar Menu', 'tempo-news'),
        'footer'       => __('Footer Menu', 'tempo-news'),
        'mobile'       => __('Mobile Menu', 'tempo-news'),
        'sidebar'      => __('Sidebar Menu', 'tempo-news'),
        'featured'     => __('Featured Menu', 'tempo-news'),
        'category'     => __('Category Menu', 'tempo-news'),
        'social'       => __('Social Menu', 'tempo-news'),
    ));

    // Switch default core markup for various elements to HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 120,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for custom background
    add_theme_support('custom-background');

    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-image'          => '',
        'default-text-color'     => '000000',
        'width'                  => 1920,
        'height'                 => 400,
        'flex-width'             => true,
        'flex-height'            => true,
    ));

    // Add support for AMP
    add_theme_support('amp');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for wide and full alignment
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for custom color scheme
    add_theme_support('custom-colors');

    // Add support for dark mode
    add_theme_support('dark-mode');

    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'video',
        'audio',
    ));

    // Load text domain
    load_theme_textdomain('tempo-news', TEMPO_NEWS_DIR . '/languages');
}
add_action('after_setup_theme', 'tempo_news_setup');

/**
 * Enqueue scripts and styles
 */
function tempo_news_scripts() {
    // Main stylesheet
    wp_enqueue_style('tempo-news-style', get_stylesheet_uri(), array(), TEMPO_NEWS_VERSION);

    // Custom CSS from options
    $custom_css = tempo_news_get_custom_css();
    if ($custom_css) {
        wp_add_inline_style('tempo-news-style', $custom_css);
    }

    // Main JavaScript
    wp_enqueue_script('tempo-news-main', TEMPO_NEWS_URI . '/js/main.js', array('jquery'), TEMPO_NEWS_VERSION, true);

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize script with AJAX URL
    wp_localize_script('tempo-news-main', 'tempoNews', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('tempo_news_nonce'),
        'i18n'    => array(
            'loading'     => __('Loading...', 'tempo-news'),
            'error'       => __('Error', 'tempo-news'),
            'newComment'  => __('New comment on', 'tempo-news'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'tempo_news_scripts');

/**
 * Register widget areas
 */
function tempo_news_widgets_init() {
    // Main Sidebar
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'tempo-news'),
        'id'            => 'sidebar-main',
        'description'   => __('Add widgets here to appear in your sidebar.', 'tempo-news'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Header Widget Area
    register_sidebar(array(
        'name'          => __('Header Widget', 'tempo-news'),
        'id'            => 'header-widget',
        'description'   => __('Add widgets here to appear in your header.', 'tempo-news'),
        'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    // Footer Widget Areas (4 columns)
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Column %d', 'tempo-news'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Widgets for footer column %d.', 'tempo-news'), $i),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }

    // Homepage Widget Areas
    register_sidebar(array(
        'name'          => __('Homepage Top', 'tempo-news'),
        'id'            => 'homepage-top',
        'description'   => __('Widgets for homepage top section.', 'tempo-news'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Homepage Middle', 'tempo-news'),
        'id'            => 'homepage-middle',
        'description'   => __('Widgets for homepage middle section.', 'tempo-news'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Homepage Bottom', 'tempo-news'),
        'id'            => 'homepage-bottom',
        'description'   => __('Widgets for homepage bottom section.', 'tempo-news'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Ad Widget Areas
    register_sidebar(array(
        'name'          => __('Ad Slot - Header', 'tempo-news'),
        'id'            => 'ad-header',
        'description'   => __('728x90 ad slot in header.', 'tempo-news'),
        'before_widget' => '<div id="%1$s" class="ad-slot ad-header %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));

    register_sidebar(array(
        'name'          => __('Ad Slot - Sidebar', 'tempo-news'),
        'id'            => 'ad-sidebar',
        'description'   => __('300x250 ad slot in sidebar.', 'tempo-news'),
        'before_widget' => '<div id="%1$s" class="ad-slot ad-sidebar %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));

    register_sidebar(array(
        'name'          => __('Ad Slot - In Article', 'tempo-news'),
        'id'            => 'ad-in-article',
        'description'   => __('Ad slot inside article content.', 'tempo-news'),
        'before_widget' => '<div id="%1$s" class="ad-slot ad-infeed %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));

    register_sidebar(array(
        'name'          => __('Ad Slot - Footer', 'tempo-news'),
        'id'            => 'ad-footer',
        'description'   => __('970x90 ad slot in footer.', 'tempo-news'),
        'before_widget' => '<div id="%1$s" class="ad-slot ad-footer %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));
}
add_action('widgets_init', 'tempo_news_widgets_init');

/**
 * Custom template tags
 */
require TEMPO_NEWS_DIR . '/inc/template-tags.php';

/**
 * Customizer additions
 */
require TEMPO_NEWS_DIR . '/inc/customizer.php';

/**
 * Widget classes
 */
require TEMPO_NEWS_DIR . '/inc/widgets.php';

/**
 * Template functions
 */
require TEMPO_NEWS_DIR . '/inc/template-functions.php';

/**
 * Admin customization
 */
if (is_admin()) {
    require TEMPO_NEWS_DIR . '/inc/admin.php';
}

/**
 * AMP Support
 */
require TEMPO_NEWS_DIR . '/inc/amp.php';

/**
 * Get custom CSS from theme options
 */
function tempo_news_get_custom_css() {
    $css = '';
    
    // Primary color
    $primary_color = get_theme_mod('tempo_primary_color', '#c90f0f');
    if ($primary_color !== '#c90f0f') {
        $css .= ':root { --primary-color: ' . esc_attr($primary_color) . '; }';
    }
    
    // Font settings
    $font_heading = get_theme_mod('tempo_font_heading', 'Georgia');
    $font_body = get_theme_mod('tempo_font_body', 'Arial');
    
    if ($font_heading !== 'Georgia') {
        $css .= ':root { --font-heading: "' . esc_attr($font_heading) . '", serif; }';
    }
    
    if ($font_body !== 'Arial') {
        $css .= ':root { --font-body: "' . esc_attr($font_body) . '", sans-serif; }';
    }
    
    return $css;
}

/**
 * Enable SVG upload
 */
function tempo_news_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'tempo_news_mime_types');

/**
 * Custom excerpt length
 */
function tempo_news_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'tempo_news_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function tempo_news_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'tempo_news_excerpt_more');

/**
 * Add body classes
 */
function tempo_news_body_classes($classes) {
    // Add class for dark mode
    if (get_theme_mod('tempo_dark_mode_default', false)) {
        $classes[] = 'dark-mode';
    }
    
    // Add class for homepage layout
    $layout = get_theme_mod('tempo_homepage_layout', 'layout-1');
    $classes[] = 'homepage-' . sanitize_html_class($layout);
    
    return $classes;
}
add_filter('body_class', 'tempo_news_body_classes');

/**
 * Preload fonts
 */
function tempo_news_preload_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'tempo_news_preload_fonts', 1);

/**
 * Remove unnecessary WordPress features for performance
 */
function tempo_news_cleanup() {
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'tempo_news_cleanup');

/**
 * Set content width
 */
if (!isset($content_width)) {
    $content_width = 800;
}
