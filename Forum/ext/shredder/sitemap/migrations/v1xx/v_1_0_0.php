<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\migrations\v1xx;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['sitemap_seo_version']) && version_compare($this->config['sitemap_seo_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'posts'	=> array(
					'post_modified'	=> array('UINT:11', 0, 'after' => 'post_delete_user'),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'posts'	=> array(
					'post_modified',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config_text.add', array('sitemap_seo_excluded', '')),
			array('config.add', array('sitemap_seo_cache', '24')),
			array('config.add', array('sitemap_seo_prior_0', '0.5')),
			array('config.add', array('sitemap_seo_prior_1', '0.6')),
			array('config.add', array('sitemap_seo_prior_2', '0.7')),
			array('config.add', array('sitemap_seo_prior_3', '0.8')),
			array('config.add', array('sitemap_seo_prior_4', '0.7')),
			array('config.add', array('sitemap_seo_prior_f', '0.9')),
			array('config.add', array('sitemap_seo_freq_0', 'daily')),
			array('config.add', array('sitemap_seo_freq_1', 'daily')),
			array('config.add', array('sitemap_seo_freq_2', 'weekly')),
			array('config.add', array('sitemap_seo_freq_3', 'weekly')),
			array('config.add', array('sitemap_seo_freq_4', 'weekly')),
			array('config.add', array('sitemap_seo_freq_f', 'weekly')),
			array('config.add', array('sitemap_seo_url_limit', '50000')),
			array('config.add', array('sitemap_seo_url_count', '')),

			// Current version
			array('config.add', array('sitemap_seo_version', '1.0.0')),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'SEOMAP')),
			array('module.add', array('acp', 'SEOMAP', array(
					'module_basename'	=> '\shredder\sitemap\acp\sitemap_module',
					'module_langname'	=> 'SEOMAP_SETTINGS',
					'module_mode'		=> 'settings',
					'module_auth'		=> 'ext_shredder/sitemap && acl_a_board',
			))),
		);
	}
}
