    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-widgets">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'All rights reserved.', 'kabarlagi' ); ?></p>
                    
                    <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <nav class="footer-navigation">
                            <?php wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                            ) ); ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Mobile Menu Bottom -->
    <nav class="mobile-menu-bottom">
        <ul>
            <li><a href="<?php echo home_url(); ?>"><span class="menu-icon">🏠</span><?php _e( 'Home', 'kabarlagi' ); ?></a></li>
            <li><a href="#" class="menu-categories"><span class="menu-icon">📁</span><?php _e( 'Categories', 'kabarlagi' ); ?></a></li>
            <li><a href="#" class="menu-search"><span class="menu-icon">🔍</span><?php _e( 'Search', 'kabarlagi' ); ?></a></li>
            <li><a href="#" class="dark-mode-toggle-mobile"><span class="menu-icon">🌓</span><?php _e( 'Dark Mode', 'kabarlagi' ); ?></a></li>
            <li><a href="#" class="menu-more"><span class="menu-icon">☰</span><?php _e( 'More', 'kabarlagi' ); ?></a></li>
        </ul>
    </nav>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
