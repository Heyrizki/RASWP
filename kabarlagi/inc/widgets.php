<?php
/**
 * Custom Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register custom widgets
function kabarlagi_register_widgets() {
    register_widget( 'KabarLagi_Trending_Widget' );
    register_widget( 'KabarLagi_Latest_Posts_Widget' );
    register_widget( 'KabarLagi_Popular_Posts_Widget' );
    register_widget( 'KabarLagi_Recent_Comments_Widget' );
    register_widget( 'KabarLagi_Social_Follow_Widget' );
    register_widget( 'KabarLagi_Category_Posts_Widget' );
    register_widget( 'KabarLagi_Ads_Widget' );
}
add_action( 'widgets_init', 'kabarlagi_register_widgets' );

/**
 * Trending Posts Widget
 */
class KabarLagi_Trending_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_trending',
            __( 'Kabar Lagi - Trending Posts', 'kabarlagi' ),
            array( 'description' => __( 'Display trending posts based on views', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        );
        
        if ( $category ) {
            $query_args['cat'] = $category;
        }
        
        $trending_query = new WP_Query( $query_args );
        
        if ( $trending_query->have_posts() ) {
            echo '<ul class="trending-posts-list">';
            while ( $trending_query->have_posts() ) {
                $trending_query->the_post();
                ?>
                <li class="trending-post-item">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="trending-post-thumb">
                            <?php the_post_thumbnail( 'kabarlagi-thumb' ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="trending-post-content">
                        <h4>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <span class="trending-post-views">
                            <i class="kl-icon-eye"></i> <?php echo kabarlagi_get_post_views( get_the_ID() ); ?>
                        </span>
                    </div>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title    = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending Now', 'kabarlagi' );
        $count    = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of posts:', 'kabarlagi' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
                <?php _e( 'Category:', 'kabarlagi' ); ?>
            </label>
            <?php
            wp_dropdown_categories( array(
                'name'             => $this->get_field_name( 'category' ),
                'selected'         => $category,
                'show_option_all'  => __( 'All Categories', 'kabarlagi' ),
                'hide_empty'       => false,
            ) );
            ?>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']    = sanitize_text_field( $new_instance['title'] );
        $instance['count']    = absint( $new_instance['count'] );
        $instance['category'] = absint( $new_instance['category'] );
        return $instance;
    }
}

/**
 * Latest Posts Widget
 */
class KabarLagi_Latest_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_latest',
            __( 'Kabar Lagi - Latest Posts', 'kabarlagi' ),
            array( 'description' => __( 'Display latest posts', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_thumb = ! empty( $instance['show_thumb'] ) ? true : false;
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'no_found_rows'  => true,
        );
        
        if ( $category ) {
            $query_args['cat'] = $category;
        }
        
        $latest_query = new WP_Query( $query_args );
        
        if ( $latest_query->have_posts() ) {
            echo '<ul class="latest-posts-list">';
            while ( $latest_query->have_posts() ) {
                $latest_query->the_post();
                ?>
                <li class="latest-post-item">
                    <?php if ( $show_thumb && has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="latest-post-thumb">
                            <?php the_post_thumbnail( 'kabarlagi-thumb' ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="latest-post-content">
                        <h4>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <span class="latest-post-time">
                            <i class="kl-icon-clock"></i> <?php echo kabarlagi_relative_time( get_the_time( 'Y-m-d H:i:s' ) ); ?>
                        </span>
                    </div>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest News', 'kabarlagi' );
        $count      = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $category   = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_thumb = ! empty( $instance['show_thumb'] ) ? true : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of posts:', 'kabarlagi' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
                <?php _e( 'Category:', 'kabarlagi' ); ?>
            </label>
            <?php
            wp_dropdown_categories( array(
                'name'             => $this->get_field_name( 'category' ),
                'selected'         => $category,
                'show_option_all'  => __( 'All Categories', 'kabarlagi' ),
                'hide_empty'       => false,
            ) );
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" <?php checked( $show_thumb ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>">
                <?php _e( 'Show Thumbnail', 'kabarlagi' ); ?>
            </label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']      = sanitize_text_field( $new_instance['title'] );
        $instance['count']      = absint( $new_instance['count'] );
        $instance['category']   = absint( $new_instance['category'] );
        $instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? true : false;
        return $instance;
    }
}

/**
 * Popular Posts Widget
 */
class KabarLagi_Popular_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_popular',
            __( 'Kabar Lagi - Popular Posts', 'kabarlagi' ),
            array( 'description' => __( 'Display popular posts based on comments', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $popular_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'orderby'        => 'comment_count',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ) );
        
        if ( $popular_query->have_posts() ) {
            echo '<ul class="popular-posts-list">';
            while ( $popular_query->have_posts() ) {
                $popular_query->the_post();
                ?>
                <li class="popular-post-item">
                    <a href="<?php the_permalink(); ?>" class="popular-post-link">
                        <?php the_title(); ?>
                    </a>
                    <span class="popular-post-comments">
                        <i class="kl-icon-comment"></i> <?php echo get_comments_number(); ?>
                    </span>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Posts', 'kabarlagi' );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of posts:', 'kabarlagi' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['count'] = absint( $new_instance['count'] );
        return $instance;
    }
}

/**
 * Recent Comments Widget
 */
class KabarLagi_Recent_Comments_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_recent_comments',
            __( 'Kabar Lagi - Recent Comments', 'kabarlagi' ),
            array( 'description' => __( 'Display recent comments', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $comments = get_comments( array(
            'number'    => $count,
            'status'    => 'approve',
            'post_type' => 'post',
        ) );
        
        if ( $comments ) {
            echo '<ul class="recent-comments-list">';
            foreach ( $comments as $comment ) {
                ?>
                <li class="recent-comment-item">
                    <span class="comment-author"><?php echo get_comment_author( $comment ); ?></span>
                    <span class="comment-on"><?php _e( 'on', 'kabarlagi' ); ?></span>
                    <a href="<?php echo get_comment_link( $comment ); ?>">
                        <?php echo get_the_title( $comment->comment_post_ID ); ?>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Comments', 'kabarlagi' );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of comments:', 'kabarlagi' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['count'] = absint( $new_instance['count'] );
        return $instance;
    }
}

/**
 * Social Follow Widget
 */
class KabarLagi_Social_Follow_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_social',
            __( 'Kabar Lagi - Social Media', 'kabarlagi' ),
            array( 'description' => __( 'Display social media links', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $social_networks = array(
            'facebook'  => array( 'icon' => 'facebook', 'label' => 'Facebook' ),
            'twitter'   => array( 'icon' => 'twitter', 'label' => 'Twitter' ),
            'instagram' => array( 'icon' => 'instagram', 'label' => 'Instagram' ),
            'youtube'   => array( 'icon' => 'youtube', 'label' => 'YouTube' ),
            'tiktok'    => array( 'icon' => 'tiktok', 'label' => 'TikTok' ),
            'linkedin'  => array( 'icon' => 'linkedin', 'label' => 'LinkedIn' ),
            'telegram'  => array( 'icon' => 'telegram', 'label' => 'Telegram' ),
            'whatsapp'  => array( 'icon' => 'whatsapp', 'label' => 'WhatsApp' ),
        );
        
        echo '<div class="social-follow-widget">';
        foreach ( $social_networks as $network => $data ) {
            $url = get_theme_mod( 'kabarlagi_social_' . $network );
            if ( $url ) {
                echo '<a href="' . esc_url( $url ) . '" class="social-link social-' . esc_attr( $network ) . '" 
                      target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">
                      <i class="kl-icon-' . esc_attr( $network ) . '"></i>
                      </a>';
            }
        }
        echo '</div>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Follow Us', 'kabarlagi' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        return $instance;
    }
}

/**
 * Category Posts Widget
 */
class KabarLagi_Category_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_category_posts',
            __( 'Kabar Lagi - Category Posts', 'kabarlagi' ),
            array( 'description' => __( 'Display posts from specific category', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $title    = apply_filters( 'widget_title', $instance['title'] );
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $count    = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $style    = ! empty( $instance['style'] ) ? $instance['style'] : 'list';
        
        if ( ! $category ) {
            return;
        }
        
        $cat = get_category( $category );
        if ( ! $cat ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            $display_title = $title;
        } else {
            $display_title = $cat->name;
        }
        
        echo $args['before_title'] . $display_title . $args['after_title'];
        
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'cat'            => $category,
            'no_found_rows'  => true,
        );
        
        $cat_query = new WP_Query( $query_args );
        
        if ( $cat_query->have_posts() ) {
            echo '<div class="category-posts-widget style-' . esc_attr( $style ) . '">';
            
            if ( $style === 'grid' ) {
                echo '<div class="category-posts-grid">';
            } else {
                echo '<ul class="category-posts-list">';
            }
            
            while ( $cat_query->have_posts() ) {
                $cat_query->the_post();
                
                if ( $style === 'grid' ) {
                    ?>
                    <div class="category-post-grid-item">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="category-post-grid-thumb">
                                <?php the_post_thumbnail( 'kabarlagi-grid-small' ); ?>
                            </a>
                        <?php endif; ?>
                        <h4>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                    </div>
                    <?php
                } else {
                    ?>
                    <li class="category-post-list-item">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span class="post-time"><?php echo kabarlagi_relative_time( get_the_time( 'Y-m-d H:i:s' ) ); ?></span>
                    </li>
                    <?php
                }
            }
            
            if ( $style === 'grid' ) {
                echo '</div>';
            } else {
                echo '</ul>';
            }
            
            echo '</div>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title    = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $count    = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $style    = ! empty( $instance['style'] ) ? $instance['style'] : 'list';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title (optional):', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
                <?php _e( 'Category:', 'kabarlagi' ); ?>
            </label>
            <?php
            wp_dropdown_categories( array(
                'name'             => $this->get_field_name( 'category' ),
                'selected'         => $category,
                'show_option_none' => __( 'Select Category', 'kabarlagi' ),
                'hide_empty'       => false,
            ) );
            ?>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of posts:', 'kabarlagi' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
                <?php _e( 'Display Style:', 'kabarlagi' ); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" 
                    name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="list" <?php selected( $style, 'list' ); ?>><?php _e( 'List', 'kabarlagi' ); ?></option>
                <option value="grid" <?php selected( $style, 'grid' ); ?>><?php _e( 'Grid', 'kabarlagi' ); ?></option>
            </select>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']    = sanitize_text_field( $new_instance['title'] );
        $instance['category'] = absint( $new_instance['category'] );
        $instance['count']    = absint( $new_instance['count'] );
        $instance['style']    = sanitize_text_field( $new_instance['style'] );
        return $instance;
    }
}

/**
 * Ads Widget
 */
class KabarLagi_Ads_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'kabarlagi_ads',
            __( 'Kabar Lagi - Advertisement', 'kabarlagi' ),
            array( 'description' => __( 'Display advertisement banner', 'kabarlagi' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        $image_url = ! empty( $instance['image_url'] ) ? esc_url( $instance['image_url'] ) : '';
        $link_url  = ! empty( $instance['link_url'] ) ? esc_url( $instance['link_url'] ) : '';
        $alt_text  = ! empty( $instance['alt_text'] ) ? esc_attr( $instance['alt_text'] ) : 'Advertisement';
        $ad_code   = ! empty( $instance['ad_code'] ) ? $instance['ad_code'] : '';
        
        echo $args['before_widget'];
        
        if ( $ad_code ) {
            echo $ad_code;
        } elseif ( $image_url ) {
            if ( $link_url ) {
                echo '<a href="' . $link_url . '" target="_blank" rel="noopener sponsored">';
            }
            echo '<img src="' . $image_url . '" alt="' . $alt_text . '" class="ads-banner-image">';
            if ( $link_url ) {
                echo '</a>';
            }
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
        $link_url  = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '';
        $alt_text  = ! empty( $instance['alt_text'] ) ? $instance['alt_text'] : 'Advertisement';
        $ad_code   = ! empty( $instance['ad_code'] ) ? $instance['ad_code'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>">
                <?php _e( 'Image URL:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $image_url ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>">
                <?php _e( 'Link URL:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $link_url ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>">
                <?php _e( 'Alt Text:', 'kabarlagi' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'alt_text' ) ); ?>" type="text" 
                   value="<?php echo esc_attr( $alt_text ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>">
                <?php _e( 'Ad Code (HTML/AdSense):', 'kabarlagi' ); ?>
            </label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>" 
                      name="<?php echo esc_attr( $this->get_field_name( 'ad_code' ) ); ?>" rows="4"><?php echo esc_textarea( $ad_code ); ?></textarea>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['image_url'] = esc_url_raw( $new_instance['image_url'] );
        $instance['link_url']  = esc_url_raw( $new_instance['link_url'] );
        $instance['alt_text']  = sanitize_text_field( $new_instance['alt_text'] );
        $instance['ad_code']   = wp_kses_post( $new_instance['ad_code'] );
        return $instance;
    }
}
