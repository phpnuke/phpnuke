<?php
/**
*
* Thanks For Posts extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace gfksx\thanksforposts\migrations;

class v_1_2_5 extends \phpbb\db\migration\migration
{
	protected $thanks_table_exists;
	protected $poster_id_field_exists;

	public function effectively_installed()
	{
		return isset($this->config['thanks_only_first_post']);
	}

	static public function depends_on()
	{
		return array('\gfksx\thanksforposts\migrations\v_0_4_0');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('remove_thanks', 1)),
			array('config.add', array('thanks_postlist_view', 1)),
			array('config.add', array('thanks_profilelist_view', 1)),
			array('config.add', array('thanks_counters_view', 1)),
			array('config.add', array('thanks_number', 100)),
			array('config.add', array('thanks_info_page', 1)),
			array('config.add', array('thanks_only_first_post', 0)),

			// Add permissions
			array('permission.add', array('f_thanks', false)),
			array('permission.add', array('u_viewthanks', true)),

			// Add permissions sets
			array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_viewthanks', 'role', true)),
			array('permission.permission_set', array('REGISTERED', 'u_viewthanks', 'group', true)),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', 'f_thanks', 'role', true)),
		);
	}
}
