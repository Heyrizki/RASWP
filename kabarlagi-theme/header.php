<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('auto-dark-mode'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kabarlagi' ); ?></a>

    <header id="masthead" class="site-header">
        <!-- Header Top Bar -->
        <div class="header-top">
            <div class="container">
                <div class="header-top-content">
                    <span class="current-date"><?php echo date_i18n( 'l, j F Y' ); ?></span>
                    <?php if ( has_nav_menu( 'topbar' ) ) : ?>
                        <nav class="top-bar-navigation">
                            <?php wp_nav_menu( array(
                                'theme_location' => 'topbar',
                                'menu_class'     => 'top-bar-menu',
                                'depth'          => 1,
                            ) ); ?>
                        </nav>
                    <?php endif; ?>
                    
                    <div class="header-social">
                        <?php
                        $facebook = get_theme_mod( 'social_facebook' );
                        $twitter = get_theme_mod( 'social_twitter' );
                        $instagram = get_theme_mod( 'social_instagram' );
                        $youtube = get_theme_mod( 'social_youtube' );
                        
                        if ( $facebook ) echo '<a href="' . esc_url( $facebook ) . '" target="_blank">FB</a>';
                        if ( $twitter ) echo '<a href="' . esc_url( $twitter ) . '" target="_blank">TW</a>';
                        if ( $instagram ) echo '<a href="' . esc_url( $instagram ) . '" target="_blank">IG</a>';
                        if ( $youtube ) echo '<a href="' . esc_url( $youtube ) . '" target="_blank">YT</a>';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="header-main">
            <div class="container">
                <div class="header-wrapper">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ( has_nav_menu( 'primary' ) ) : ?>
                        <nav id="site-navigation" class="main-navigation">
                            <?php wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'primary-menu',
                                'container'      => false,
                                'fallback_cb'    => false,
                            ) ); ?>
                        </nav>
                    <?php endif; ?>

                    <div class="header-actions">
                        <button class="mobile-menu-toggle" aria-label="Toggle Menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                        
                        <?php kabarlagi_dark_mode_toggle(); ?>
                        
                        <div class="header-search">
                            <button class="search-toggle" aria-label="Search">🔍</button>
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <input type="search" placeholder="<?php _e( 'Search...', 'kabarlagi' ); ?>" name="s" />
                                <input type="submit" value="Search" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breaking News Bar -->
        <?php if ( has_nav_menu( 'breaking' ) ) : ?>
            <?php kabarlagi_breaking_news(); ?>
        <?php endif; ?>

        <!-- Header Ad Placement -->
        <?php if ( is_active_sidebar( 'header-ad' ) ) : ?>
            <div class="ad-header">
                <div class="container">
                    <?php dynamic_sidebar( 'header-ad' ); ?>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <!-- Floating Ads -->
    <?php if ( is_active_sidebar( 'floating-left' ) && ! wp_is_mobile() ) : ?>
        <div class="floating-ad-container left">
            <?php dynamic_sidebar( 'floating-left' ); ?>
        </div>
    <?php endif; ?>

    <?php if ( is_active_sidebar( 'floating-right' ) && ! wp_is_mobile() ) : ?>
        <div class="floating-ad-container right">
            <?php dynamic_sidebar( 'floating-right' ); ?>
        </div>
    <?php endif; ?>

    <div id="content" class="site-content">
