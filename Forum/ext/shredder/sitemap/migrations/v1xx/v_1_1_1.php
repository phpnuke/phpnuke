<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\migrations\v1xx;

class v_1_1_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['sitemap_seo_version']) && version_compare($this->config['sitemap_seo_version'], '1.1.1', '>=');
	}

	static public function depends_on()
	{
		return array('\shredder\sitemap\migrations\v1xx\v_1_1_0');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('sitemap_seo_version', '1.1.1')),
		);
	}
}
