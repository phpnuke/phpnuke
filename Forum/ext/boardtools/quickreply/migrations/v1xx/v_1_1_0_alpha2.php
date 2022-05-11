<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_alpha2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-alpha2', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_1_0_alpha');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'forums' => array(
					'qr_form_type'    => array('TINT:2', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'    => array(
				$this->table_prefix . 'forums' => array('qr_form_type'),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_quickquote_button', 1)),
			array('config.add', array('qr_last_quote', 1)),

			// Update existing configs
			array('config.update', array('qr_version', '1.1.0-alpha2')),

			// Add permissions
			array('permission.add', array('f_qr_full_quote', false)),

			// Set permissions
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_qr_full_quote')),
			array('permission.permission_set', array('ROLE_FORUM_POLLS', 'f_qr_full_quote')),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', 'f_qr_full_quote')),
			array('permission.permission_set', array('ROLE_FORUM_NEW_MEMBER', 'f_qr_full_quote', 'role', false)),
		);
	}
}
