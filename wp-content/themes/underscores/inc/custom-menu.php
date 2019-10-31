<?php 
// indent(v): thụt lề
// Filters:
// start lvl: 
// - nav_menu_submenu_css_class
// start_el:
// - nav_menu_item_args
// - nav_menu_css_class
// - nav_menu_item_id
// - nav_menu_link_attributes
// - the_title
// - nav_menu_item_title
// - walker_nav_menu_start_el

class HuuTien_Menu_Walker extends Walker_Nav_Menu {

    /**
     * lvl: level
     * * Phương thức start_lvl()
     * * Được sử dụng để hiển thị các thẻ bắt đầu cấu trúc của một cấp độ mới trong menu. ( ví dụ: <ul class="sub-menu"> )
     * 
     * @param string     $output Được sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
     * @param int        $depth (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
     * @param stdClass   $args Các tham số trong hàm wp_nav_menu().
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        // @type string  $item_spacing Có duy trì khoản trắng trong HTML của menu thay không ? Chấp nhận 'preserve' hoặc 'discard'. Mặc định 'preserve'. 
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
        }
        
		$indent = str_repeat( $t, $depth );  // *indent tạo thụt lề đầu dòng cho tag ul.

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Tạo đối số/tham số filters  cho CSS classes được áp dụng cho thành phần mục danh sách mục của menu.
		 *
		 * @param string[] $classes Mảng của lớp CSS được áp dụng cho menu `<ul>`
		 * @param stdClass $args    Các tham số trong hàm wp_nav_menu().
		 * @param int      $depth   (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    /** 
     * lvl: level
     * * Phương thức end_lvl()
     * * Được sử dụng để hiển thị đoạn kết thúc của một cấp độ mới trong menu. ( ví dụ <ul> )
     * @param string    $output Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài.
     * @param int       $depth (độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
     * @param stdClass  $args Các tham số trong hàm wp_nav_menu().
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
        }
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
    }

    /**
     * el: element
     * * Phương thức start_el()
     * * Được sử dụng để hiển thị đoạn bắt đầu của một phần tử trong menu. ( ví dụ: <li id="menu-item-5"> )
     * @param string   $output Được sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài.
     * @param WP_Post  $item Dữ liệu của các phần tử trong Menu.
     * @param int      $depth (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
     * @param stdClass $args Các tham số trong hàm của wp_nav_menu().
     * @param int      $id ID của phần tử hiện tại.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0) {
        // @type string  $item_spacing Có duy trì khoản trắng trong HTML của menu thay không ? Chấp nhận 'preserve' hoặc 'discard'. Mặc định 'preserve'. 
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' .$item->ID;        
        
        /**
         * Tạo filters cho một single nav menu item.
		 *
		 * @param stdClass $args  Các tham số trong hàm của wp_nav_menu().
		 * @param WP_Post  $item  Dữ liệu của các phần tử trong Menu.
		 * @param int      $depth (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Tạo filters cho CSS classes được áp dụng cho thành phần mục danh sách mục của menu.
		 *
		 * @param string[] $classes Mảng các lớp CSS được áp dụng cho phần tử `<li>` của mục menu.
		 * @param WP_Post  $item    Menu item hiện tại.
		 * @param stdClass $args    Các tham số trong hàm của wp_nav_menu().
		 * @param int      $depth   (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Tạo Filters the ID áp dụng cho một danh sách phần tử menu.
         *
		 * @param string   $menu_id ID được áp dụng cho phần tử menu `<li>`.
		 * @param WP_Post  $item    Thành phần menu hiện tại.
		 * @param stdClass $args    Các tham số trong hàm của wp_nav_menu().
		 * @param int      $depth   (Độ sâu) Cấp độ hiện tại của menu. Cấp độ 0 là lớn 
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Tạo Filters thuộc tính HTML áp dụng vào thành phần danh mục một menu.
		 *
		 * @param array $atts {
		 *     thuộc tính HTML được áp dụng cho phần tử menu `<a>`, chuỗi rỗng bị bỏ qua.
		 *
		 *     @type string $title        Thuộc tính Title.
		 *     @type string $target       Thuộc tính Target.
		 *     @type string $rel          Thuộc tính rel.
		 *     @type string $href         Thuộc tính href.
		 *     @type string $aria_current Thuộc tính aria-current.
		 * }
		 * @param WP_Post  $item  Danh mục menu hiện tại.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        // $item_output .= '<br />' . $item->description;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * el: element 
     * * Phương thức end_el()
     * * Được sự dụng hiển thị đoạn kết thúc của một phần tử trong menu. ( ví dụ: </li> )
     * @param string    $output Sử dụng để thêm nội dung vào những gì hiển thị ra ngoai.
     * @param WP_Post   $item  Dữ liệu của phần tử trong menu. Không sử dụng.
     * @param int       $depth Cấp độ hiện tại trong menu. Cấp độ 0 là lớn nhất.
     * @param stdClass     $args Các tham số trong hàm wp_nav_menu().
     */
    public function end_el( &$output, $item, $dept = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
    }

}

