<?php
/**
 * The template for displaying the footer
 *
 * @package Tempo_News
 */
?>

    <!-- Footer -->
    <footer id="colophon" class="site-footer">
        <div class="container">
            
            <!-- Ad Slot - Footer -->
            <?php if (is_active_sidebar('ad-footer')) : ?>
                <div class="footer-ad">
                    <?php dynamic_sidebar('ad-footer'); ?>
                </div>
            <?php endif; ?>
            
            <!-- Footer Widgets -->
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All Rights Reserved.', 'tempo-news'); ?></p>
                </div>
                
                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-nav',
                            'depth'          => 1,
                        ));
                        ?>
                    </nav>
                <?php endif; ?>
                
                <div class="footer-social">
                    <?php if (has_nav_menu('social')) : ?>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'social',
                            'menu_class'     => 'social-icons',
                            'depth'          => 1,
                            'link_before'    => '<span class="screen-reader-text">',
                            'link_after'     => '</span>',
                        ));
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Menu -->
    <nav class="mobile-bottom-menu">
        <ul>
            <li>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <i class="fas fa-home"></i>
                    <span><?php _e('Home', 'tempo-news'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>">
                    <i class="fas fa-newspaper"></i>
                    <span><?php _e('News', 'tempo-news'); ?></span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-toggle">
                    <i class="fas fa-bars"></i>
                    <span><?php _e('Menu', 'tempo-news'); ?></span>
                </a>
            </li>
            <li>
                <a href="#" class="search-toggle">
                    <i class="fas fa-search"></i>
                    <span><?php _e('Search', 'tempo-news'); ?></span>
                </a>
            </li>
            <li>
                <a href="#" class="dark-mode-toggle-mobile">
                    <i class="fas fa-moon"></i>
                    <span><?php _e('Dark', 'tempo-news'); ?></span>
                </a>
            </li>
        </ul>
    </nav>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
