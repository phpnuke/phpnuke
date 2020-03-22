<?php
/**
 *
 * @package SCSS Compiler
 * @author Arty (Vjacheslav Trushkin) cyberalien@gmail.com
 * @copyright (c) 2015 artodia.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace arty\scss\migrations\v100;

class install_v100 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['scss_version']) && version_compare($this->config['scss_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\gold');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('scss_version', '1.0.0')),

			array('module.add', array(
				'acp',
				'ACP_STYLE_MANAGEMENT',
				array(
					'module_basename'	=> '\arty\scss\acp\scss_module',
					'auth'				=> 'acl_a_styles',
					'modes'				=> array('main'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('scss_version')),

			array('module.remove', array(
				'acp',
				'ACP_STYLE_MANAGEMENT',
				array(
					'module_basename'	=> '\arty\scss\acp\scss_module',
				),
			)),
		);
	}
}
