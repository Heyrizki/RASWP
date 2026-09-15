<?php
/**
 * The main template file
 */

get_header();

$home_layout = get_theme_mod( 'home_layout', 'layout-1' );
?>

<main id="primary" class="site-main">
    <div class="container">
        
        <?php if ( is_home() && ! is_paged() ) : ?>
            <!-- Headline Section -->
            <section class="headline-section">
                <?php
                $headline_args = array(
                    'posts_per_page' => 4,
                    'meta_key'       => '_thumbnail_id',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                $headline_query = new WP_Query( $headline_args );
                
                if ( $headline_query->have_posts() ) :
                    $headline_query->the_post();
                ?>
                <div class="headline-grid">
                    <div class="headline-main">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'kabarlagi-large' ); ?>
                            <div class="headline-title">
                                <h2><?php the_title(); ?></h2>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            </div>
                        </a>
                    </div>
                    
                    <div class="headline-sub">
                        <?php while ( $headline_query->have_posts() ) : $headline_query->the_post(); ?>
                            <div class="headline-sub-item">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'kabarlagi-small' ); ?>
                                <?php endif; ?>
                                <div class="headline-sub-content">
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <span class="post-time"><?php echo get_the_date(); ?></span>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </section>
            
            <!-- Trending Posts Widget -->
            <section class="trending-section">
                <h3 class="section-title"><?php _e( 'Trending Now', 'kabarlagi' ); ?></h3>
                <?php
                $trending = kabarlagi_trending_posts( 5 );
                if ( $trending->have_posts() ) :
                ?>
                <div class="trending-posts">
                    <?php while ( $trending->have_posts() ) : $trending->the_post(); ?>
                        <div class="trending-post-item">
                            <span class="trend-number"><?php echo $trending->current_post + 1; ?></span>
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="views-count"><?php echo kabarlagi_post_views(); ?> <?php _e( 'views', 'kabarlagi' ); ?></span>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Main Post Grid -->
        <section class="latest-posts">
            <h3 class="section-title"><?php _e( 'Latest News', 'kabarlagi' ); ?></h3>
            
            <div class="post-grid">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', 'grid' );
                    endwhile;
                    
                    // Load More Button
                    kabarlagi_load_more_button();
                    
                else :
                    get_template_part( 'template-parts/content', 'none' );
                endif;
                ?>
            </div>
        </section>

    </div>
</main>

<?php
get_footer();
