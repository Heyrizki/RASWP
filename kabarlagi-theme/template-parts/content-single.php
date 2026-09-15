<?php
/**
 * Template part for displaying single posts
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?> data-post-id="<?php the_ID(); ?>">
    
    <header class="post-header">
        <?php kabarlagi_breadcrumb(); ?>
        
        <h1 class="post-title"><?php the_title(); ?></h1>
        
        <div class="post-meta-single">
            <span class="post-author">
                <?php _e( 'By', 'kabarlagi' ); ?> <?php the_author(); ?>
            </span>
            <span class="post-date">
                <?php echo get_the_date(); ?>
            </span>
            <span class="post-views">
                👁 <?php echo kabarlagi_post_views(); ?> <?php _e( 'views', 'kabarlagi' ); ?>
            </span>
            <span class="reading-time">
                ⏱ <?php echo kabarlagi_reading_time(); ?>
            </span>
            <?php if ( has_category() ) : ?>
                <span class="post-category">
                    <?php the_category( ', ' ); ?>
                </span>
            <?php endif; ?>
        </div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-featured-image">
            <?php the_post_thumbnail( 'kabarlagi-large' ); ?>
            <?php 
            $caption = get_post( get_post_thumbnail_id() )->post_excerpt;
            if ( $caption ) :
            ?>
                <p class="image-caption"><?php echo esc_html( $caption ); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <?php
        // Insert inline related posts after first 2 paragraphs
        $content = get_the_content();
        $paragraphs = explode( '</p>', $content );
        $insert_after = 2;
        
        foreach ( $paragraphs as $index => $paragraph ) {
            echo $paragraph . '</p>';
            
            if ( $index == $insert_after ) {
                kabarlagi_inline_related_posts( 3 );
            }
        }
        
        // Parallax Ad in content
        $parallax_ad = get_theme_mod( 'parallax_ad_image' );
        if ( $parallax_ad ) :
        ?>
        <div class="parallax-ad-container" style="background-image: url('<?php echo esc_url( $parallax_ad ); ?>');">
            <div class="parallax-ad-content">
                <?php 
                $parallax_ad_url = get_theme_mod( 'parallax_ad_url', '#' );
                $parallax_ad_text = get_theme_mod( 'parallax_ad_text', 'Advertisement' );
                ?>
                <a href="<?php echo esc_url( $parallax_ad_url ); ?>" target="_blank">
                    <img src="<?php echo esc_url( get_theme_mod( 'parallax_ad_banner', '' ) ); ?>" alt="<?php echo esc_attr( $parallax_ad_text ); ?>" />
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php the_content(); ?>
        
        <?php
        // Post pagination for multi-page articles
        wp_link_pages( array(
            'before'      => '<div class="post-pagination"><span class="pagination-title">' . __( 'Pages:', 'kabarlagi' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span class="page-link">',
            'link_after'  => '</span>',
        ) );
        ?>
    </div>

    <!-- Social Share -->
    <?php kabarlagi_social_share(); ?>

    <!-- Tags -->
    <?php if ( has_tag() ) : ?>
        <div class="post-tags">
            <span class="tags-label"><?php _e( 'Tags:', 'kabarlagi' ); ?></span>
            <?php the_tags( '', ', ', '' ); ?>
        </div>
    <?php endif; ?>

    <!-- Author Box -->
    <div class="author-box">
        <div class="author-avatar">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
        </div>
        <div class="author-info">
            <h4 class="author-name"><?php the_author(); ?></h4>
            <p class="author-bio"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
        </div>
    </div>

    <!-- Related Posts at Bottom -->
    <div class="related-posts-section">
        <h3 class="section-title"><?php _e( 'Related Articles', 'kabarlagi' ); ?></h3>
        <?php
        $post_id = get_the_ID();
        $categories = wp_get_post_categories( $post_id );
        
        $related_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => array( $post_id ),
            'category__in'   => $categories,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        $related_query = new WP_Query( $related_args );
        
        if ( $related_query->have_posts() ) :
        ?>
        <div class="post-grid">
            <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'grid' ); ?>
            <?php endwhile; ?>
        </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </div>

    <!-- Comments -->
    <?php
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>

</article>
