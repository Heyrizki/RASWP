<?php
/**
 * Template part for displaying posts in grid layout
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
            <?php the_post_thumbnail( 'kabarlagi-medium' ); ?>
        </a>
    <?php endif; ?>
    
    <div class="post-card-content">
        <div class="post-card-category">
            <?php 
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . 
                     esc_html( $categories[0]->name ) . '</a>';
            }
            ?>
        </div>
        
        <h3 class="post-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <div class="post-card-meta">
            <span class="post-time"><?php echo get_the_date(); ?></span>
            <span class="post-views">👁 <?php echo kabarlagi_post_views(); ?></span>
        </div>
    </div>
</article>
