<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v2xx;

use phpbb\textreparser\manager;
use phpbb\textreparser\reparser_interface;

class v_2_0_0_beta2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.0.0-beta2', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v2xx\v_2_0_0_beta');
	}

	public function update_data()
	{
		return array(
			// Update existing configs
			array('config.update', array('qr_version', '2.0.0-beta2')),

			array('custom', array(array($this, 'update_bbcode_ref'))),
		);
	}

	public function update_bbcode_ref()
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

			// Update or Add BBCode
			$bbcode_funcs->add_bbcode($row_exists, $bbcode_array);
		}
	}

	private function get_bbcode_data()
	{
		return array(
			'ref=' => array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[ref={COLOR;optional}]{TEXT}[/ref]',
				'bbcode_tpl'		=> '
					<xsl:choose>
						<xsl:when test="@*">
							<span style="font-weight: bold; color: {COLOR};">{TEXT}</span>
						</xsl:when>
						<xsl:otherwise>
							<span style="font-weight: bold;">{TEXT}</span>
						</xsl:otherwise>
					</xsl:choose>',
				'display_on_posting'=> 0,
			),
		);
	}
}
