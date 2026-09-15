<?php
/**
 * The main template file
 *
 * @package Tempo_News
 */

get_header();
?>

<div id="content" class="site-content">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px;">
            
            <!-- Main Content Area -->
            <main id="primary" class="content-area">
                
                <?php if (is_front_page()) : ?>
                    <!-- Homepage Layout Builder -->
                    <div class="homepage-layout">
                        <?php
                        // Get homepage layout from theme options
                        $layout = get_theme_mod('tempo_homepage_layout', 'layout-1');
                        
                        // Load appropriate homepage template
                        get_template_part('template-parts/home/layout', $layout);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (is_home() && !is_front_page()) : ?>
                    <!-- Blog Posts Page -->
                    <div class="posts-listing">
                        <?php if (have_posts()) : ?>
                            <div class="posts-grid">
                                <?php while (have_posts()) : the_post(); ?>
                                    <?php get_template_part('template-parts/content', get_post_type()); ?>
                                <?php endwhile; ?>
                            </div>
                            
                            <!-- Pagination -->
                            <?php tempo_news_pagination(); ?>
                        <?php else : ?>
                            <?php get_template_part('template-parts/content', 'none'); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (is_search()) : ?>
                    <!-- Search Results -->
                    <div class="search-results">
                        <h1 class="page-title">
                            <?php printf(__('Search Results for: %s', 'tempo-news'), '<span>' . get_search_query() . '</span>'); ?>
                        </h1>
                        <?php if (have_posts()) : ?>
                            <div class="posts-grid">
                                <?php while (have_posts()) : the_post(); ?>
                                    <?php get_template_part('template-parts/content', 'search'); ?>
                                <?php endwhile; ?>
                            </div>
                            <?php tempo_news_pagination(); ?>
                        <?php else : ?>
                            <?php get_template_part('template-parts/content', 'none'); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (is_archive()) : ?>
                    <!-- Archive Page -->
                    <div class="archive-page">
                        <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
                        <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
                        
                        <?php if (have_posts()) : ?>
                            <div class="posts-grid">
                                <?php while (have_posts()) : the_post(); ?>
                                    <?php get_template_part('template-parts/content', get_post_type()); ?>
                                <?php endwhile; ?>
                            </div>
                            <?php tempo_news_pagination(); ?>
                        <?php else : ?>
                            <?php get_template_part('template-parts/content', 'none'); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </main>

            <!-- Sidebar -->
            <aside id="secondary" class="sidebar widget-area">
                <?php if (is_active_sidebar('sidebar-main')) : ?>
                    <?php dynamic_sidebar('sidebar-main'); ?>
                <?php else : ?>
                    <!-- Default Widgets -->
                    <section class="widget">
                        <h3 class="widget-title"><?php _e('Trending Posts', 'tempo-news'); ?></h3>
                        <?php
                        $trending_args = array(
                            'posts_per_page' => 5,
                            'meta_key'       => '_post_views',
                            'orderby'        => 'meta_value_num',
                            'order'          => 'DESC',
                        );
                        $trending_query = new WP_Query($trending_args);
                        
                        if ($trending_query->have_posts()) :
                            ?>
                            <ul class="trending-posts">
                                <?php
                                $counter = 1;
                                while ($trending_query->have_posts()) : $trending_query->the_post();
                                    ?>
                                    <li class="trending-post-item">
                                        <span class="trending-post-number"><?php echo $counter; ?></span>
                                        <div class="trending-post-info">
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <span><?php echo get_the_date(); ?></span>
                                        </div>
                                    </li>
                                    <?php
                                    $counter++;
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </ul>
                            <?php
                        endif;
                        ?>
                    </section>

                    <section class="widget">
                        <h3 class="widget-title"><?php _e('Categories', 'tempo-news'); ?></h3>
                        <ul>
                            <?php
                            wp_list_categories(array(
                                'title_li'     => '',
                                'show_count'   => true,
                                'hide_empty'   => false,
                            ));
                            ?>
                        </ul>
                    </section>

                    <section class="widget">
                        <h3 class="widget-title"><?php _e('Follow Us', 'tempo-news'); ?></h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon social-facebook" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon social-twitter" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon social-instagram" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon social-youtube" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="social-icon social-whatsapp" aria-label="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Ad Slot - Sidebar -->
                <?php if (is_active_sidebar('ad-sidebar')) : ?>
                    <?php dynamic_sidebar('ad-sidebar'); ?>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</div>

<?php
get_footer();
