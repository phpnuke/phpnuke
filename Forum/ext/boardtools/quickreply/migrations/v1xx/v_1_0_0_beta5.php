<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_0_0_beta5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.0.0-beta5', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_0_0_beta4');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_quicknick_ref', 1)),
			array('config.add', array('qr_quicknick_pm', 1)),
			array('config.add', array('qr_quickquote_link', 1)),
			array('config.add', array('qr_full_quote', 1)),
			array('config.add', array('qr_show_subjects_in_search', 0)),

			// Update existing configs
			array('config.update', array('qr_version', '1.0.0-beta5')),
		);
	}
}
