<?php
/**
 * Template Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Display post formats
function kabarlagi_post_format_content() {
    $format = get_post_format();
    
    switch ( $format ) {
        case 'video':
            $video_url = get_post_meta( get_the_ID(), '_video_url', true );
            if ( $video_url ) {
                echo '<div class="post-video">';
                echo wp_oembed_get( $video_url );
                echo '</div>';
            }
            break;
            
        case 'gallery':
            $gallery_images = get_post_meta( get_the_ID(), '_gallery_images', true );
            if ( $gallery_images ) {
                echo '<div class="post-gallery">';
                foreach ( $gallery_images as $image_id ) {
                    echo wp_get_attachment_image( $image_id, 'kabarlagi-carousel' );
                }
                echo '</div>';
            }
            break;
            
        default:
            the_content();
            break;
    }
}

// Get related posts
function kabarlagi_get_related_posts( $post_id, $count = 3 ) {
    $categories = wp_get_post_categories( $post_id );
    $tags = wp_get_post_tags( $post_id );
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post__not_in'   => array( $post_id ),
        'no_found_rows'  => true,
    );
    
    // Try categories first
    if ( ! empty( $categories ) ) {
        $args['category__in'] = $categories;
    }
    
    // If no categories, try tags
    if ( empty( $categories ) && ! empty( $tags ) ) {
        $args['tag__in'] = wp_list_pluck( $tags, 'term_id' );
    }
    
    return new WP_Query( $args );
}

// Inline related posts in content
function kabarlagi_inline_related_posts( $content ) {
    if ( ! is_single() ) {
        return $content;
    }
    
    if ( ! get_theme_mod( 'kabarlagi_show_related_posts', true ) ) {
        return $content;
    }
    
    global $post;
    $inline_count = get_theme_mod( 'kabarlagi_inline_related_count', 2 );
    
    // Find paragraph count to insert after
    $paragraphs = substr_count( $content, '</p>' );
    $insert_after = floor( $paragraphs / 2 );
    
    if ( $insert_after < 1 ) {
        return $content;
    }
    
    $related_query = kabarlagi_get_related_posts( $post->ID, $inline_count );
    
    if ( $related_query->have_posts() ) {
        $related_html = '<div class="inline-related-post">';
        $related_html .= '<h4>' . __( 'Baca Juga:', 'kabarlagi' ) . '</h4>';
        $related_html .= '<ul>';
        
        while ( $related_query->have_posts() ) {
            $related_query->the_post();
            $related_html .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        
        $related_html .= '</ul></div>';
        
        // Insert after middle paragraph
        $paragraphs_array = explode( '</p>', $content );
        array_splice( $paragraphs_array, $insert_after, 0, array( $related_html ) );
        $content = implode( '</p>', $paragraphs_array );
        
        wp_reset_postdata();
    }
    
    return $content;
}
add_filter( 'the_content', 'kabarlagi_inline_related_posts' );

// Social share buttons
function kabarlagi_share_buttons() {
    if ( ! get_theme_mod( 'kabarlagi_show_share_buttons', true ) ) {
        return;
    }
    
    global $post;
    $url = urlencode( get_permalink( $post->ID ) );
    $title = urlencode( get_the_title( $post->ID ) );
    ?>
    <div class="share-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" 
           target="_blank" rel="noopener" class="share-btn share-btn-facebook" 
           aria-label="Share on Facebook">
            <i class="kl-icon-facebook"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" 
           target="_blank" rel="noopener" class="share-btn share-btn-twitter" 
           aria-label="Share on Twitter">
            <i class="kl-icon-twitter"></i>
        </a>
        <a href="https://wa.me/?text=<?php echo $title; ?>%20<?php echo $url; ?>" 
           target="_blank" rel="noopener" class="share-btn share-btn-whatsapp" 
           aria-label="Share on WhatsApp">
            <i class="kl-icon-whatsapp"></i>
        </a>
        <a href="https://t.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" 
           target="_blank" rel="noopener" class="share-btn share-btn-telegram" 
           aria-label="Share on Telegram">
            <i class="kl-icon-telegram"></i>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" 
           target="_blank" rel="noopener" class="share-btn share-btn-linkedin" 
           aria-label="Share on LinkedIn">
            <i class="kl-icon-linkedin"></i>
        </a>
    </div>
    <?php
}

// Parallax ad in content
function kabarlagi_parallax_ad_in_content( $content ) {
    if ( ! is_single() ) {
        return $content;
    }
    
    $parallax_ad = get_theme_mod( 'kabarlagi_parallax_ad' );
    $parallax_ad_link = get_theme_mod( 'kabarlagi_parallax_ad_link' );
    
    if ( ! $parallax_ad ) {
        return $content;
    }
    
    $ad_html = '<div class="parallax-ad">';
    $ad_html .= '<div class="parallax-ad-inner" style="background-image: url(' . esc_url( $parallax_ad ) . ');">';
    
    if ( $parallax_ad_link ) {
        $ad_html .= '<a href="' . esc_url( $parallax_ad_link ) . '" target="_blank" rel="noopener sponsored">';
        $ad_html .= '<span class="sr-only">Advertisement</span>';
        $ad_html .= '</a>';
    }
    
    $ad_html .= '</div></div>';
    
    // Insert after second paragraph
    $paragraphs = substr_count( $content, '</p>' );
    if ( $paragraphs >= 2 ) {
        $paragraphs_array = explode( '</p>', $content );
        array_splice( $paragraphs_array, 2, 0, array( $ad_html ) );
        $content = implode( '</p>', $paragraphs_array );
    }
    
    return $content;
}
add_filter( 'the_content', 'kabarlagi_parallax_ad_in_content' );

// Post pagination for multi-page articles
function kabarlagi_post_pagination() {
    wp_link_pages( array(
        'before'      => '<div class="post-pagination"><span class="pagination-title">' . __( 'Pages:', 'kabarlagi' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span class="page-number">',
        'link_after'  => '</span>',
        'separator'   => ' ',
    ) );
}

// Comment notification function
function kabarlagi_comment_notification( $comment_id ) {
    if ( ! get_theme_mod( 'kabarlagi_push_notification_enable', false ) ) {
        return;
    }
    
    $comment = get_comment( $comment_id );
    $post_id = $comment->comment_post_ID;
    
    // Send browser push notification
    // This would integrate with a push notification service
    do_action( 'kabarlagi_send_push_notification', $comment, $post_id );
}
add_action( 'wp_insert_comment', 'kabarlagi_comment_notification', 10, 1 );

// AJAX Load More handler
function kabarlagi_load_more_posts() {
    check_ajax_referer( 'kabarlagi_loadmore_nonce', 'nonce' );
    
    $paged = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $category = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $style = isset( $_POST['style'] ) ? sanitize_text_field( $_POST['style'] ) : 'grid';
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'no_found_rows'  => true,
    );
    
    if ( $category ) {
        $args['cat'] = $category;
    }
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/content', $style );
        }
    }
    
    wp_reset_postdata();
    die();
}
add_action( 'wp_ajax_kabarlagi_load_more', 'kabarlagi_load_more_posts' );
add_action( 'wp_ajax_nopriv_kabarlagi_load_more', 'kabarlagi_load_more_posts' );

// Custom excerpt for homepage
function kabarlagi_custom_excerpt( $length ) {
    if ( is_home() || is_front_page() ) {
        return 20;
    }
    return $length;
}
add_filter( 'excerpt_length', 'kabarlagi_custom_excerpt', 999 );

// Enable AMP support
function kabarlagi_amp_support() {
    // Add AMP boilerplate
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        add_action( 'wp_head', 'kabarlagi_amp_boilerplate', 1 );
    }
}
add_action( 'wp', 'kabarlagi_amp_support' );

function kabarlagi_amp_boilerplate() {
    ?>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <?php
}
