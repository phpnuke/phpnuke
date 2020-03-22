<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v2xx;

class v_2_0_0_dev2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.0.0-dev', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_1_0_beta1');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'update_bbcode_post'))),

			// Update existing configs
			array('config.update', array('qr_version', '2.0.0-dev2')),

			// Remove configs
			array('config.remove', array('qr_source_post')),
			array('config.remove', array('qr_quickquote_link')),
		);
	}

	public function update_bbcode_post()
	{
		if (!class_exists('boardtools\quickreply\migrations\v0xx\v_0_1_3'))
		{
			include($this->phpbb_root_path . 'ext/boardtools/quickreply/migrations/v0xx/v_0_1_3.' . $this->php_ext);
		}
		$bbcode_funcs = new \boardtools\quickreply\migrations\v0xx\v_0_1_3($this->config, $this->db, $this->db_tools, $this->phpbb_root_path, $this->php_ext, $this->table_prefix);

		// Load the acp_bbcode class
		$bbcode_tool = $bbcode_funcs->load_class();

		$bbcode_data = $this->get_bbcode_data();

		foreach ($bbcode_data as $bbcode_name => $bbcode_array)
		{
			// Build the BBCodes
			$data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl']);
			$bbcode_array += $bbcode_funcs->build_bbcode_array($data);

			$row_exists = $bbcode_funcs->exist_bbcode($bbcode_name, $bbcode_array);
			if ($row_exists)
			{
				// Update existing BBCode
				$bbcode_funcs->update_bbcode($row_exists['bbcode_id'], $bbcode_array);
			}
		}
	}

	private function get_bbcode_data()
	{
		return array(
			'post'	=> array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[post]{NUMBER}[/post]',
				'bbcode_tpl'		=> '<a href="./viewtopic.php?p={NUMBER}#p{NUMBER}" title="{L_QR_BBPOST}"><i class="icon fa-external-link-square fa-fw icon-lightgray icon-md"></i></a>',
				'display_on_posting'=> 0,
			),
		);
	}
}
