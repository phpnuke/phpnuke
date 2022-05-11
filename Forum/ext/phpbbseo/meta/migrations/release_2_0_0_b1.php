<?php
/**
*
* @package Dynamic Meta Tags phpBB SEO
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\meta\migrations;

class release_2_0_0_b1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !empty($this->config['seo_meta_on']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\rc1');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('seo_meta_on', 1)),
		);
	}
}
