<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor
	* 
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\user $user
	* @param \phpbb\db\driver\driver $db
	* @param string $phpbb_root_path Root path
	* @param string $phpbb_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'			=> 'load_language_on_setup',
			'core.submit_post_end'			=> 'update_post_modified_time',
			'core.page_footer_after'		=> 'seo_return',
		);
	}

	/**
	* Load common files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'shredder/sitemap',
			'lang_set' => 'info_acp_seo_sitemap',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Update post modification time when posting or edit
	*
	* @return null
	* @access public
	*/
	public function update_post_modified_time($event)
	{
		$data = $event['data'];

		$sql = 'UPDATE ' . POSTS_TABLE . '
				SET post_modified = ' . (int) time() . '
			WHERE post_id = ' . (int) $data['post_id'];
		$this->db->sql_query($sql);
	}

	public function seo_return($event)
	{
		if (!defined('PHPBB_WORK_INFO'))
		{
			$this->template->assign_vars(array(
				'PHPBB_WORK_SITEMAP'	=> ($this->config['default_lang'] == 'ru') ? true : false,
			));

			define('PHPBB_WORK_INFO', 1);
		}
	}
}
