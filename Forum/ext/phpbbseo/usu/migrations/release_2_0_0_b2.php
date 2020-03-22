<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\migrations;

class release_2_0_0_b2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		if (!empty($this->config['seo_usu_version']))
		{
			return version_compare($this->config['seo_usu_version'], '2.0.0-b2', '>=');
		}

		return false;
	}

	static public function depends_on()
	{
		return array('\phpbbseo\usu\migrations\release_2_0_0_b1');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('seo_usu_version', '2.0.0-b2')),
			array(
				'module.remove',
				array(
					'acp',
					'ACP_MOD_REWRITE',
					array(
						'module_basename'	=> '\phpbbseo\usu\acp\usu',
						'module_langname'	=> 'ACP_HTACCESS',
						'module_mode'		=> 'htaccess',
						'module_auth'		=> 'ext_phpbbseo/usu && acl_a_board',
					),
				)
			),
			array(
				'module.add',
				array(
					'acp',
					'ACP_MOD_REWRITE',
					array(
						'module_basename'	=> '\phpbbseo\usu\acp\usu',
						'module_langname'	=> 'ACP_REWRITE_CONF',
						'module_mode'		=> 'server',
						'module_auth'		=> 'ext_phpbbseo/usu && acl_a_board',
					),
				)
			),
			array(
				'module.add',
				array(
					'acp',
					'ACP_MOD_REWRITE',
					array(
						'module_basename'	=> '\phpbbseo\usu\acp\usu',
						'module_langname'	=> 'ACP_SYNC_URL',
						'module_mode'		=> 'sync_url',
						'module_auth'		=> 'ext_phpbbseo/usu && acl_a_board',
					),
				)
			),
		);
	}
}
