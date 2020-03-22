<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_dev extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-dev', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_0_2');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
					'qr_quicknick_string'    => array('BOOL', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'    => array(
				$this->table_prefix . 'users' => array('qr_quicknick_string'),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Update existing configs
			array('config.update', array('qr_version', '1.1.0-dev')),

			// Add configs
			array('config.add', array('qr_quicknick_string', '1')),
		);
	}
}
