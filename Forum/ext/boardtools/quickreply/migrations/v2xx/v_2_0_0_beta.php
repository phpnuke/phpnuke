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

class v_2_0_0_beta extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.0.0-beta', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v2xx\v_2_0_0_alpha');
	}

	public function update_data()
	{
		return array(
			// Update existing configs
			array('config.update', array('qr_version', '2.0.0-beta')),

			array('custom', array(array($this, 'reparse_posts'))),
		);
	}

	public function reparse_posts()
	{
		/** @var manager $reparser_manager */
		$reparser_manager = $this->container->get('text_reparser.manager');

		// Reparse only posts in topics and PMs - ref BBCode should not be used anywhere else.
		$reparsers_list = array(
			'text_reparser.pm_text',
			'text_reparser.post_text',
		);

		// Re-initialize reparsers.
		foreach ($reparsers_list as $name)
		{
			/** @var reparser_interface $reparser */
			$reparser = $this->container->get($name);

			$reparser_manager->update_resume_data($name, 1, $reparser->get_max_id(), 100);
		}
	}
}
