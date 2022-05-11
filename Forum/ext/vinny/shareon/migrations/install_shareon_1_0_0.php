<?php
/**
*
* Share On extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\shareon\migrations;

class install_shareon_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['shareon_version']) && version_compare($this->config['shareon_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('so_status', 1)),
			array('config.add', array('so_type', 1)),
			array('config.add', array('so_facebook', 1)),
			array('config.add', array('so_twitter', 1)),
			array('config.add', array('so_tuenti', 1)),
			array('config.add', array('so_sonico', 1)),
			array('config.add', array('so_friendfeed', 1)),
			array('config.add', array('so_digg', 1)),
			array('config.add', array('so_delicious', 1)),
			array('config.add', array('so_vk', 1)),
			array('config.add', array('so_tumblr', 1)),
			array('config.add', array('so_google', 1)),
			array('config.add', array('so_reddit', 1)),

			array('config.add', array('shareon_version', '1.0.0')),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'SO_ACP'
			)),
			array('module.add', array(
				'acp',
				'SO_ACP',
				array(
					'module_basename'	=> '\vinny\shareon\acp\shareon_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
