<?php
/**
 * Theme Name: Kabar Lagi
 * Theme URI: https://kabarlagi.com
 * Author: Kabar Lagi Team
 * Author URI: https://kabarlagi.com
 * Description: Tema berita profesional dengan fitur lengkap: Home Builder, Dark Mode, Ads Placement, Inline Related Posts, Responsive Design, dan banyak lagi. Mendukung 9 posisi menu, breaking news, floating mobile menu, dan kustomisasi penuh.
 * Version: 1.0.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kabarlagi
 * Tags: news, blog, magazine, dark-mode, responsive, seo-friendly, adsense-ready
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define theme constants
define( 'KABARLAGI_VERSION', '1.0.0' );
define( 'KABARLAGI_DIR', get_template_directory() );
define( 'KABARLAGI_URI', get_template_directory_uri() );

// Include required files
require_once KABARLAGI_DIR . '/inc/setup.php';
require_once KABARLAGI_DIR . '/inc/customizer.php';
require_once KABARLAGI_DIR . '/inc/widgets.php';
require_once KABARLAGI_DIR . '/inc/template-functions.php';
require_once KABARLAGI_DIR . '/inc/template-tags.php';
require_once KABARLAGI_DIR . '/inc/post-types.php';
require_once KABARLAGI_DIR . '/inc/ads.php';
require_once KABARLAGI_DIR . '/inc/builder.php';

// Custom Login Page
require_once KABARLAGI_DIR . '/inc/login-custom.php';

function kabarlagi_scripts() {
    // Main CSS
    wp_enqueue_style( 'kabarlagi-style', get_stylesheet_uri(), array(), KABARLAGI_VERSION );
    
    // Custom CSS from Customizer
    wp_add_inline_style( 'kabarlagi-style', kabarlagi_custom_css() );
    
    // JavaScript
    wp_enqueue_script( 'kabarlagi-main', KABARLAGI_URI . '/js/main.js', array('jquery'), KABARLAGI_VERSION, true );
    
    // Load More functionality
    wp_enqueue_script( 'kabarlagi-loadmore', KABARLAGI_URI . '/js/loadmore.js', array('jquery'), KABARLAGI_VERSION, true );
    
    // Localize script for AJAX
    wp_localize_script( 'kabarlagi-loadmore', 'kabarlagi_ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'kabarlagi_loadmore_nonce' )
    ));
    
    // Comment notification
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'kabarlagi_scripts' );

// Register Navigation Menus (9 Positions)
function kabarlagi_register_menus() {
    register_nav_menus( array(
        'primary'       => __( 'Primary Menu (Header)', 'kabarlagi' ),
        'secondary'     => __( 'Secondary Menu', 'kabarlagi' ),
        'mobile'        => __( 'Mobile Menu', 'kabarlagi' ),
        'footer'        => __( 'Footer Menu', 'kabarlagi' ),
        'topbar'        => __( 'Top Bar Menu', 'kabarlagi' ),
        'sidebar'       => __( 'Sidebar Menu', 'kabarlagi' ),
        'breaking'      => __( 'Breaking News Menu', 'kabarlagi' ),
        'social'        => __( 'Social Media Links', 'kabarlagi' ),
        'extra'         => __( 'Extra Menu', 'kabarlagi' ),
    ) );
}
add_action( 'after_setup_theme', 'kabarlagi_register_menus' );

// Theme Support
function kabarlagi_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'dark-editor-style' );
    
    // Post Formats
    add_theme_support( 'post-formats', array( 'standard', 'video', 'gallery', 'image', 'audio' ) );
    
    // Custom Logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    
    // Custom Background
    add_theme_support( 'custom-background' );
    
    // Set default image sizes
    add_image_size( 'kabarlagi-hero', 1200, 600, true );
    add_image_size( 'kabarlagi-grid', 600, 400, true );
    add_image_size( 'kabarlagi-thumb', 300, 200, true );
    add_image_size( 'kabarlagi-carousel', 800, 500, true );
}
add_action( 'after_setup_theme', 'kabarlagi_setup' );

// Register Widget Areas
function kabarlagi_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'kabarlagi' ),
        'id'            => 'sidebar-main',
        'description'   => __( 'Widgets in this area will be shown on the main sidebar.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 1', 'kabarlagi' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 2', 'kabarlagi' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 3', 'kabarlagi' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Header Ad Area', 'kabarlagi' ),
        'id'            => 'header-ad',
        'before_widget' => '<div class="header-ad-widget">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Below Header Ad', 'kabarlagi' ),
        'id'            => 'below-header-ad',
        'before_widget' => '<div class="below-header-ad-widget">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Floating Left Ad', 'kabarlagi' ),
        'id'            => 'floating-left-ad',
        'before_widget' => '<div class="floating-ad-left">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Floating Right Ad', 'kabarlagi' ),
        'id'            => 'floating-right-ad',
        'before_widget' => '<div class="floating-ad-right">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Inside Article Parallax Ad', 'kabarlagi' ),
        'id'            => 'parallax-ad',
        'before_widget' => '<div class="parallax-ad-widget">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Before Content Ad', 'kabarlagi' ),
        'id'            => 'before-content-ad',
        'before_widget' => '<div class="before-content-ad">',
        'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'After Content Ad', 'kabarlagi' ),
        'id'            => 'after-content-ad',
        'before_widget' => '<div class="after-content-ad">',
        'after_widget'  => '</div>',
    ) );
}
add_action( 'widgets_init', 'kabarlagi_widgets_init' );
