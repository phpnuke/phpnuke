<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v0xx;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.0', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v0xx\v_0_0_1');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_capslock_transfer', '0')),
			array('config.add', array('qr_ajax_submit', '1')),

			// Update existing configs
			array('config.update', array('qr_version', '0.1.0')),
		);
	}
}
