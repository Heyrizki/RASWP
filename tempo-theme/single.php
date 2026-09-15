<?php
/**
 * The template for displaying single posts
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
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
                        
                        <!-- Article Header -->
                        <header class="article-header">
                            <?php
                            // Category
                            $categories = get_the_category();
                            if ($categories) :
                                ?>
                                <div class="article-category">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="category-badge">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Title -->
                            <h1 class="article-title"><?php the_title(); ?></h1>
                            
                            <!-- Meta -->
                            <div class="article-meta">
                                <span class="author">
                                    <i class="fas fa-user"></i>
                                    <?php the_author_posts_link(); ?>
                                </span>
                                <span class="date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'tempo-news'); ?>
                                </span>
                                <span class="comments">
                                    <i class="fas fa-comment"></i>
                                    <?php comments_number(__('0 Comments', 'tempo-news'), __('1 Comment', 'tempo-news'), __('% Comments', 'tempo-news')); ?>
                                </span>
                            </div>
                        </header>

                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="article-featured-image">
                                <?php the_post_thumbnail('large'); ?>
                                <?php if (get_the_post_thumbnail_caption()) : ?>
                                    <figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endif; ?>

                        <!-- Ad Slot - Before Content -->
                        <?php if (is_active_sidebar('ad-in-article')) : ?>
                            <div class="ad-container ad-before-content">
                                <?php dynamic_sidebar('ad-in-article'); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Article Content -->
                        <div class="article-content">
                            <?php
                            // Get content and insert related posts after certain paragraphs
                            $content = get_the_content();
                            $paragraphs = explode('</p>', $content);
                            $total_paragraphs = count($paragraphs);
                            $related_inserted = false;
                            $parallax_inserted = false;
                            
                            foreach ($paragraphs as $index => $paragraph) {
                                echo $paragraph . '</p>';
                                
                                // Insert Parallax Ad after 3rd paragraph
                                if (!$parallax_inserted && $index == 2) :
                                    ?>
                                    <div class="parallax-ad-container">
                                        <div class="parallax-ad" style="background-image: url('<?php echo get_theme_mod('tempo_parallax_ad_image', get_template_directory_uri() . '/images/parallax-ad.jpg'); ?>');">
                                            <div class="parallax-ad-content">
                                                <?php echo get_theme_mod('tempo_parallax_ad_content', '<h3>Special Advertisement</h3>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $parallax_inserted = true;
                                endif;
                                
                                // Insert Inline Related Posts after 5th paragraph
                                if (!$related_inserted && $index == 4) :
                                    $related_posts = tempo_news_get_related_posts(get_the_ID(), 3);
                                    if ($related_posts->have_posts()) :
                                        ?>
                                        <div class="inline-related-posts">
                                            <h3 class="inline-related-title"><?php _e('Related News', 'tempo-news'); ?></h3>
                                            <div class="related-posts-grid">
                                                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                                    <div class="related-post-item">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <?php the_post_thumbnail('tempo-related'); ?>
                                                            <?php else : ?>
                                                                <img src="<?php echo get_template_directory_uri(); ?>/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                                                            <?php endif; ?>
                                                            <h4><?php the_title(); ?></h4>
                                                        </a>
                                                    </div>
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                        <?php
                                        wp_reset_postdata();
                                        $related_inserted = true;
                                    endif;
                                endif;
                            }
                            ?>
                        </div>

                        <!-- Tags -->
                        <?php $tags = get_the_tags(); if ($tags) : ?>
                            <div class="article-tags">
                                <strong><?php _e('Tags:', 'tempo-news'); ?></strong>
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag-link">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Share Buttons -->
                        <div class="article-share">
                            <h4><?php _e('Share this article:', 'tempo-news'); ?></h4>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php urlencode(get_permalink()); ?>" target="_blank" class="share-btn facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php urlencode(get_permalink()); ?>&text=<?php urlencode(get_the_title()); ?>" target="_blank" class="share-btn twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text=<?php urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="share-btn whatsapp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        </div>

                        <!-- Author Box -->
                        <div class="author-box">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                            </div>
                            <div class="author-info">
                                <h4><?php the_author(); ?></h4>
                                <p><?php echo esc_html(get_the_author_meta('description')); ?></p>
                                <div class="author-social">
                                    <?php if (get_the_author_meta('twitter')) : ?>
                                        <a href="https://twitter.com/<?php echo esc_attr(get_the_author_meta('twitter')); ?>" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Post Navigation -->
                        <div class="post-navigation">
                            <?php
                            the_post_navigation(array(
                                'prev_text' => '<span class="nav-subtitle">' . __('Previous:', 'tempo-news') . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . __('Next:', 'tempo-news') . '</span> <span class="nav-title">%title</span>',
                            ));
                            ?>
                        </div>

                        <!-- Comments -->
                        <?php
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>

                    </article>

                <?php endwhile; ?>

            </main>

            <!-- Sidebar -->
            <aside id="secondary" class="sidebar widget-area">
                <?php if (is_active_sidebar('sidebar-main')) : ?>
                    <?php dynamic_sidebar('sidebar-main'); ?>
                <?php endif; ?>

                <!-- Ad Slot - Sidebar -->
                <?php if (is_active_sidebar('ad-sidebar')) : ?>
                    <?php dynamic_sidebar('ad-sidebar'); ?>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</div>

<!-- Dark Mode Toggle -->
<button class="dark-mode-toggle" id="darkModeToggle" aria-label="<?php _e('Toggle Dark Mode', 'tempo-news'); ?>">
    <i class="fas fa-moon"></i>
</button>

<!-- Comment Notification -->
<div class="comment-notification" id="commentNotification">
    <h4><i class="fas fa-bell"></i> <?php _e('New Comment', 'tempo-news'); ?></h4>
    <p id="commentNotificationText"></p>
</div>

<?php
get_footer();
