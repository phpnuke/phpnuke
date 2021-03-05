<?php

$nuke_meta_keys_parts['Articles2'] = array(
	"is_album" => array(
		'label'	=> 'is album ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>آلبوم</th><td>',
						'after_html'  => '</td></tr>',
						'id'  => 'is_album',
						'name'  => 'article_fields[meta_fields][is_album]',
						'class'  => 'styled',
						'type'  => 'checkbox',
						'label'  => '',
						'value' => 1,
						'attrs'  => array('data-label'=> "is album"),
					),
	),
	"is_coming" => array(
		'label'	=> 'is comming ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>بزودي</th><td>',
						'after_html'  => '</td></tr>',
						'id'  => 'is_coming',
						'name'  => 'article_fields[meta_fields][is_coming]',
						'class'  => 'styled',
						'type'  => 'checkbox',
						'label'  => '',
						'value' => 1,
						'attrs'  => array(),
					),
	),
	"is_filter" => array(
		'label'	=> 'is filter ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>فيلتر</th><td>',
						'after_html'  => '</td></tr>',
						'id'  => 'is_filter',
						'name'  => 'article_fields[meta_fields][is_filter]',
						'class'  => 'styled',
						'type'  => 'checkbox',
						'label'  => '',
						'value' => 1,
						'attrs'  => array(),
					),
	),
	"send_to_top" => array(
		'label'	=> 'ارسال به بالا ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>ارسال به بالا</th><td>',
						'after_html'  => '</td></tr>',
						'id'  => 'send_to_top',
						'name'  => 'article_fields[send_to_top]',
						'class'  => 'styled',
						'type'  => 'checkbox',
						'label'  => '',
						'value' => 1,
						'attrs'  => array(),
					),
		'php_before'	=>	'',
		'php_after'	=> 'unset($items[\'send_to_top\']);$items[\'time\'] = _NOWTIME;$items[\'status\'] = \'publish\';',
		'php_load'	=>	'',
	),
	"is_irani" => array(
		'label'	=> 'is iranian ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>is iranian ?</th><td>',
						'after_html'  => '</td></tr>',
						'name'  => 'article_fields[meta_fields][is_irani]',
						'class'  => 'is-irani styled',
						'type'  => 'radio',
						'label'  => '',
						'value' => 1,
						'options'   => array(
							array("is-irani", "1", "attrs" => array('data-label' => "_YES")),
							array("not-is-irani", "0", "attrs" => array('data-label' => "_NO"))
						)
					),
	),
	"brand" => array(
		'label'	=> 'brand ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>brand ?</th><td>',
						'after_html'  => '</td></tr>',
						'name'  => 'article_fields[meta_fields][brand]',
						'class'  => 'inp-form',
						'label'  => '',
						'id'  => 'brand',
					),
	),
	"size" => array(
		'label'	=> 'size ?',
		'opts'	=>	array(
						'wrap_tag'  => '',
						'before_html'  => '<tr><th>size ?</th><td>',
						'after_html'  => '</td></tr>',
						'name'  => 'article_fields[meta_fields][size]',
						'class'  => 'styledselect-select',
						'type'  => 'select',
						'label'  => '',
						'id'  => 'size',
						'options'   => array(
							array("", "3*4", "3*4", "attrs" => array()),
							array("", "4*6", "4*6", "attrs" => array())
						)
					),
	)
);

?>