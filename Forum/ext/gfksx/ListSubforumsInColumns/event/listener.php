<?php
/**
*
* List Subforums In Columns extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace gfksx\ListSubforumsInColumns\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\user                          $user     Request object
	* @param \phpbb\request\request_interface     $request  User object
	* @return \rxu\ListSubforumsInColumns\event\listener
	* @access public
	*/
	public function __construct(\phpbb\user $user, \phpbb\request\request_interface $request, \phpbb\template\template $template)
	{
		$this->user = $user;
		$this->request = $request;
		$this->template = $template;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.display_forums_modify_template_vars'		=> 'switch_columns',
			'core.acp_manage_forums_request_data'			=> 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'		=> 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'			=> 'acp_manage_forums_display_form',
		);
	}

	public function switch_columns($event)
	{
		$row = $event['row'];
		$forum_row = $event['forum_row'];
		$subforums_row = $event['subforums_row'];
		$subforums_count = count($subforums_row);
		if ($subforums_count && (int) $row['forum_subforumslist_type'])
		{
			$forum_row['S_COLUMNS_ENABLED'] = true;
			$this->template->assign_vars(array(
				'S_PHPBB_31'	=> phpbb_version_compare(PHPBB_VERSION, '3.1.0@dev', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.2.0@dev', '<'),
				'S_PHPBB_32'	=> phpbb_version_compare(PHPBB_VERSION, '3.2.0@dev', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.3.0@dev', '<'),
			));

			$rows_per_column = (int) ceil($subforums_count / (int) $row['forum_subforumslist_type']);

			foreach ($subforums_row as $number => $subforum_row)
			{
				if (($number + 1) < $subforums_count && ($number + 1) % $rows_per_column == 0)
				{
					$subforums_row[$number]['S_SWITCH_COLUMN'] = true;
				}
			}
			$event['forum_row'] = $forum_row;
			$event['subforums_row'] = $subforums_row;
		}
	}

	public function acp_manage_forums_request_data($event)
	{
		$forum_data = $event['forum_data'];

		$forum_data += array(
			'forum_subforumslist_type'	=> $this->request->variable('subforumslist_type', 0),
		);

		$event['forum_data'] = $forum_data;
	}

	public function acp_manage_forums_initialise_data($event)
	{
		$this->user->add_lang_ext('gfksx/ListSubforumsInColumns', 'info_acp_sflist');

		$forum_data = $event['forum_data'];
		$forum_data += array(
			'forum_subforumslist_type'	=> 0,
		);

		$event['forum_data'] = $forum_data;
	}

	public function acp_manage_forums_display_form($event)
	{
		$forum_data = $event['forum_data'];
		$template_data = $event['template_data'];

		$template_data += array(
			'SUBFORUMSLIST_TYPE'	=> $forum_data['forum_subforumslist_type'],
		);

		$event['template_data'] = $template_data;
	}
}
