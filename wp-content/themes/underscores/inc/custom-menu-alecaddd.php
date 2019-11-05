<?php 

/**
 * wp_nav_menu();
 * 
 * <div class="menu-container">
 *      <ul> // start_lvl()
 *          <li><a><span> // start_el()
 *              </a></span></li> // end_el()
 *      </ul> // end_lvl()
 * </div>
 */

class HuuTien_Menu_Walker_Alecaddd extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = array() ) { // ul
        $indent = str_repeat("\t", $depth);
        $submenu = ( $depth > 0 ) ? 'sub-menu' : '';
        $output .= "\n{$indent}<ul class=\"dropdown-menu{$submenu} depth_{$depth}\">\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){ //li a span
		
		$indent = ( $depth ) ? str_repeat("\t",$depth) : '';
		
		$li_attributes = '';
		$class_names = $value = '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        // * Tạo class cho li
        // ! Để ý đối tượng Walker còn rất nhiều hữu ích, $args->walker->has_children
        // Thêm class dropdown
        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        // Để xem $item cứ 
        // echo "<pre>";
        // print_r( $item );
        // echo "</pre>";
        // Thêm class active khi cả cha và con
		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        // Thểm class menu-item-{ID}
        $classes[] = 'menu-item-' . $item->ID;
        // Thêm class dropdown-submenu khi $depth
        if( $depth && $args->walker->has_children ){
			$classes[] = 'dropdown-submenu'; // vậy vừa có class dropdown, dropdown-submenu
		}
        // array_filter để loại bỏ giá trị false trong mảng.
        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr($class_names) . '"';
        
        // * Tạo id cho li
		$id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
        
        // * Tạo thuộc tính cho tag <a> bao gồm href, class, ...
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr($item->url) . '"' : '';
		
		$attributes .= ( $args->walker->has_children ) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
		
		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= ( $depth == 0 && $args->walker->has_children ) ? ' <b class="caret"></b></a>' : '</a>';
        $item_output .= $args->after;
		
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}    
