<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_0_0_beta4 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.0.0-beta4', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_0_0_beta3');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_allow_for_guests', 0)),

			// Update existing configs
			array('config.update', array('qr_version', '1.0.0-beta4')),
		);
	}
}
