<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class plugins_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth         $auth
	 * @param \phpbb\config\config     $config
	 * @param \phpbb\user              $user
	 * @param \phpbb\extension\manager $phpbb_extension_manager
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
	}

	/**
	 * Returns template variables for supported extensions for quick reply.
	 *
	 * @return array
	 */
	public function template_variables_for_extensions()
	{
		$template_variables = array();
		if (
			$this->phpbb_extension_manager->is_enabled('rxu/PostsMerging') &&
			$this->user->data['is_registered'] &&
			$this->config['merge_interval']
		)
		{
			// Always show the checkbox if PostsMerging extension is installed.
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
			$template_variables += array('POSTS_MERGING_OPTION' => true);
		}

		// ABBC3
		$template_variables += array('S_ABBC3_INSTALLED' => $this->phpbb_extension_manager->is_enabled('vse/abbc3'));

		return $template_variables;
	}

	/**
	 * Returns template variables for QuickReply built-in plugins.
	 *
	 * @param int $forum_id Current forum ID
	 * @return array
	 */
	public function template_variables_for_plugins($forum_id)
	{
		return array(
			'S_QR_NOT_CHANGE_SUBJECT'   => !$this->auth->acl_get('f_qr_change_subject', $forum_id),

			// begin mod CapsLock Transfer
			'S_QR_CAPS_ENABLE'          => $this->config['qr_capslock_transfer'],
			// end mod CapsLock Transfer

			// begin mod Translit
			'S_QR_SHOW_BUTTON_TRANSLIT' => $this->config['qr_show_button_translit'],
			// end mod Translit
		);
	}

	/**
	 * Checks whether $data array contains post_id and topic_first_post_id keys.
	 * These keys can be absent in custom calls of submit_post() function.
	 *
	 * @param array $data The array for checking
	 * @return bool
	 */
	public function post_id_in_array($data)
	{
		return isset($data['topic_first_post_id']) && isset($data['post_id']);
	}

	/**
	 * Returns whether the user cannot change post subject.
	 *
	 * @param int    $forum_id            Forum ID
	 * @param string $mode                Mode
	 * @param int    $topic_first_post_id ID of the first post in the topic
	 * @param int    $post_id             ID of the current post
	 * @return bool
	 */
	public function cannot_change_subject($forum_id, $mode, &$topic_first_post_id, $post_id)
	{
		return (
			!$this->auth->acl_get('f_qr_change_subject', $forum_id)
			&& $mode != 'post'
			&& ($topic_first_post_id != $post_id || $mode == 'quote')
		);
	}

	/**
	 * Returns whether the full quote is disabled for the user.
	 *
	 * @param array $topic_data Array with topic data
	 * @param int   $post_id    Current post ID
	 * @return bool
	 */
	public function full_quote_disabled($topic_data, $post_id)
	{
		return (
			!$this->auth->acl_get('f_qr_full_quote', $topic_data['forum_id']) || (
				!$this->config['qr_last_quote'] &&
				$topic_data['topic_last_post_id'] === $post_id
			)
		);
	}

	/**
	 * Returns whether the quote button should be hidden from the user.
	 *
	 * @param array $topic_data Array with topic data
	 * @param int   $post_id    Current post ID
	 * @return bool
	 */
	public function quote_button_disabled($topic_data, $post_id)
	{
		return !$this->config['qr_quickquote_button'] && $this->full_quote_disabled($topic_data, $post_id);
	}

	/**
	 * Checks whether the user does not have the full quote permission.
	 *
	 * @param object $event The event object
	 * @return bool
	 */
	public function check_full_quote_permission($event)
	{
		return ($event['mode'] == 'quote' && !$event['submit'] && !$event['preview'] && !$event['refresh'] &&
			$this->full_quote_disabled($event['post_data'], $event['post_id']));
	}
}
