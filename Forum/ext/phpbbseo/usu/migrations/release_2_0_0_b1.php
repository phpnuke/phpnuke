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

class release_2_0_0_b1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		if (!empty($this->config['seo_usu_on']))
		{
			return $this->db_tools->sql_column_exists($this->table_prefix . 'topics', 'topic_url');
		}

		return false;
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\rc1');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				TOPICS_TABLE	=> array(
					'topic_url'	=> array('VCHAR:255', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				TOPICS_TABLE	=> array(
					'topic_url',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('seo_usu_on', 1)),
			array(
				'module.add',
				array(
					'acp',
					'',
					array(
						'module_langname'	=> 'ACP_CAT_PHPBB_SEO',
					),
				)
			),
			array(
				'module.add',
				array(
					'acp',
					'ACP_CAT_PHPBB_SEO',
					array(
						'module_langname'	=> 'ACP_MOD_REWRITE',
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
						'module_langname'	=> 'ACP_PHPBB_SEO_CLASS',
						'module_mode'		=> 'settings',
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
						'module_langname'	=> 'ACP_FORUM_URL',
						'module_mode'		=> 'forum_url',
						'module_auth'		=> 'ext_phpbbseo/usu && acl_a_board',
					),
				),
			),
			array(
				'module.add',
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
						'module_langname'	=> 'ACP_SEO_EXTENDED',
						'module_mode'		=> 'extended',
						'module_auth'		=> 'ext_phpbbseo/usu && acl_a_board',
					),
				)
			),
		);
	}
}
