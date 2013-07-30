<?php

/**
 * Create HTML list of nav menu items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker
 */
class BinaryBootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu sub-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		if ( $args->has_children && $depth < 1 ) {
			$classes[] = 'dropdown';
		}

		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = $item->current ? 'active' : '';

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . '>';

		$atts = array();
		$atts['title'] = !empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = !empty( $item->target ) ? $item->target : '';
		$atts['rel'] = !empty( $item->xfn ) ? $item->xfn : '';
		$atts['href'] = !empty( $item->url ) ? $item->url : '';
		if ( $args->has_children && $depth < 1 ) {
			$atts['class'] = 'dropdown-toggle';
			$atts['data-toggle'] = 'dropdown';
			$atts['data-target'] = '#';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( !empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= ($args->has_children && $depth < 1) ? '<b class="caret"></b>' : '';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {

		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		//display this element
		if ( isset( $args[0] ) && is_array( $args[0] ) )
			$args[0]['has_children'] = !empty( $children_elements[$element->$id_field] );
		elseif ( is_object( $args[0] ) )
			$args[0]->has_children = (bool) (!empty( $children_elements[$element->$id_field] ) AND $depth != $max_depth - 1);

		$cb_args = array_merge( array(&$output, $element, $depth), $args );
		call_user_func_array( array($this, 'start_el'), $cb_args );

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[$id] ) ) {

			foreach ( $children_elements[$id] as $child ) {

				if ( !isset( $newlevel ) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args );
					call_user_func_array( array($this, 'start_lvl'), $cb_args );
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[$id] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args );
			call_user_func_array( array($this, 'end_lvl'), $cb_args );
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args );
		call_user_func_array( array($this, 'end_el'), $cb_args );
	}

}
