<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_alpha extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-alpha', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_1_0_dev');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'forums' => array(
					'qr_ajax_submit'    => array('BOOL', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'    => array(
				$this->table_prefix . 'forums' => array('qr_ajax_submit'),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Update existing configs
			array('config.update', array('qr_version', '1.1.0-alpha')),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_QUICKREPLY', array(
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_qn_module',
					'module_langname'	=> 'ACP_QUICKREPLY_QN_EXPLAIN',
					'module_mode'		=> 'config_quickreply_qn',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			))),
			array('module.add', array('acp', 'ACP_QUICKREPLY', array(
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_plugins_module',
					'module_langname'	=> 'ACP_QUICKREPLY_PLUGINS_EXPLAIN',
					'module_mode'		=> 'config_quickreply_plugins',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			))),
		);
	}
}
