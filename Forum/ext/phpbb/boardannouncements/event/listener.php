<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\event;

use phpbb\boardannouncements\acp\board_announcements_module;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\cache\driver\driver_interface $cache             Cache driver interface
	* @param \phpbb\config\config                 $config            Config object
	* @param \phpbb\config\db_text                $config_text       DB text object
	* @param \phpbb\controller\helper             $controller_helper Controller helper object
	* @param \phpbb\request\request               $request           Request object
	* @param \phpbb\template\template             $template          Template object
	* @param \phpbb\user                          $user              User object
	* @access public
	*/
	public function __construct(\phpbb\cache\driver\driver_interface $cache, \phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\controller\helper $controller_helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->cache = $cache;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->controller_helper = $controller_helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
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
			'core.page_header_after'	=> 'display_board_announcements',
		);
	}

	/**
	* Display board announcements
	*
	* @return void
	* @access public
	*/
	public function display_board_announcements()
	{
		// Do not continue if announcement has been disabled
		if (!$this->config['board_announcements_enable'])
		{
			return;
		}

		// Do not continue if board announcement is expired
		if ($this->config['board_announcements_expiry'] && $this->config['board_announcements_expiry'] < time())
		{
			$this->config->set('board_announcements_enable', 0);
			return;
		}

		// Do not continue if user is registered, but announcement is for guests only
		// This is to prevent newly registered users from seeing guest only announcements
		if ($this->user->data['user_id'] != ANONYMOUS && $this->config['board_announcements_users'] == board_announcements_module::GUESTS)
		{
			return;
		}

		// Get board announcement data from the cache
		$board_announcement_data = $this->cache->get('_board_announcement_data');

		if ($board_announcement_data === false)
		{
			// Get board announcement data from the config_text object
			$board_announcement_data = $this->config_text->get_array(array(
				'announcement_text',
				'announcement_uid',
				'announcement_bitfield',
				'announcement_options',
				'announcement_bgcolor',
				'announcement_timestamp',
			));

			// Cache board announcement data
			$this->cache->put('_board_announcement_data', $board_announcement_data);
		}

		// Get announcement cookie if one exists
		$cookie = $this->request->variable($this->config['cookie_name'] . '_baid', '', true, \phpbb\request\request_interface::COOKIE);

		// Do not continue if announcement has been dismissed
		if (!$this->user->data['board_announcements_status'] || $cookie == $board_announcement_data['announcement_timestamp'])
		{
			return;
		}

		// Prepare board announcement message for display
		$announcement_message = generate_text_for_display(
			$board_announcement_data['announcement_text'],
			$board_announcement_data['announcement_uid'],
			$board_announcement_data['announcement_bitfield'],
			$board_announcement_data['announcement_options']
		);

		// Add board announcements language file
		$this->user->add_lang_ext('phpbb/boardannouncements', 'boardannouncements');

		// Output board announcement to the template
		$this->template->assign_vars(array(
			'S_BOARD_ANNOUNCEMENT'			=> true,
			'S_BOARD_ANNOUNCEMENT_DISMISS'	=> (bool) $this->config['board_announcements_dismiss'],

			'BOARD_ANNOUNCEMENT'			=> $announcement_message,
			'BOARD_ANNOUNCEMENT_BGCOLOR'	=> $board_announcement_data['announcement_bgcolor'],

			'U_BOARD_ANNOUNCEMENT_CLOSE'	=> $this->controller_helper->route('phpbb_boardannouncements_controller', array(
				'hash' => generate_link_hash('close_boardannouncement')
			)),
		));
	}
}
