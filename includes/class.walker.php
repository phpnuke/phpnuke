<?php

/**
 * Walker
 * 
 * Copyright (c) 2010 BarsMaster
 * e-mail: barsmaster@gmail.com, arthur.borisow@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @author BarsMaster
 * @copyright 2010
 * @version 1.1
 * @access public
 */
 
if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

abstract class Walker {

	private $_objects = false;

	public $_uniqueId;
	public $_parentUniqueId;
	
	public $has_children;
	
	public function start_lvl( &$output, $depth = 0, $args = array() ) {}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {}

	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {}

	public function end_el( &$output, $object, $depth = 0, $args = array() ) {}

	public function walk($elements, $uniqueIdName = 'id', $parentUniqueIdName = 'parentId', $max_depth = 0) {

		$this->_objects = (bool)is_object($elements);
		$this->_uniqueId = $uniqueIdName;
		$this->_parentUniqueId = $parentUniqueIdName;
		
		$args = array_slice(func_get_args(), 4);
		$output = '';
		
		//invalid parameter or nothing to walk
		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		// flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e )
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			return $output;
		}
		
		$parent_field = $this->_parentUniqueId;
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( empty( $e->$parent_field ) )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}
		
		if ( empty($top_level_elements) ) {

			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		foreach ( $top_level_elements as $e )
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
		
		if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans )
				foreach ( $orphans as $op )
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
		 }
		 
		 return $output;
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->_uniqueId;
		$id       = $element->$id_field;

		//display this element
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Backwards compatibility.
		}

		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array($this, 'start_el'), $cb_args);

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach ( $children_elements[ $id ] as $child ){

				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array($this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array($this, 'end_lvl'), $cb_args);
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array($this, 'end_el'), $cb_args);
	}
}

class Walker_nav_categories extends Walker
{

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent\t<".$args->list_type." class=\"sub-menu\">\n";
	}
		
	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\t</".$args->list_type.">\n";
	}
	
	public function start_el(&$output, $element, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';


		$classes = empty($element->classes) ? array() : (array) $element->classes;
		$classes[] = 'cat-item-' . $element->catid;
		
		$class_names = join(' ', array_filter($classes));
		$class_names = $class_names ? ' class="' . filter($class_names, "nohtml") . '"' : '';
		
		$id = $element->catid;
		$id_tag = $id ? ' data-id="' . intval($id) . '"' : '';

		$output .= $indent . '<li' . $id_tag . $class_names .'>';
		
		$cattext = $element->cattext;

		$before = sprintf( $args->before, $element->catid, $element->cattext);
		
		$item_output = $before . (isset($args->hide_content) && $args->hide_content ? '':$cattext) . $args->after;
		
		$output .= $item_output;
	}
	
	public function end_el(&$output, $element, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}

class Walker_nav_menus extends Walker
{

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent\t<".$args->list_type." class=\"".((isset($args->list_class) && $args->list_class != '') ? $args->list_class:"")."\">\n";
	}
		
	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\t</".$args->list_type.">\n";
	}
	
	public function start_el(&$output, $element, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		
		$classes	= empty($element->attributes->classes)	?'' : $element->attributes->classes;	
		$styles		= empty($element->attributes->styles)	?'' : $element->attributes->styles;	
		$xfn		= empty($element->attributes->xfn)		?'' : $element->attributes->xfn;	
		$target		= empty($element->attributes->target)	?'' : $element->attributes->target;

		$class_names = ' class="menu-item menu-item-'.$element->nid.' '.filter($classes, "nohtml").'"';
		$styles_codes = ($styles != '') ? ' style="'.$styles.'"':'';
		$xfn_rels_codes = ($xfn != '') ? ' rel="'.$xfn.'"':'';
		$idrel = ' id="menu-item-'.$element->nid.'"';		

		$id				= $element->nid;
		$title			= $element->title;
		$type			= $element->type;
		$part_id		= $element->part_id;
		$module			= $element->module;
		$url			= $element->url;
		
		if($type == 'categories' && $url == '' && $module != '')
		{
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($part_id, $args->nuke_categories[$module], "parent_id", "catname_url"))), "nohtml"), array("/"));
			$cat_link = category_link($module, $cat_title, $attrs=array(), 3);
			$url = end($cat_link);
		}
		
		$output .= $indent . '<li'.$class_names.$styles_codes.$idrel.'>';
		
		$link_before = sprintf($args->link_before, $url, $target);

		$item_output = $args->before . $link_before . $title . $args->link_after . $args->after;
		
		$output .= $item_output;
	}
	
	public function end_el(&$output, $element, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}

?>