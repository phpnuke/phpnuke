<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v0xx;

class v_0_1_3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.3', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v0xx\v_0_1_2');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'install_bbcode_for_qr'))),

			// Add configs
			array('config.add', array('qr_attach', '0')),
			array('config.add', array('qr_show_subjects', '0')),
			array('config.add', array('qr_color_nickname', '1')),
			array('config.add', array('qr_show_button_translit', '0')),

			// Update existing configs
			array('config.update', array('qr_version', '0.1.3')),
		);
	}

	public function install_bbcode_for_qr()
	{
		// Load the acp_bbcode class
		$bbcode_tool = $this->load_class();

		$bbcode_data = $this->get_bbcode_data();

		foreach ($bbcode_data as $bbcode_name => $bbcode_array)
		{
			// Build the BBCodes
			$data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl']);
			$bbcode_array += $this->build_bbcode_array($data);

			$row_exists = $this->exist_bbcode($bbcode_name, $bbcode_array);
			$this->add_bbcode($row_exists, $bbcode_array);
		}
	}

	public function load_class()
	{
		if (!class_exists('acp_bbcodes'))
		{
			include($this->phpbb_root_path . 'includes/acp/acp_bbcodes.' . $this->php_ext);
		}
		return new \acp_bbcodes();
	}

	public function get_bbcode_data()
	{
		return array(
			'ref' => array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[ref]{TEXT}[/ref]',
				'bbcode_tpl'		=> '<span style="font-weight: bold;">{TEXT}</span>',
				'display_on_posting'=> 0,
			),
			'ref=' => array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[ref={COLOR}]{TEXT}[/ref]',
				'bbcode_tpl'		=> '<span style="font-weight: bold; color: {COLOR};">{TEXT}</span>',
				'display_on_posting'=> 0,
			),
			'post'	=> array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[post]{NUMBER}[/post]',
				'bbcode_tpl'		=> '<a href="./viewtopic.php?p={NUMBER}#p{NUMBER}"><span class="imageset icon_topic_latest" title="{L_QR_BBPOST}">{L_QR_BBPOST}</span></a>',
				'display_on_posting'=> 0,
			),
		);
	}

	public function build_bbcode_array($data)
	{
		return array(
				'bbcode_tag'			=> $data['bbcode_tag'],
				'first_pass_match'		=> $data['first_pass_match'],
				'first_pass_replace'	=> $data['first_pass_replace'],
				'second_pass_match'		=> $data['second_pass_match'],
				'second_pass_replace'	=> $data['second_pass_replace']
			);
	}

	public function exist_bbcode($bbcode_name, $bbcode_array)
	{
		$sql = 'SELECT bbcode_id
				FROM ' . $this->table_prefix . "bbcodes
				WHERE LOWER(bbcode_tag) = '" . strtolower($bbcode_name) . "'
				OR LOWER(bbcode_tag) = '" . strtolower($bbcode_array['bbcode_tag']) . "'";
		$result = $this->db->sql_query($sql);
		$row_exists = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row_exists;
	}

	public function add_bbcode($row_exists, $bbcode_array)
	{
		if ($row_exists)
		{
			// Update existing BBCode
			$this->update_bbcode($row_exists['bbcode_id'], $bbcode_array);
		}
		else
		{
			// Create new BBCode
			$this->insert_bbcode($bbcode_array);
		}
	}

	public function update_bbcode($bbcode_id, $bbcode_array)
	{
		$sql = 'UPDATE ' . $this->table_prefix . 'bbcodes
			SET ' . $this->db->sql_build_array('UPDATE', $bbcode_array) . '
			WHERE bbcode_id = ' . $bbcode_id;
		$this->db->sql_query($sql);
	}

	public function insert_bbcode($bbcode_array)
	{
		$bbcode_id = $this->get_next_bbcode_id();

		if ($bbcode_id <= BBCODE_LIMIT)
		{
			$bbcode_array['bbcode_id'] = (int) $bbcode_id;

			$this->db->sql_query('INSERT INTO ' . $this->table_prefix . 'bbcodes ' . $this->db->sql_build_array('INSERT', $bbcode_array));
		}
	}

	public function get_next_bbcode_id()
	{
		$sql = 'SELECT MAX(bbcode_id) AS max_bbcode_id
			FROM ' . $this->table_prefix . 'bbcodes';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $this->check_next_bbcode_id($row);
	}

	public function check_next_bbcode_id($row)
	{
		if ($row)
		{
			$bbcode_id = $row['max_bbcode_id'] + 1;

			// Make sure it is greater than the core BBCode ids...
			if ($bbcode_id <= NUM_CORE_BBCODES)
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}
		}
		else
		{
			$bbcode_id = NUM_CORE_BBCODES + 1;
		}

		return $bbcode_id;
	}
}
