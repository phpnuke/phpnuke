<?php
/**
 *
 * Board Announcements extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\boardannouncements\migrations\v10x;

/**
 * Migration stage 8: Add display on index only support
 */
class m8_index_only extends \phpbb\db\migration\migration
{
	/**
	 * {@inheritdoc}
	 */
	static public function depends_on()
	{
		return array('\phpbb\boardannouncements\migrations\v10x\m2_initial_data');
	}

	/**
	 * {@inheritdoc}
	 */
	public function effectively_installed()
	{
		return $this->config->offsetExists('board_announcements_index_only');
	}

	/**
	 * {@inheritdoc}
	 */
	public function update_data()
	{
		return array(
			array('config.add', array('board_announcements_index_only', 0)),
		);
	}
}
