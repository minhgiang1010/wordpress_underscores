<?php 
    function nht_simple_slide_func( $args, $content ) {
        
        ob_start();

        $flag = 1;
        $flag_2 = 1;

        $args_post = array( 
            'post_type' => 'cpt-per-slide',
            'post_parent' => (int) $args['id'],
            'order' => 'ASC',
            'posts_per_page' => -1
        );

        $wp_query = new WP_Query( $args_post );
        ?>
        <div class="carousel">
        <button class="carousel__button carousel__button--left">
            <i class="fas fa-angle-left"></i>
        </button>

        <div class="carousel__track-container">
            <ul class="carousel__track">
            <?php if ( $wp_query->have_posts() ) : while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                <li class="carousel__slide <?php echo ( $flag == 1 ) ? 'current-slide' : ''; ?> ">
                    <img class="carousel__image" src="<?php echo get_the_excerpt(); ?>" alt="">
                </li>
            <?php $flag ++;  endwhile; endif; ?>
            </ul>
        </div>
        <button class="carousel__button carousel__button--right">
            <i class="fas fa-angle-right"></i>
        </button>
            <div class="carousel__nav">
                <?php if ( $wp_query->have_posts() ) : while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                    <button class="carousel__indicator <?php echo ( $flag_2 == 1 ) ? 'current-slide' : ''; ?>"></button>
                <?php $flag_2 ++; endwhile; endif; ?>
            </div>
        </div>

        <?php
        wp_reset_query();
        
        return ob_get_clean();
    }
    
    add_shortcode('nht_simple_slide', 'nht_simple_slide_func');