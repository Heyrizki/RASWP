<?php
/**
 * Theme Setup Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Content width
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

// Register custom image sizes
function kabarlagi_custom_image_sizes() {
    add_image_size( 'kabarlagi-hero', 1200, 600, true );
    add_image_size( 'kabarlagi-grid-large', 800, 500, true );
    add_image_size( 'kabarlagi-grid-medium', 600, 400, true );
    add_image_size( 'kabarlagi-grid-small', 400, 300, true );
    add_image_size( 'kabarlagi-carousel', 800, 500, true );
    add_image_size( 'kabarlagi-thumb', 300, 200, true );
    add_image_size( 'kabarlagi-list', 200, 150, true );
}
add_action( 'after_setup_theme', 'kabarlagi_custom_image_sizes' );

// Custom excerpt length
function kabarlagi_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    }
    return 25;
}
add_filter( 'excerpt_length', 'kabarlagi_excerpt_length', 999 );

// Custom excerpt more
function kabarlagi_excerpt_more( $more ) {
    if ( is_admin() ) {
        return $more;
    }
    return '...';
}
add_filter( 'excerpt_more', 'kabarlagi_excerpt_more' );

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Add body classes
function kabarlagi_body_classes( $classes ) {
    // Dark mode class
    if ( get_theme_mod( 'kabarlagi_dark_mode', 'auto' ) === 'dark' ) {
        $classes[] = 'dark-mode';
    } elseif ( get_theme_mod( 'kabarlagi_dark_mode', 'auto' ) === 'auto' ) {
        $classes[] = 'auto-mode';
    }
    
    // Layout class
    $layout = get_theme_mod( 'kabarlagi_site_layout', 'full-width' );
    $classes[] = 'layout-' . sanitize_html_class( $layout );
    
    return $classes;
}
add_filter( 'body_class', 'kabarlagi_body_classes' );

// Pagination
function kabarlagi_pagination() {
    global $wp_query;
    
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }
    
    $big = 999999999;
    
    echo paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ) );
}

// Post views counter
function kabarlagi_get_post_views( $post_id ) {
    $count_key = 'post_views_count';
    $count = get_post_meta( $post_id, $count_key, true );
    if ( $count == '' ) {
        delete_post_meta( $post_id, $count_key );
        add_post_meta( $post_id, $count_key, '0' );
        return '0';
    }
    return number_format_i18n( $count );
}

function kabarlagi_set_post_views( $post_id ) {
    $count_key = 'post_views_count';
    $count = get_post_meta( $post_id, $count_key, true );
    if ( $count == '' ) {
        $count = 0;
        delete_post_meta( $post_id, $count_key );
        add_post_meta( $post_id, $count_key, '0' );
    } else {
        $count++;
        update_post_meta( $post_id, $count_key, $count );
    }
}

// Track post views
function kabarlagi_track_post_views( $post_id ) {
    if ( ! is_single() ) {
        return;
    }
    if ( empty( $post_id ) ) {
        global $post;
        $post_id = $post->ID;
    }
    kabarlagi_set_post_views( $post_id );
}
add_action( 'wp_head', 'kabarlagi_track_post_views' );

// Reading time estimation
function kabarlagi_reading_time() {
    global $post;
    $content = get_post_field( 'post_content', $post );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_speed = 200; // words per minute
    $reading_time = ceil( $word_count / $reading_speed );
    
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    return sprintf( _n( '%d min read', '%d mins read', $reading_time, 'kabarlagi' ), $reading_time );
}

// Relative time function
function kabarlagi_relative_time( $time ) {
    if ( ! is_singular() ) {
        $ptime = strtotime( $time );
        $etime = time() - $ptime;
        
        if ( $etime < 86400 ) { // Less than 24 hours
            if ( $etime < 60 ) {
                return __( 'Just now', 'kabarlagi' );
            } elseif ( $etime < 3600 ) {
                $minutes = floor( $etime / 60 );
                return sprintf( _n( '%d minute ago', '%d minutes ago', $minutes, 'kabarlagi' ), $minutes );
            } else {
                $hours = floor( $etime / 3600 );
                return sprintf( _n( '%d hour ago', '%d hours ago', $hours, 'kabarlagi' ), $hours );
            }
        }
    }
    
    return get_the_date();
}

// Breadcrumb function
function kabarlagi_breadcrumb() {
    if ( is_front_page() ) {
        return;
    }
    
    echo '<nav class="breadcrumb" aria-label="Breadcrumb">';
    echo '<ul>';
    echo '<li><a href="' . home_url() . '">' . __( 'Home', 'kabarlagi' ) . '</a></li>';
    
    if ( is_category() || is_single() ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            echo '<li><a href="' . get_category_link( $categories[0]->term_id ) . '">' . $categories[0]->name . '</a></li>';
        }
        if ( is_single() ) {
            echo '<li>' . get_the_title() . '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li>' . get_the_title() . '</li>';
    } elseif ( is_tag() ) {
        echo '<li>' . single_tag_title( '', false ) . '</li>';
    } elseif ( is_author() ) {
        echo '<li>' . get_the_author() . '</li>';
    } elseif ( is_year() ) {
        echo '<li>' . get_the_time( 'Y' ) . '</li>';
    } elseif ( is_month() ) {
        echo '<li>' . get_the_time( 'F Y' ) . '</li>';
    } elseif ( is_day() ) {
        echo '<li>' . get_the_time( 'F j, Y' ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li>' . __( 'Search Results for:', 'kabarlagi' ) . ' "' . get_search_query() . '"</li>';
    } elseif ( is_404() ) {
        echo '<li>' . __( 'Page Not Found', 'kabarlagi' ) . '</li>';
    }
    
    echo '</ul>';
    echo '</nav>';
}
