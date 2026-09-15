<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _e('Skip to content', 'tempo-news'); ?></a>

    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-content">
                <div class="header-date">
                    <?php echo date_i18n(__('l, j F Y', 'tempo-news')); ?>
                </div>
                <div class="header-social">
                    <?php if (has_nav_menu('social')) : ?>
                        <nav class="social-menu">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'social',
                                'menu_class'     => 'social-icons',
                                'depth'          => 1,
                                'link_before'    => '<span class="screen-reader-text">',
                                'link_after'     => '</span>',
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header id="masthead" class="site-header">
        <div class="header-main">
            <div class="container">
                <div class="header-main-content" style="display: flex; justify-content: space-between; align-items: center;">
                    <!-- Logo -->
                    <div class="site-logo">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                        <?php endif; ?>
                    </div>

                    <!-- Header Widget Area -->
                    <?php if (is_active_sidebar('header-widget')) : ?>
                        <div class="header-widget-area">
                            <?php dynamic_sidebar('header-widget'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ad Slot - Header -->
        <?php if (is_active_sidebar('ad-header')) : ?>
            <div class="container">
                <?php dynamic_sidebar('ad-header'); ?>
            </div>
        <?php endif; ?>

        <!-- Primary Navigation -->
        <?php if (has_nav_menu('primary')) : ?>
            <nav id="site-navigation" class="main-navigation">
                <div class="container">
                    <div class="main-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'primary-menu',
                            'container'      => false,
                            'depth'          => 3,
                        ));
                        ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Breaking News Bar -->
        <div class="breaking-news-bar">
            <div class="container">
                <div style="display: flex; align-items: center;">
                    <span class="breaking-label"><?php _e('Breaking News', 'tempo-news'); ?></span>
                    <div class="marquee-container">
                        <div class="marquee-content">
                            <?php
                            // Get latest posts for breaking news
                            $breaking_args = array(
                                'posts_per_page' => 5,
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                            );
                            $breaking_query = new WP_Query($breaking_args);
                            
                            if ($breaking_query->have_posts()) :
                                while ($breaking_query->have_posts()) : $breaking_query->the_post();
                                    ?>
                                    <a href="<?php the_permalink(); ?>" style="color: #fff; margin-right: 30px;">
                                        <?php the_title(); ?>
                                    </a>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Headline Section (Homepage only) -->
    <?php if (is_front_page()) : ?>
        <section class="headline-section">
            <div class="container">
                <div class="headline-grid">
                    <?php
                    // Get featured post
                    $featured_args = array(
                        'posts_per_page' => 1,
                        'meta_key'       => '_thumbnail_id',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $featured_query = new WP_Query($featured_args);
                    
                    if ($featured_query->have_posts()) :
                        while ($featured_query->have_posts()) : $featured_query->the_post();
                            ?>
                            <div class="headline-main">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('tempo-headline'); ?>
                                    <?php endif; ?>
                                    <div class="headline-overlay">
                                        <h2 class="headline-title"><?php the_title(); ?></h2>
                                        <div class="headline-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    
                    // Get secondary headlines
                    $secondary_args = array(
                        'posts_per_page' => 3,
                        'meta_key'       => '_thumbnail_id',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'offset'         => 1,
                    );
                    $secondary_query = new WP_Query($secondary_args);
                    
                    if ($secondary_query->have_posts()) :
                        ?>
                        <div class="headline-secondary">
                            <?php while ($secondary_query->have_posts()) : $secondary_query->the_post(); ?>
                                <div class="headline-item">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('tempo-thumbnail'); ?>
                                        <?php endif; ?>
                                        <h3 class="headline-item-title"><?php the_title(); ?></h3>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <?php
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
