<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\controller;

class controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\config\config                $config         Config object
	* @param \phpbb\config\db_text               $config_text    DB text object
	* @param \phpbb\db\driver\driver_interface   $db             Database object
	* @param \phpbb\request\request              $request        Request object
	* @param \phpbb\user                         $user           User object
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\user $user)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->db = $db;
		$this->request = $request;
		$this->user = $user;
	}

	/**
	* Board Announcements controller accessed from the URL /boardannouncements/close
	*
	* @throws \phpbb\exception\http_exception An http exception
	* @return \Symfony\Component\HttpFoundation\JsonResponse A Symfony JSON Response object
	* @access public
	*/
	public function close_announcement()
	{
		// Check the link hash to protect against CSRF/XSRF attacks
		if (!$this->config['board_announcements_dismiss'] || !check_link_hash($this->request->variable('hash', ''), 'close_boardannouncement'))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		// Set a cookie
		$response = $this->set_board_announcement_cookie();

		// Close the announcement for registered users
		if ($this->user->data['is_registered'])
		{
			$response = $this->update_board_announcement_status();
		}

		// Send a JSON response if an AJAX request was used
		if ($this->request->is_ajax())
		{
			return new \Symfony\Component\HttpFoundation\JsonResponse(array(
				'success' => $response,
			));
		}

		// Redirect the user back to their last viewed page (non-AJAX requests)
		$redirect = $this->request->variable('redirect', $this->user->data['session_page']);
		$redirect = reapply_sid($redirect);
		redirect($redirect);

		// We shouldn't get here, but throw an http exception just in case
		throw new \phpbb\exception\http_exception(500, 'GENERAL_ERROR');
	}

	/**
	* Set a cookie to keep an announcement closed
	*
	* @return bool True
	* @access protected
	*/
	protected function set_board_announcement_cookie()
	{
		// Get board announcement data from the DB text object
		$announcement_timestamp = $this->config_text->get('announcement_timestamp');

		// Store the announcement timestamp/id in a cookie with a 1 year expiration
		$this->user->set_cookie('baid', $announcement_timestamp, strtotime('+1 year'));

		return true;
	}

	/**
	* Close an announcement for a registered user
	*
	* @return bool True if successful, false otherwise
	* @access protected
	*/
	protected function update_board_announcement_status()
	{
		// Set announcement status to 0 for registered user
		$sql = 'UPDATE ' . USERS_TABLE . '
			SET board_announcements_status = 0
			WHERE user_id = ' . (int) $this->user->data['user_id'] . '
			AND user_type <> ' . USER_IGNORE;
		$this->db->sql_query($sql);

		return (bool) $this->db->sql_affectedrows();
	}
}
