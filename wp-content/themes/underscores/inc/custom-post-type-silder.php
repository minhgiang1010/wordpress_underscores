<?php
/**
 * Tạo ra 2 cpt 1 làm cha(slider), cpt 2 làm con(per slide)
 * Custom meta box form submit thẳng để khởi tạo cpt 2.
 * custom meta box và vị trí khác nhau trên mỗi trang trong cpt slider.
 */

add_action( 'init', 'cpt_slider' );
add_action( 'init', 'cpt_slide' );
add_action( 'admin_head', 'cpt_css' );
add_action( 'post_submitbox_misc_actions', 'cpt_post_type_info' );

add_filter( 'manage_cpt-slider_posts_columns', 'cpt_set_columns' );
add_action( 'manage_cpt-slider_posts_custom_column', 'cpt_custom_column', 10, 2 );

if ( is_admin() ) {
    // Xử lý trên trang add slide
    add_action( 'load-post-new.php', 'init_metabox_load_post_new' );
    // Xử lý trên trang slide
    add_action( 'load-post.php', 'init_metabox_load_post' );
}

function init_metabox_load_post_new() {
    add_action( 'add_meta_boxes', 'cpt_add_meta_box' );
}

function init_metabox_load_post() {
    add_action( 'add_meta_boxes', 'cpt_add_super_meta_box' );
}

add_action( 'save_post', 'save_meta_cpt_add_new', 10, 2 );

function cpt_slider() {
    $labels = array(
        'name' => 'Slider',
        'singular_name' => 'Slider',
        'menu_name' => 'Sliders',
        'name_admin_bar' => 'Slider',
    );

    $args = array(
        'labels'			        => $labels,
		'show_ui'			        => true,
		'show_in_menu'	        	=> true,
		'capability_type'	        => 'post',
        'hierarchical'	        	=> false,
        'public'                    => true,
		'menu_position'	        	=> 26,
        'menu_icon'		        	=> 'dashicons-images-alt',
		'supports'		        	=> false
    );

    register_post_type( 'cpt-slider', $args );	
}

function cpt_slide() {
    $labels = array(
        'name' => 'Slide',
        'singular_name' => 'Slide',
        'menu_name' => 'Slide',
    );

    $args = array(
        'labels'			        => $labels,
		'show_ui'			        => true,
		'show_in_menu'	        	=> true,
        'capability_type'	        => 'post',
        'show_ui'                   => false,
        'hierarchical'	        	=> false,
        'public'                    => true,
		'supports'		        	=> false
    );

    register_post_type( 'cpt-per-slide', $args );
}

function cpt_post_type_info($post_obj) {
    global $post;
    $post_type = 'cpt-slider'; 
   
    if( $post_type == $post->post_type ) {
        echo  "<div class=\"misc-pub-section\">
            <b> Information </b>
            <p>  123 </p>
        </div>";
        echo "<pre>";
            print_r( $post_obj );
        echo "</pre>";
    }
}

function cpt_css() {
    global $post_type;
    if( $post_type == 'cpt-slider') {
        echo '
        <style type="text/css">
            #misc-publishing-actions,
            #minor-publishing-actions{
                display:none;
            }

            #post-body-content {
                margin-bottom: 0px!important;
            }

            #post-body #normal-sortables {
                min-height: 0px;
            }
        </style>
        ';
    }
}

function cpt_set_columns ( $col ) {
    $newColumns = array();
    $newColumns['cb'] = 'Bulk action';
    // $newColumns['slider-image'] = 'Slider Image';
    $newColumns['title-slide'] = 'Title';
    $newColumns['description'] = 'Description';
    $newColumns['count'] = 'Number of Images';
	$newColumns['shortcode'] = 'Shortcode';
	return $newColumns;
}

function cpt_custom_column( $col, $post_id ) {

    switch( $col ){

        case 'cb':
            echo '<input type="checkbox" />';
            break;

		case 'title-slide' :
            $title = get_post_meta( $post_id, '_title_slider_value_key', true );
            echo '<a href="'. get_admin_url().'post.php?post='.$post_id.'&action=edit" >'.$title.'</a>';
			break;
        
        case 'description': 
            $description = get_post_meta( $post_id, '_description_slider_value_key', true );
            echo $description;
            break;

        case 'count' : 
            $args = array(
                'post_type'  => 'cpt-per-slide',
                'posts_per_page' => -1,
                'post_parent'  => $post_id
            );

            $count = count ( get_posts( $args ) );

            echo esc_html( $count );
            break;
            
		case 'shortcode' :
            echo '[ nht_simple_slide id="'.esc_html( $post_id ) .'" ]';
			break;
	}  

}

function cpt_add_meta_box() {
	add_meta_box( 'slide_info', 'Main Slider Setting', 'cpt_setting_callback', 'cpt-slider', 'advanced', 'high' );
}

function cpt_add_super_meta_box() {
    add_meta_box( 'child_slide_info', 'Click to edit your slide', 'cpt_setting_slide_callback', 'cpt-slider', 'advanced', 'high' );

    add_meta_box( 'title_slide', 'Slide information', 'cpt_setting_title_callback', 'cpt-slider', 'side');
}

function cpt_setting_callback( $post ) {
    wp_nonce_field( 'cpt_slider_data', 'cpt_slider_meta_box_nonce' );

    $value_title = get_post_meta( $post->ID, '_title_slider_value_key', true );
    $value_description = get_post_meta( $post->ID, '_description_slider_value_key', true );

    echo "<table class=\"form-table\">";
    echo "<tbody>";
    echo "<tr>";
        echo "<th><label for=\"cpt_title_field\">Title: </label></th>";
        echo "<td><input class=\"regular-text code\" type=\"text\" id=\"cpt_title_field\" name=\"cpt_title_field\" value=\"". esc_attr($value_title) ."\" /><p>Title slider ( slider 1 )</p></td>";
    echo "</tr>";

    echo "<tr>";
        echo '<th><label for="cpt_description_field">Description: </label></th>';
        echo "<td><textarea name=\"cpt_description_field\" id=\"cpt_description_field\" class=\"large-text code\" rows=\"3\" spellcheck=\"false\">". esc_attr($value_description) ."</textarea><p>Type your description here !</p></td>";
    echo "</tr>";

    echo "<tbody>";
    echo "</table>";
}

function cpt_setting_title_callback( $post ) {
    
    wp_nonce_field( 'cpt_slider_data', 'cpt_slider_meta_box_nonce' );

    $value_title = get_post_meta( $post->ID, '_title_slider_value_key', true );
    $value_description = get_post_meta( $post->ID, '_description_slider_value_key', true );

    echo "<div class=\"label-slide-title\"><label for=\"cpt_title_field\">Title: </label></div>";
    echo "<div><input class=\"code input-slide-title\" type=\"text\" id=\"cpt_title_field\" name=\"cpt_title_field\" value=\"". esc_attr($value_title) ."\" /></div>";

    echo "<br>";

    echo "<div class=\"label-slide-title\"><label for=\"cpt_description_field\">Description: </label></div>";
    echo "<div><textarea name=\"cpt_description_field\" id=\"cpt_description_field\" class=\"large-text code\" rows=\"3\" spellcheck=\"false\">". esc_attr($value_description) ."</textarea></div>";

}

function cpt_setting_slide_callback( $post ) {
    $args = array( 
        'post_type' => 'cpt-per-slide',
        'post_parent' => $post->ID,
        'order' => 'ASC',
        'posts_per_page' => -1
    );
    $query = new WP_Query( $args );

    ?>
    <ul class="thumbs-wrapper" data-id-parent="<?php echo esc_attr( $post->ID ); ?>">
        <?php 
            if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        ?>
        <li data-id="<?php the_ID(); ?>" class="slide <?php echo "slide-".get_the_ID();?>">
            <div class="delete-btn"></div>
            <img src="<?php echo esc_url( get_the_excerpt() ); ?>" height="75" width="138">		
        </li>

        <?php 
            endwhile; endif;
            wp_reset_postdata();
        ?>            
        <li class="add-new-btn selected">
            <div>
                <p> Add New </p>
            </div>
        </li>
    </ul>
        
    <div class="clear"></div>

    <div class="slides-options">
        <div class="slide-opt">
            <div class="backend-option">
                <div class="backend-option-label">
                    <label>Image</label>
                </div>
                <div class="backend-option-input">
                    <div class="thumb" data-image="<?php echo get_template_directory_uri();?>/images/no-image.png">
                        <img src="<?php echo get_template_directory_uri();?>/images/no-image.png" style="background-image: url(<?php echo get_template_directory_uri();?>/images/no-image.png)" alt="">
                        <a href="#"> </a>
                    </div>
                </div>
            </div>
            <div class="backend-option">
                <div class="backend-option-label">
                    <label>Title</label>
                </div>
                <div class="backend-option-input">
                <input name="" id="title-slide" class="" value="" type="text">
                </div>
            </div>
            <div class="backend-option">
                <div class="backend-option-label">
                    <label>Description</label>
                </div>
                <div class="backend-option-input">
                    <textarea rows="6" name="" id="description-slide" class="code" spellcheck="false"></textarea>
                </div>
            </div>
            <div class="buttons-wrapper">
                <button id="add-slide" class="button-primary button-large">Add Slide</button>
                <button id="update-slide" data-id='' class="edit-buttons button-primary button-large" style="display: none;" > Save slide </button>
                <button id="cancel-slide" class="edit-buttons button">Cancel</button>
            </div>
        </div>
    </div>

   <?php
}

function save_meta_cpt_add_new( $post_id ) {

	if( ! isset( $_POST['cpt_slider_meta_box_nonce'] ) ){
		return;
	}
	
	if( ! wp_verify_nonce( $_POST['cpt_slider_meta_box_nonce'], 'cpt_slider_data') ) {
		return;
	}
	
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['cpt_title_field'] ) ) {
		return;
	}
    
    if( ! isset( $_POST['cpt_description_field'] ) ) {
		return;
    }
    
	$title_data = sanitize_text_field( $_POST['cpt_title_field'] );
    $description_data = sanitize_text_field( $_POST['cpt_description_field'] );
    
	update_post_meta( $post_id, '_title_slider_value_key', $title_data );
    update_post_meta( $post_id, '_description_slider_value_key', $description_data );

    // ! ;) https://developer.wordpress.org/reference/functions/wp_update_post/#user-contributed-notes
    if ( ! wp_is_post_revision( $post_id ) ){
        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'save_meta_cpt_add_new');

        $post = array( 
            'ID' => $post_id, 
            'post_title' => 'slider_'.$post_id,
            'post content' => 'slider_info_'.$post_id
        );
        
        wp_update_post( $post );

        add_action('save_post', 'save_meta_cpt_add_new');
    }
}

