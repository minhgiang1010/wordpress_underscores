<?php
/**
 * * Walker menu custom lại của alecaddd
 * Custom class: divider, featured-image, megamenu, column-divider.
 */

class HuuTien_Menu_Walker_Alecaddd_Mega extends Walker_Nav_Menu {
    public $isMegaMenu; // boolean
    public $count; // flag
    public $col;

    public function __construct() {
        $this->isMegaMenu = 0;
        $this->count = 1;
        $this->row = 1;
    }
    
    public function start_lvl( &$output, $depth = 0, $args = array() ) {

        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\" >\n";

        if ( $this->isMegaMenu != 0) {
            $output .= "<li class=\"megamenu-col\"><ul>\n";
        }
        
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        // làm cái đóng cho mega menu
        if ( $this->isMegaMenu != 0) {
            $output .= "\n</li></ul>\n";
        }

        $indent  = str_repeat("\t", $depth );
		$output .= "\n {$indent} </ul>\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        // $item->menu_item_parent cha thì 0, check xem megamenu có phải anh lớp cha hay ko, ko thì ko tạo thêm <ul class=        "mega"><li> 
        // if ( $this->isMegaMenu != intval($item->menu_item_parent) ) {
        //     $this->isMegaMenu = 0;
        // }

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // kiểm tra megamenu === menu_item_parant và nó ko phải thằng cha
        if ( $this->isMegaMenu === intval( $item->menu_item_parent ) && $this->isMegaMenu  != 0 ) {

            // check row = description trong class megamenu
            // if ($this->count % ( $this->row + 1 ) == 0) {
                
            //     $output .= "</ul></li> <li class=\"megamenu-col\"><ul>\n";
            //     $this->count = 1;

            // }

            // $this->count++;


            // check 
            $column_divider = array_search( 'column-divider', $classes );
            if ($column_divider !== false) {
                $output .= "</ul></li> <li class=\"megamenu-col\"><ul>\n";
            }        

        } else {

            $this->isMegaMenu = 0;

        }

        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $li_attributes = '';
        $class_names = $value = '';

       

        $divider_class_position = array_search('divider', $classes);
        if ($divider_class_position !== false) {
            $output .= "<li class=\"divider\"></li>\n";
            unset($classes[$divider_class_position]);
        }

        // check có class megamenu thì set class variable = itemID, để thêm vào một tag li ul
        if ( array_search('megamenu', $classes) !== false ) {
            $this->isMegaMenu = $item->ID;
            $this->row = $item->description; // description là số row muốn gán
        }

        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        $classes[] = 'menu-item-' . $item->ID;
        
        // Thêm class dropdown-submenu khi $depth
        if ($depth && $args->walker->has_children) {
            $classes[] = 'dropdown-submenu';
        }

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';
        
        // * Tạo id cho li
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';
        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
        
        // * Tạo thuộc tính cho tag <a> bao gồm href, class, ...
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ($args->walker->has_children) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';

        // kiểm tra xem có class featured-image
        $has_featured_image = array_search('featured-image', $classes);
        if ($has_featured_image !== false) {
            $postID = url_to_postid( $item->url );
            $output .= "<img src=\" ". get_the_post_thumbnail_url( $postID ) ." \">";
        }


        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        
        // add support for menu item title
        if (strlen($item->attr_title) > 2) {
            $item_output .= '<h3 class="tit">' . $item->attr_title . '</h3>';
        }

        // add support for menu item descriptions
        if (strlen($item->description) > 2) {
            $item_output .= '</a> <span class="sub">' . $item->description . '</span>';
        }

        $item_output .= ( ($depth == 0 || 1) && $args->walker->has_children) ? ' <b class="caret"></b></a>' : '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}