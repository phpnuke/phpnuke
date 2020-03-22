<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\acp;

class board_announcements_module
{
	const ALL = 0;
	const MEMBERS = 1;
	const GUESTS = 2;
	const DATE_FORMAT = 'Y-m-d H:i';

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $u_action;

	public function main()
	{
		global $phpbb_container;

		$this->cache = $phpbb_container->get('cache.driver');
		$this->config = $phpbb_container->get('config');
		$this->config_text = $phpbb_container->get('config_text');
		$this->db = $phpbb_container->get('dbal.conn');
		$this->log = $phpbb_container->get('log');
		$this->request = $phpbb_container->get('request');
		$this->template = $phpbb_container->get('template');
		$this->user = $phpbb_container->get('user');
		$this->phpbb_root_path = $phpbb_container->getParameter('core.root_path');
		$this->php_ext = $phpbb_container->getParameter('core.php_ext');

		// Add the posting lang file needed by BBCodes
		$this->user->add_lang(array('posting'));

		// Add the board announcements ACP lang file
		$this->user->add_lang_ext('phpbb/boardannouncements', 'boardannouncements_acp');

		// Load a template from adm/style for our ACP page
		$this->tpl_name = 'board_announcements';

		// Set the page title for our ACP page
		$this->page_title = 'ACP_BOARD_ANNOUNCEMENTS_SETTINGS';

		// Define the name of the form for use as a form key
		$form_name = 'acp_board_announcements';
		add_form_key($form_name);

		// Set an empty error array
		$errors = array();

		// Include files needed for displaying BBCodes
		if (!function_exists('display_custom_bbcodes'))
		{
			include $this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext;
		}

		// Get all board announcement data from the config_text table in the database
		$data = $this->config_text->get_array(array(
			'announcement_text',
			'announcement_uid',
			'announcement_bitfield',
			'announcement_options',
			'announcement_bgcolor',
		));

		// Get config options from the config table in the database
		$board_announcements_enable = $this->config['board_announcements_enable'];
		$board_announcements_users = $this->config['board_announcements_users'];
		$board_announcements_dismiss = $this->config['board_announcements_dismiss'];
		$board_announcements_expiry = $this->config['board_announcements_expiry'];

		// If form is submitted or previewed
		if ($this->request->is_set_post('submit') || $this->request->is_set_post('preview'))
		{
			// Test if form key is valid
			if (!check_form_key($form_name))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}

			// Get new announcement text and bgcolor values from the form
			$data['announcement_text'] = $this->request->variable('board_announcements_text', '', true);
			$data['announcement_bgcolor'] = $this->request->variable('board_announcements_bgcolor', '', true);

			// Get config options from the form
			$board_announcements_enable = $this->request->variable('board_announcements_enable', false);
			$board_announcements_users = $this->request->variable('board_announcements_users', self::ALL);
			$board_announcements_dismiss = $this->request->variable('board_announcements_dismiss', false);
			$board_announcements_expiry = $this->request->variable('board_announcements_expiry', '');
			if ($board_announcements_expiry !== '')
			{
				$board_announcements_expiry = $this->user->get_timestamp_from_format(self::DATE_FORMAT, $board_announcements_expiry);
				if ($board_announcements_expiry < time())
				{
					$errors[] = $this->user->lang('BOARD_ANNOUNCEMENTS_EXPIRY_INVALID');
				}
			}

			// Prepare announcement text for storage
			generate_text_for_storage(
				$data['announcement_text'],
				$data['announcement_uid'],
				$data['announcement_bitfield'],
				$data['announcement_options'],
				!$this->request->variable('disable_bbcode', false),
				!$this->request->variable('disable_magic_url', false),
				!$this->request->variable('disable_smilies', false)
			);

			// Store the announcement text and settings if submitted with no errors
			if (empty($errors) && $this->request->is_set_post('submit'))
			{
				// Store the config enable/disable state
				$this->config->set('board_announcements_enable', $board_announcements_enable);
				$this->config->set('board_announcements_users', $board_announcements_users);
				$this->config->set('board_announcements_dismiss', $board_announcements_dismiss);
				$this->config->set('board_announcements_expiry', $board_announcements_expiry);

				// Store the announcement settings to the config_table in the database
				$this->config_text->set_array(array(
					'announcement_text'			=> $data['announcement_text'],
					'announcement_uid'			=> $data['announcement_uid'],
					'announcement_bitfield'		=> $data['announcement_bitfield'],
					'announcement_options'		=> $data['announcement_options'],
					'announcement_bgcolor'		=> $data['announcement_bgcolor'],
					'announcement_timestamp'	=> time(),
				));

				$announcement_text = (!empty($data['announcement_text']));
				$guests_only  = ($board_announcements_users === self::GUESTS);
				$members_only = ($board_announcements_users === self::MEMBERS);

				$this->db->sql_transaction('begin');

				// Set the board_announcements_status for all registered users
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET board_announcements_status = ' . ($announcement_text && !$guests_only ? 1 : 0) . '
					WHERE user_type <> ' . USER_IGNORE;
				$this->db->sql_query($sql);

				// Set the board_announcement status for guests if they are allowed
				// We do this separately for guests to make sure it is always set to
				// the correct value every time.
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET board_announcements_status = ' . ($announcement_text && !$members_only ? 1 : 0) . '
					WHERE user_id = ' . ANONYMOUS;
				$this->db->sql_query($sql);

				$this->db->sql_transaction('commit');

				// Log the announcement update
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'BOARD_ANNOUNCEMENTS_UPDATED_LOG');

				// Destroy any cached board announcement data
				$this->cache->destroy('_board_announcement_data');

				// Output message to user for the announcement update
				trigger_error($this->user->lang('BOARD_ANNOUNCEMENTS_UPDATED') . adm_back_link($this->u_action));
			}
		}

		// Prepare a fresh announcement preview
		$announcement_text_preview = '';
		if ($this->request->is_set_post('preview'))
		{
			$announcement_text_preview = generate_text_for_display($data['announcement_text'], $data['announcement_uid'], $data['announcement_bitfield'], $data['announcement_options']);
		}

		// prepare the announcement text for editing inside the textbox
		$announcement_text_edit = generate_text_for_edit($data['announcement_text'], $data['announcement_uid'], $data['announcement_options']);

		// Output data to the template
		$this->template->assign_vars(array(
			'ERRORS'						=> count($errors) ? implode('<br />', $errors) : '',
			'BOARD_ANNOUNCEMENTS_ENABLED'	=> $board_announcements_enable,
			'BOARD_ANNOUNCEMENTS_DISMISS'	=> $board_announcements_dismiss,
			'BOARD_ANNOUNCEMENTS_TEXT'		=> $announcement_text_edit['text'],
			'BOARD_ANNOUNCEMENTS_PREVIEW'	=> $announcement_text_preview,
			'BOARD_ANNOUNCEMENTS_EXPIRY'	=> $board_announcements_expiry ? $this->user->format_date($board_announcements_expiry, self::DATE_FORMAT) : '',
			'BOARD_ANNOUNCEMENTS_BGCOLOR'	=> $data['announcement_bgcolor'],

			'S_BOARD_ANNOUNCEMENTS_USERS'	=> build_select(array(
				self::ALL		=> 'BOARD_ANNOUNCEMENTS_EVERYONE',
				self::MEMBERS	=> 'G_REGISTERED',
				self::GUESTS	=> 'G_GUESTS',
			), $board_announcements_users),

			'S_BBCODE_DISABLE_CHECKED'		=> !$announcement_text_edit['allow_bbcode'],
			'S_SMILIES_DISABLE_CHECKED'		=> !$announcement_text_edit['allow_smilies'],
			'S_MAGIC_URL_DISABLE_CHECKED'	=> !$announcement_text_edit['allow_urls'],

			'BBCODE_STATUS'			=> $this->user->lang('BBCODE_IS_ON', '<a href="' . append_sid("{$this->phpbb_root_path}faq.{$this->php_ext}", 'mode=bbcode') . '">', '</a>'),
			'SMILIES_STATUS'		=> $this->user->lang('SMILIES_ARE_ON'),
			'IMG_STATUS'			=> $this->user->lang('IMAGES_ARE_ON'),
			'FLASH_STATUS'			=> $this->user->lang('FLASH_IS_ON'),
			'URL_STATUS'			=> $this->user->lang('URL_IS_ON'),

			'S_BBCODE_ALLOWED'		=> true,
			'S_SMILIES_ALLOWED'		=> true,
			'S_BBCODE_IMG'			=> true,
			'S_BBCODE_FLASH'		=> true,
			'S_LINKS_ALLOWED'		=> true,
			'S_BOARD_ANNOUNCEMENTS'	=> true,

			'PICKER_DATE_FORMAT'	=> self::DATE_FORMAT,

			'U_ACTION'				=> $this->u_action,
		));

		// Build custom bbcodes array
		display_custom_bbcodes();
	}
}
