<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

// v 0.8.6
class PhpFormBuilder {
	// Stores all form inputs
	private $inputs = array();
	// Stores all form attributes
	private $form = array();
	// Does this form have a submit value?
	private $has_submit = false;
	
	private $has_form = false;
	/**
	 * Constructor function to set form action and attributes
	 *
	 * @param string $action
	 * @param bool   $args
	 */
	function __construct( $action = '', $args = false ) {
		// Default form attributes
		$defaults = array(
			'action'       => $action,
			'method'       => 'post',
			'enctype'      => 'application/x-www-form-urlencoded',
			'class'        => array(),
			'id'           => '',
			'markup'       => 'html',
			'novalidate'   => false,
			'form_element' => true,
			'add_submit'   => true
		);
		// Merge with arguments, if present
		if ( $args ) {
			$settings = array_merge( $defaults, $args );
		} // Otherwise, use the defaults wholesale
		else {
			$settings = $defaults;
		}
		// Iterate through and save each option
		foreach ( $settings as $key => $val ) {
			// Try setting with user-passed setting
			// If not, try the default with the same key name
			if ( ! $this->set_att( $key, $val ) ) {
				$this->set_att( $key, $defaults[ $key ] );
			}
		}
	}
	/**
	 * Validate and set form
	 *
	 * @param string        $key A valid key; switch statement ensures validity
	 * @param string | bool $val A valid value; validated for each key
	 *
	 * @return bool
	 */
	function set_att( $key, $val ) {
		switch ( $key ) :
			case 'action':
				break;
			case 'method':
				if ( ! in_array( $val, array( 'post', 'get' ) ) ) {
					return false;
				}
				break;
			case 'enctype':
				if ( ! in_array( $val, array( 'application/x-www-form-urlencoded', 'multipart/form-data' ) ) ) {
					return false;
				}
				break;
			case 'markup':
				if ( ! in_array( $val, array( 'html', 'xhtml' ) ) ) {
					return false;
				}
				break;
			case 'class':
			case 'id':
				if ( ! $this->_check_valid_attr( $val ) ) {
					return false;
				}
				break;
			case 'novalidate':
			case 'form_element':
			case 'add_submit':
				if ( ! is_bool( $val ) ) {
					return false;
				}
				break;
			default:
				return false;
		endswitch;
		$this->form[ $key ] = $val;
		return true;
	}
	/**
	 * Add an input field to the form for outputting later
	 *
	 * @param string $label
	 * @param string $args
	 * @param string $slug
	 */
	function add_input( $label, $args = '', $slug = '' ) {
		if ( empty( $args ) ) {
			$args = array();
		}
		// Create a valid id or class attribute
		if ( empty( $slug ) ) {
			$slug = $this->_make_slug( $label );
		}
		$defaults = array(
			'type'				=> 'text',
			'name'				=> $slug,
			'id'				=> $slug,
			'label'				=> $label,
			'value'				=> '',
			'placeholder'		=> '',
			'class'				=> array(),
			'attr'				=> array(),
			'min'				=> '',
			'max'				=> '',
			'step'				=> '',
			'autofocus'			=> false,
			'checked'			=> false,
			'selected'			=> false,
			'required'			=> false,
			'add_label'			=> true,
			'options'			=> array(),
			'wrap_tag'			=> 'div',
			'wrap_class'		=> array( 'form_field_wrap' ),
			'wrap_id'			=> '',
			'wrap_style'		=> '',
			'before_html'		=> '',
			'after_html'		=> '',
			'request_populate'	=> true
		);
		// Combined defaults and arguments
		// Arguments override defaults
		$args                  = array_merge( $defaults, $args );
		$this->inputs[ $slug ] = $args;
	}
	/**
	 * Add multiple inputs to the input queue
	 *
	 * @param $arr
	 *
	 * @return bool
	 */
	function add_inputs( $arr ) {
		if ( ! is_array( $arr ) ) {
			return false;
		}
		foreach ( $arr as $key => $field ) {
			$this->add_input(
				$field['label'], isset( $field['opts'] ) ? $field['opts'] : '',
				isset( $key ) ? $key : ''
			);
		}
		return true;
	}
	/**
	 * Build the HTML for the form based on the input queue
	 *
	 * @param bool $echo Should the HTML be echoed or returned?
	 *
	 * @return string
	 */
	function build_form($request_data, $echo = true) {
	
		$output = '';
		if ( $this->form['form_element'] ) {
			$output .= '<form method="' . $this->form['method'] . '"';
			if ( ! empty( $this->form['enctype'] ) ) {
				$output .= ' enctype="' . $this->form['enctype'] . '"';
			}
			if ( ! empty( $this->form['action'] ) ) {
				$output .= ' action="' . $this->form['action'] . '"';
			}
			if ( ! empty( $this->form['id'] ) ) {
				$output .= ' id="' . $this->form['id'] . '"';
			}
			if ( count( $this->form['class'] ) > 0 ) {
				$output .= $this->_output_classes( $this->form['class'] );
			}
			if ( $this->form['novalidate'] ) {
				$output .= ' novalidate';
			}
			$output .= '>';
		}
		
		// Iterate through the input queue and add input HTML
		foreach ( $this->inputs as $filed_key => $val ) :
			$min_max_range = $element = $end = $attr = $field = $label_html = '';
			// Automatic population of values using $request data
			if ( $val['request_populate'] && isset( $request_data[ $filed_key ] ) ) {
				// Can this field be populated directly?
				if ( ! in_array( $val['type'], array( 'html', 'title', 'radio', 'checkbox', 'select', 'submit' ) ) ) {
					$val['value'] = $request_data[ $filed_key ];
				}
			}
			// Automatic population for checkboxes and radios
			if (
				$val['request_populate'] &&
				( $val['type'] == 'radio' || $val['type'] == 'checkbox' ) &&
				empty( $val['options'] )
			) {
				$val['checked'] = (isset( $request_data[ $filed_key ]) && $val['value'] == $request_data[ $filed_key ] ) ? true : $val['checked'];
			}
			$end = '';
			switch ( $val['type'] ) {
				case 'html':
					$element = '';
					if($val['label'] != ''){
						$end     .= $val['label'];
					}
					break;
				case 'title':
					$element = '';
					if($val['label'] != ''){
						$end     .= '
						<h3>' . $val['label'] . '</h3>';
					}
					break;
				case 'textarea':
					$element = 'textarea';
					$end     .= '>' . $val['value'] . '</textarea>';
					break;
				case 'select':
					$element = 'select';
					$end     .= '>';
					foreach ( $val['options'] as $opt ) {
						$opt_id = $this->_make_slug($opt[0]);
						$opt_value = $opt[1];
						$opt_text = $opt[2];
						$opt_attrs = isset($opt['attrs']) ? $opt['attrs']:array();
						$opt_insert = '';
						if (
							// Is this field set to automatically populate?
							$val['request_populate'] &&
							// Do we have request data to use?
							isset( $request_data[ $filed_key ] ) &&
							// Are we currently outputting the selected value?
							$request_data[ $filed_key ] == $opt_value
						) {
							$opt_insert = ' selected';
						// Does the field have a default selected value?
						} else if ( $val['selected'] === $opt_value ) {
							$opt_insert = ' selected';
						}
						$end .= '<option id="'.$opt_id.'" value="' . $opt_value . '"' . $opt_insert . '>' . $opt_text . '</option>';
					}
					$end .= '</select>';
					break;
				case 'radio':
				case 'checkbox':
					// Special case for multiple check boxes
					if ( count( $val['options'] ) > 0 ) :
						$element = '';
						foreach ( $val['options'] as $opt ) {
							$opt_id = $this->_make_slug($opt[0]);
							$opt_value = $opt[1];
							$opt_attrs = isset($opt['attrs']) ? $opt['attrs']:array();
						
							$end .= sprintf(
								'<input type="%s" name="%s" value="%s" '.(($opt_id != '') ? 'id="%s"':'%s').'',
								$val['type'],
								$val['name'],
								$opt_value,
								$opt_id
							);
							
							if (
								// Is this field set to automatically populate?
								$val['request_populate'] &&
								// Do we have request data to use?
								isset( $request_data[ $filed_key ] ) &&
								// Is the selected item(s) in the $request_data data?
								$request_data[ $filed_key ] == $opt_value
							) {
								$end .= ' checked';
							}
							if(!empty($opt_attrs))
							{
								foreach($opt_attrs as $opt_attr_key => $opt_attr_val)
									$end .= ' '.$opt_attr_key.'="'.((defined($opt_attr_val)) ? constant($opt_attr_val):$opt_attr_val).'"';
							}
							$class = $this->_output_classes( $val['class'] );
							$end .= $class;
							$end .= $this->field_close();
							if($val['label'] != ''){
								$end .= ($val['add_label'] ) ? ' <label for="' . $opt_id . '">' . $opt[1] . '</label>':' ' . $opt[1] . ' &nbsp;';
							}
						}
						//$label_html = ($val['label'] != '') ?'<div class="checkbox_header">' . $val['label'] . '</div>':'';
						break;
					endif;
				// Used for all text fields (text, email, url, etc), single radios, single checkboxes, and submit
				default :
					$element = 'input';
					$end .= ' type="' . $val['type'] . '" value="' . $val['value'] . '"';
					$end .= $val['checked'] ? ' checked' : '';
					$end .= $this->field_close();
					break;
			}
			// Added a submit button, no need to auto-add one
			if ( $val['type'] === 'submit' ) {
				$this->has_submit = true;
			}
			// Special number values for range and number types
			if ( $val['type'] === 'range' || $val['type'] === 'number' ) {
				$min_max_range .= ! empty( $val['min'] ) ? ' min="' . $val['min'] . '"' : '';
				$min_max_range .= ! empty( $val['max'] ) ? ' max="' . $val['max'] . '"' : '';
				$min_max_range .= ! empty( $val['step'] ) ? ' step="' . $val['step'] . '"' : '';
			}
			// Add an ID field, if one is present
			$id = ! empty( $val['id'] ) ? ' id="' . $val['id'] . '"' : '';
			// Output classes
			$class = $this->_output_classes( $val['class'] );
			// Special HTML5 fields, if set
			$attr .= $val['autofocus'] ? ' autofocus' : '';
			$attr .= $val['checked'] ? ' checked' : '';
			$attr .= $val['required'] ? ' required' : '';
			if(!empty($val['attrs']))
			{
				foreach($val['attrs'] as $opt_attr_key => $opt_attr_val)
					$attr .= ' '.$opt_attr_key.'="'.((defined($opt_attr_val)) ? constant($opt_attr_val):$opt_attr_val).'"';
			}
			
			// Build the label
			if ( ! empty( $label_html ) ) {
				$field .= $label_html;
			} elseif ( $val['add_label'] && ! in_array( $val['type'], array( 'hidden', 'submit', 'title', 'html' ) ) && $val['label'] != '') {
				if ( $val['required'] ) {
					$val['label'] .= ' <strong>*</strong>';
				}
				$field .= '<label for="' . $val['id'] . '">' . $val['label'] . '</label>';
			}
			// An $element was set in the $val['type'] switch statement above so use that
			if ( ! empty( $element ) ) {
				if ( $val['type'] === 'checkbox' ) {
					$field = '
					<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $attr . $end .
					         $field;
				} else {
					$field .= '
					<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $attr . $end;
				}
			// Not a form element
			} else {
				$field .= $end;
			}
			// Parse and create wrap, if needed
			if ( $val['type'] != 'hidden' && $val['type'] != 'html' ) :
				$wrap_before = $val['before_html'];
				if ( ! empty( $val['wrap_tag'] ) ) {
					$wrap_before .= '<' . $val['wrap_tag'];
					$wrap_before .= count( $val['wrap_class'] ) > 0 ? $this->_output_classes( $val['wrap_class'] ) : '';
					$wrap_before .= ! empty( $val['wrap_style'] ) ? ' style="' . $val['wrap_style'] . '"' : '';
					$wrap_before .= ! empty( $val['wrap_id'] ) ? ' id="' . $val['wrap_id'] . '"' : '';
					$wrap_before .= '>';
				}
				$wrap_after = $val['after_html'];
				if ( ! empty( $val['wrap_tag'] ) ) {
					$wrap_after = '</' . $val['wrap_tag'] . '>' . $wrap_after;
				}
				$output .= $wrap_before . $field . $wrap_after;
			else :
				$output .= $field;
			endif;
		endforeach;
		// Auto-add submit button
		if ( ! $this->has_submit && $this->form['add_submit'] ) {
			$output .= '<div class="form_field_wrap"><input type="submit" value="Submit" name="submit"></div>';
		}
		// Close the form tag if one was added
		if ( $this->form['form_element'] ) {
			$output .= '</form>';
		}
		// Output or return?
		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
	/* clear inputs */
	function clear_form() {
		$this->inputs = array();
	}
	// Easy way to auto-close fields, if necessary
	function field_close() {
		return $this->form['markup'] === 'xhtml' ? ' />' : '>';
	}
	// Validates id and class attributes
	// TODO: actually validate these things
	private function _check_valid_attr( $string ) {
		$result = true;
		// Check $name for correct characters
		// "^[a-zA-Z0-9_-]*$"
		return $result;
	}
	// Create a slug from a label name
	private function _make_slug( $string ) {
		$result = '';
		$result = str_replace( '"', '', $string );
		$result = str_replace( "'", '', $result );
		$result = str_replace( '_', '-', $result );
		$result = preg_replace( '~[\W\s]~', '-', $result );
		$result = strtolower( $result );
		return $result;
	}
	// Parses and builds the classes in multiple places
	private function _output_classes( $classes ) {
		$output = '';
		
		if ( is_array( $classes ) && count( $classes ) > 0 ) {
			$output .= ' class="';
			foreach ( $classes as $class ) {
				$output .= $class . ' ';
			}
			$output .= '"';
		} else if ( is_string( $classes ) ) {
			$output .= ' class="' . $classes . '"';
		}
		return $output;
	}
}


//example
/*
require_once( INCLUDE_PATH.'/class.form-builder.php' );

//Create a new instance
//Pass in a URL to set the action
$form = new PhpFormBuilder();

//Form attributes are modified with the set_att function.
//First argument is the setting
//Second argument is the value
// Add a new form action
$new_form->set_att('action', 'http://submit-here.com');

// Change the submit method
$new_form->set_att('method', 'get');

// Change the enctype
$new_form->set_att('enctype', 'multipart/form-data');

// Can be set to 'html' or 'xhtml'
$new_form->set_att('markup', 'xhtml');

// Classes are added as an array
$new_form->set_att('class', array());

// Add an id to the form
$new_form->set_att('id', 'xhtml');

// Adds the HTML5 "novalidate" attribute
$new_form->set_att('novalidate', true);

// Adds a WordPress nonce field using the string being passed
$new_form->set_att('add_nonce', 'build_a_nonce_using_this');

// Adds a blank, hidden text field for spam control
$new_form->set_att('add_honeypot', true);

// Wraps the inputs with a form element
$new_form->set_att('form_element', true);

// If no submit type is added, add one automatically
$new_form->set_att('form_element', true);

//Uss add_input to create form fields
//First argument is the name
//Second argument is an array of arguments for the field
//Third argument is an alternative name field, if needed

$form->add_input( 'Name', array(
	'request_populate' => false
), 'contact_name' );
$form->add_input( 'Email', array(
	'type' => 'email',
	'class' => array( 'class_1', 'class_2', 'class_3' )
), 'contact_email' );
$form->add_input( 'Filez', array(
	'type' => 'file'
), 'filez_here' );
$form->add_input( 'Should we call you?', array(
	'type'  => 'checkbox',
	'value' => 1
) );
$form->add_input( 'True or false', array(
	'type'    => 'radio',
	'checked' => false,
	'value'   => 1
) );
$form->add_input( 'Reason for contacting', array(
	'type'    => 'checkbox',
	'options' => array(
		'say_hi'     => 'Just saying hi!',
		'complain'   => 'I have a bone to pick',
		'offer_gift' => 'I\'d like to give you something neat',
	)
) );
$form->add_input( 'Bad Headline', array(
	'type'    => 'radio',
	'options' => array(
		'say_hi_2'     => 'Just saying hi! 2',
		'complain_2'   => 'I have a bone to pick 2',
		'offer_gift_2' => 'I\'d like to give you something neat 2',
	)
) );
$form->add_input( 'Reason for contact', array(
	'type'    => 'select',
	'options' => array(
		''           => 'Select...',
		'say_hi'     => 'Just saying hi!',
		'complain'   => 'I have a bone to pick',
		'offer_gift' => 'I\'d like to give you something neat',
	)
) );
$form->add_input( 'Question or comment', array(
	'required' => true,
	'type'     => 'textarea',
	'value'    => 'Type away!'
) );
$form->add_inputs( array(
	array( 'Field 1' ),
	array( 'Field 2' ),
	array( 'Field 3' )
) );

//Create the form
$form->build_form();
*/

?>