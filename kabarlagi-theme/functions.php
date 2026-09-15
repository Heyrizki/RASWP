<?php
/**
 * Theme Name: Kabar Lagi
 * Theme URI: https://kabarlagi.com
 * Author: Kabar Lagi Team
 * Author URI: https://kabarlagi.com
 * Description: Tema berita modern dengan fitur lengkap: Home Page Builder, Dark Mode, Responsive Design, Custom Widgets, Inline Related Posts, Ads Placement, dan banyak lagi. Mendukung 3 layout homepage, breaking news, floating mobile menu, dan optimasi SEO.
 * Version: 1.0.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kabarlagi
 * Tags: news, blog, dark-mode, responsive, seo-friendly, custom-colors, custom-fonts, adsense, amp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'KABARLAGI_VERSION', '1.0.0' );
define( 'KABARLAGI_DIR', get_template_directory() );
define( 'KABARLAGI_URI', get_template_directory_uri() );

require_once KABARLAGI_DIR . '/inc/core-functions.php';
require_once KABARLAGI_DIR . '/inc/customizer.php';
require_once KABARLAGI_DIR . '/inc/custom-post-types.php';
require_once KABARLAGI_DIR . '/inc/widgets.php';
require_once KABARLAGI_DIR . '/inc/template-functions.php';
require_once KABARLAGI_DIR . '/inc/ads-placement.php';
require_once KABARLAGI_DIR . '/inc/builder-elements.php';

function kabarlagi_setup() {
    load_theme_textdomain( 'kabarlagi', KABARLAGI_DIR . '/languages' );
    
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    
    add_theme_support( 'post-formats', array(
        'standard',
        'video',
        'gallery',
    ) );
    
    add_theme_support( 'custom-background' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    register_nav_menus( array(
        'primary'   => __( 'Primary Menu', 'kabarlagi' ),
        'secondary' => __( 'Secondary Menu', 'kabarlagi' ),
        'mobile'    => __( 'Mobile Menu', 'kabarlagi' ),
        'footer'    => __( 'Footer Menu', 'kabarlagi' ),
        'topbar'    => __( 'Top Bar Menu', 'kabarlagi' ),
        'sidebar'   => __( 'Sidebar Menu', 'kabarlagi' ),
        'offcanvas' => __( 'Off Canvas Menu', 'kabarlagi' ),
        'mega'      => __( 'Mega Menu', 'kabarlagi' ),
        'breaking'  => __( 'Breaking News Menu', 'kabarlagi' ),
    ) );
    
    set_post_thumbnail_size( 1200, 675, true );
    add_image_size( 'kabarlagi-large', 1200, 675, true );
    add_image_size( 'kabarlagi-medium', 800, 450, true );
    add_image_size( 'kabarlagi-small', 400, 225, true );
    add_image_size( 'kabarlagi-thumb', 150, 150, true );
}
add_action( 'after_setup_theme', 'kabarlagi_setup' );

function kabarlagi_scripts() {
    wp_enqueue_style( 'kabarlagi-style', get_stylesheet_uri(), array(), KABARLAGI_VERSION );
    wp_enqueue_style( 'kabarlagi-responsive', KABARLAGI_URI . '/css/responsive.css', array(), KABARLAGI_VERSION );
    wp_enqueue_style( 'kabarlagi-dark', KABARLAGI_URI . '/css/dark-mode.css', array(), KABARLAGI_VERSION );
    
    wp_enqueue_script( 'kabarlagi-main', KABARLAGI_URI . '/js/main.js', array('jquery'), KABARLAGI_VERSION, true );
    wp_enqueue_script( 'kabarlagi-builder', KABARLAGI_URI . '/js/builder.js', array('jquery'), KABARLAGI_VERSION, true );
    wp_enqueue_script( 'kabarlagi-loadmore', KABARLAGI_URI . '/js/loadmore.js', array('jquery'), KABARLAGI_VERSION, true );
    
    wp_localize_script( 'kabarlagi-main', 'kabarlagi_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'kabarlagi_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'kabarlagi_scripts' );

function kabarlagi_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'kabarlagi' ),
        'id'            => 'sidebar-main',
        'description'   => __( 'Add widgets here for the main sidebar.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 1', 'kabarlagi' ),
        'id'            => 'footer-1',
        'description'   => __( 'First footer widget area.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 2', 'kabarlagi' ),
        'id'            => 'footer-2',
        'description'   => __( 'Second footer widget area.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer Widget 3', 'kabarlagi' ),
        'id'            => 'footer-3',
        'description'   => __( 'Third footer widget area.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Header Ad Widget', 'kabarlagi' ),
        'id'            => 'header-ad',
        'description'   => __( 'Widget area for header advertisement.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Left Floating Ad', 'kabarlagi' ),
        'id'            => 'floating-left',
        'description'   => __( 'Left side floating advertisement.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="floating-ad left %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Right Floating Ad', 'kabarlagi' ),
        'id'            => 'floating-right',
        'description'   => __( 'Right side floating advertisement.', 'kabarlagi' ),
        'before_widget' => '<div id="%1$s" class="floating-ad right %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'kabarlagi_widgets_init' );

function kabarlagi_customizer_controls() {
    require_once KABARLAGI_DIR . '/customizer/class-kabarlagi-custom-controls.php';
}
add_action( 'customize_register', 'kabarlagi_customizer_controls' );

function kabarlagi_login_customization() {
    echo '<style type="text/css">
        body.login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .login h1 a {
            background-image: url("' . KABARLAGI_URI . '/images/login-logo.png");
            background-size: contain;
            background-repeat: no-repeat;
            width: 320px;
            height: 80px;
            margin-bottom: 20px;
        }
        .login form {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
        }
        .login input[type=text],
        .login input[type=password] {
            border-radius: 5px;
            border: 2px solid #e0e0e0;
            padding: 12px;
        }
        .login input[type=submit] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .login input[type=submit]:hover {
            opacity: 0.9;
        }
        .login .button-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
        }
        .message, .success, .error {
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>';
}
add_action( 'login_enqueue_scripts', 'kabarlagi_login_customization' );

function kabarlagi_relative_time( $time_string ) {
    $time = get_the_time( 'U' );
    $diff = current_time( 'timestamp' ) - $time;
    
    if ( $diff < DAY_IN_SECONDS ) {
        if ( $diff < HOUR_IN_SECONDS ) {
            $mins = round( $diff / MINUTE_IN_SECONDS );
            return sprintf( _n( '%d menit yang lalu', '%d menit yang lalu', $mins, 'kabarlagi' ), $mins );
        } else {
            $hours = round( $diff / HOUR_IN_SECONDS );
            return sprintf( _n( '%d jam yang lalu', '%d jam yang lalu', $hours, 'kabarlagi' ), $hours );
        }
    }
    
    return $time_string;
}
add_filter( 'get_the_date', 'kabarlagi_relative_time' );
add_filter( 'get_the_modified_date', 'kabarlagi_relative_time' );

function kabarlagi_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );
    
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    return sprintf( _n( '%d menit baca', '%d menit baca', $reading_time, 'kabarlagi' ), $reading_time );
}

function kabarlagi_post_views() {
    $post_id = get_the_ID();
    $views = get_post_meta( $post_id, 'kabarlagi_post_views', true );
    
    if ( ! $views ) {
        $views = 0;
    }
    
    return number_format( $views );
}

function kabarlagi_update_post_views() {
    if ( is_single() ) {
        $post_id = get_the_ID();
        $views = get_post_meta( $post_id, 'kabarlagi_post_views', true );
        
        if ( ! $views ) {
            $views = 0;
        }
        
        update_post_meta( $post_id, 'kabarlagi_post_views', $views + 1 );
    }
}
add_action( 'wp_head', 'kabarlagi_update_post_views' );

function kabarlagi_inline_related_posts( $count = 3 ) {
    $post_id = get_the_ID();
    $categories = wp_get_post_categories( $post_id );
    $tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post__not_in'   => array( $post_id ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    if ( ! empty( $categories ) ) {
        $args['category__in'] = $categories;
    } elseif ( ! empty( $tags ) ) {
        $args['tag__in'] = $tags;
    } else {
        return;
    }
    
    $related_query = new WP_Query( $args );
    
    if ( $related_query->have_posts() ) {
        echo '<div class="inline-related-posts">';
        echo '<h3 class="related-title">' . __( 'Baca Juga', 'kabarlagi' ) . '</h3>';
        echo '<div class="related-posts-grid">';
        
        while ( $related_query->have_posts() ) {
            $related_query->the_post();
            ?>
            <div class="related-post-item">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'kabarlagi-thumb' ); ?>
                    </a>
                <?php endif; ?>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <span class="related-time"><?php echo get_the_date(); ?></span>
            </div>
            <?php
        }
        
        echo '</div></div>';
        wp_reset_postdata();
    }
}

function kabarlagi_trending_posts( $count = 5 ) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'meta_key'       => 'kabarlagi_post_views',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );
    
    return new WP_Query( $args );
}

function kabarlagi_breaking_news() {
    $breaking_menu = get_theme_mod( 'breaking_news_menu', 'breaking' );
    $menu_items = wp_get_nav_menu_items( $breaking_menu );
    
    if ( $menu_items ) {
        echo '<div class="breaking-news-bar">';
        echo '<div class="breaking-label">' . __( 'BREAKING NEWS', 'kabarlagi' ) . '</div>';
        echo '<div class="breaking-marquee">';
        echo '<div class="marquee-content">';
        
        foreach ( $menu_items as $item ) {
            echo '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
        }
        
        echo '</div></div></div>';
    }
}

function kabarlagi_social_share() {
    $url = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    
    echo '<div class="social-share">';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" class="share-facebook">Facebook</a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" class="share-twitter">Twitter</a>';
    echo '<a href="https://wa.me/?text=' . $title . '%20' . $url . '" target="_blank" class="share-whatsapp">WhatsApp</a>';
    echo '<a href="https://t.me/share/url?url=' . $url . '&text=' . $title . '" target="_blank" class="share-telegram">Telegram</a>';
    echo '</div>';
}

function kabarlagi_breadcrumb() {
    echo '<nav class="breadcrumb">';
    echo '<a href="' . home_url() . '">' . __( 'Home', 'kabarlagi' ) . '</a>';
    
    if ( is_category() || is_single() ) {
        echo ' <span class="separator">/</span> ';
        the_category( ' <span class="separator">/</span> ' );
    }
    
    if ( is_single() ) {
        echo ' <span class="separator">/</span> ';
        the_title();
    }
    
    echo '</nav>';
}

function kabarlagi_dark_mode_toggle() {
    echo '<button class="dark-mode-toggle" aria-label="Toggle Dark Mode">';
    echo '<span class="icon-sun">☀️</span>';
    echo '<span class="icon-moon">🌙</span>';
    echo '</button>';
}

function kabarlagi_load_more_button() {
    echo '<div class="load-more-container">';
    echo '<button class="load-more-btn" data-nonce="' . wp_create_nonce( 'kabarlagi_loadmore' ) . '">';
    echo __( 'Load More Articles', 'kabarlagi' );
    echo '</button>';
    echo '</div>';
}

function kabarlagi_home_builder_element( $element_type, $args = array() ) {
    ob_start();
    
    switch ( $element_type ) {
        case 'grid':
            get_template_part( 'template-parts/content', 'grid', $args );
            break;
        case 'carousel':
            get_template_part( 'template-parts/content', 'carousel', $args );
            break;
        case 'list':
            get_template_part( 'template-parts/content', 'list', $args );
            break;
        case 'hero':
            get_template_part( 'template-parts/content', 'hero', $args );
            break;
        case 'custom':
            get_template_part( 'template-parts/content', 'custom', $args );
            break;
    }
    
    return ob_get_clean();
}

function kabarlagi_amp_compatibility() {
    add_theme_support( 'amp' );
}
add_action( 'after_setup_theme', 'kabarlagi_amp_compatibility' );

function kabarlagi_push_notification() {
    if ( is_singular() && comments_open() ) {
        echo '<script>
        if ("Notification" in window && Notification.permission === "granted") {
            console.log("Push notifications enabled");
        } else if ("Notification" in window && Notification.permission !== "denied") {
            Notification.requestPermission().then(function(permission) {
                if (permission === "granted") {
                    console.log("Push notifications enabled");
                }
            });
        }
        </script>';
    }
}
add_action( 'wp_footer', 'kabarlagi_push_notification' );

function kabarlagi_comment_notification( $comment_id ) {
    $comment = get_comment( $comment_id );
    $post_id = $comment->comment_post_ID;
    
    if ( function_exists( 'wp_send_json_success' ) ) {
        wp_send_json_success( array(
            'message' => __( 'New comment posted!', 'kabarlagi' ),
            'comment' => $comment->comment_content,
        ) );
    }
}
add_action( 'wp_insert_comment', 'kabarlagi_comment_notification' );

function kabarlagi_ajax_load_more() {
    check_ajax_referer( 'kabarlagi_loadmore', 'nonce' );
    
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    if ( ! empty( $category ) ) {
        $args['category_name'] = $category;
    }
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/content', get_post_format() );
        }
        wp_reset_postdata();
    }
    
    wp_die();
}
add_action( 'wp_ajax_kabarlagi_load_more', 'kabarlagi_ajax_load_more' );
add_action( 'wp_ajax_nopriv_kabarlagi_load_more', 'kabarlagi_ajax_load_more' );
