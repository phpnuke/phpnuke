<?php

namespace rin\editor\migrations;

class bbcodedata extends \phpbb\db\migration\migration
{

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'addbbcode'))),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'removebbcode'))),
		);
	}

	public function removebbcode()
	{
		$bbcodedata = array('s', 'sub', 'sup', 'align=', 'font=', 'hr', 'youtube');

		$sql = 'DELETE FROM ' . $this->table_prefix . 'bbcodes WHERE ' . $this->db->sql_in_set('bbcode_tag', $bbcodedata);
		$this->db->sql_query($sql);
	}

	public function addbbcode()
	{
		$bbcodedata = array('s', 'sub', 'sup', 'align=', 'font=', 'hr', 'youtube');

		$sql = 'DELETE FROM ' . $this->table_prefix . 'bbcodes WHERE ' . $this->db->sql_in_set('bbcode_tag', $bbcodedata);
		$this->db->sql_query($sql);

		$sql = 'SELECT MAX(bbcode_id) AS max_id
					FROM ' . $this->table_prefix . 'bbcodes';
		$result = $this->db->sql_query($sql);

		$style_ids = 0;
		if ($styles_row = $this->db->sql_fetchrow())
		{
			$style_ids = $styles_row['max_id'];
		}
		$this->db->sql_freeresult($result);

		// Make sure we don't start too low
		if ($style_ids <= NUM_CORE_BBCODES)
		{
			$style_ids = NUM_CORE_BBCODES;
		}

		$phpbb_bbcodes = array(
			array( // row #1
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 's',
				'bbcode_helpline' => '',
				'display_on_posting' => 1,
				'bbcode_match' => '[s]{TEXT}[/s]',
				'bbcode_tpl' => '<span style="text-decoration: line-through;">{TEXT}</span>',
				'first_pass_match' => '!\\[s\\](.*?)\\[/s\\]!ies',
				'first_pass_replace' => '\'[s:$uid]\'.str_replace(array("\\r\\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${1}\')).\'[/s:$uid]\'',
				'second_pass_match' => '!\\[s:$uid\\](.*?)\\[/s:$uid\\]!s',
				'second_pass_replace' => '<span style="text-decoration: line-through;">${1}</span>'
			),
			array( // row #2
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'sub',
				'bbcode_helpline' => '',
				'display_on_posting' => 0,
				'bbcode_match' => '[sub]{TEXT}[/sub]',
				'bbcode_tpl' => '<sub>{TEXT}</sub>',
				'first_pass_match' => '!\\[sub\\](.*?)\\[/sub\\]!ies',
				'first_pass_replace' => '\'[sub:$uid]\'.str_replace(array("\\r\\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${1}\')).\'[/sub:$uid]\'',
				'second_pass_match' => '!\\[sub:$uid\\](.*?)\\[/sub:$uid\\]!s',
				'second_pass_replace' => '<sub>${1}</sub>'
			),
			array( // row #3
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'sup',
				'bbcode_helpline' => '',
				'display_on_posting' => 0,
				'bbcode_match' => '[sup]{TEXT}[/sup]',
				'bbcode_tpl' => '<sup>{TEXT}</sup>',
				'first_pass_match' => '!\\[sup\\](.*?)\\[/sup\\]!ies',
				'first_pass_replace' => '\'[sup:$uid]\'.str_replace(array("\\r\\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${1}\')).\'[/sup:$uid]\'',
				'second_pass_match' => '!\\[sup:$uid\\](.*?)\\[/sup:$uid\\]!s',
				'second_pass_replace' => '<sup>${1}</sup>'
			),
			array( // row #4
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'align=',
				'bbcode_helpline' => '',
				'display_on_posting' => 1,
				'bbcode_match' => '[align={IDENTIFIER}]{TEXT}[/align]',
				'bbcode_tpl' => '<div align="{IDENTIFIER}">{TEXT}</div>',
				'first_pass_match' => '!\\[align\\=(left|center|right|justify)\\](.*?)\\[/align\\]!ies',
				'first_pass_replace' => '\'[align=${1}:$uid]\'.str_replace(array("\\r\\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${2}\')).\'[/align:$uid]\'',
				'second_pass_match' => '!\\[align\\=(left|center|right|justify):$uid\\](.*?)\\[/align:$uid\\]!s',
				'second_pass_replace' => '<div align="${1}">${1}</div>'
			),
			array( // row #5
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'font=',
				'bbcode_helpline' => '',
				'display_on_posting' => 1,
				'bbcode_match' => '[font={SIMPLETEXT}]{TEXT}[/font]',
				'bbcode_tpl' => '<span style="font-family: {SIMPLETEXT};">{TEXT}</span>',
				'first_pass_match' => "!\\[font\\=([a-z0-9 ,\-_']+)\\](.*?)\\[/font\\]!ies",
				'first_pass_replace' => '\'[font=${1}:$uid]\'.str_replace(array("\\r\\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${2}\')).\'[/font:$uid]\'',
				'second_pass_match' => "!\\[font\\=([a-z0-9 ,\-_']+):".'$uid'."\\](.*?)\\[/font:".'$uid'."\\]!s",
				'second_pass_replace' => '<span style="font-family: ${1};">${2}</span>'
			),
			array( // row #6
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'hr',
				'bbcode_helpline' => '',
				'display_on_posting' => 1,
				'bbcode_match' => '[hr]',
				'bbcode_tpl' => '<hr />',
				'first_pass_match' => '!\\[hr\\]!i',
				'first_pass_replace' => '[hr:$uid]',
				'second_pass_match' => '!\\[hr:$uid\\]!s',
				'second_pass_replace' => '<hr />'
			),
			array( // row #7
				'bbcode_id' => ++$style_ids,
				'bbcode_tag' => 'youtube',
				'bbcode_helpline' => '',
				'display_on_posting' => 1,
				'bbcode_match' => '[youtube]{SIMPLETEXT}[/youtube]',
				'bbcode_tpl' => '<iframe width="560" height="315" src="//www.youtube.com/embed/{SIMPLETEXT}?html5=1" frameborder="0" allowfullscreen></iframe>',
				'first_pass_match' => '!\\[youtube\\]([a-zA-Z0-9-+.,_ ]+)\\[/youtube\\]!i',
				'first_pass_replace' => '[youtube:$uid]${1}[/youtube:$uid]',
				'second_pass_match' => '!\\[youtube:$uid\\]([a-zA-Z0-9-+.,_ ]+)\\[/youtube:$uid\\]!s',
				'second_pass_replace' => '<iframe width="560" height="315" src="//www.youtube.com/embed/${1}?html5=1" frameborder="0" allowfullscreen></iframe>'
			)
		);
		foreach ($phpbb_bbcodes as $eee)
		{
			$sql = 'INSERT INTO ' . $this->table_prefix . 'bbcodes' . $this->db->sql_build_array('INSERT', $eee);
			$this->db->sql_query($sql);
		}
	}
}
