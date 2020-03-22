<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class ajax_preview_helper
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $preview_message = '';

	/** @var string|bool */
	protected $preview_attachments = false;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user            $user
	 * @param \phpbb\request\request $request
	 */
	public function __construct(\phpbb\user $user, \phpbb\request\request $request)
	{
		$this->user = $user;
		$this->request = $request;
	}

	/**
	 * Checks whether this is an ajax request and handles ajax preview
	 *
	 * @param object $event The event object
	 */
	public function ajax_preview($event)
	{
		$post_data = $event['post_data'];
		$forum_id = (int) $post_data['forum_id'];
		/** @var \parse_message $message_parser */
		$message_parser = $event['message_parser'];

		$message_parser->message = $this->request->variable('message', '', true);
		$this->preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

		// Attachment Preview
		$this->preview_attachments($message_parser, $forum_id);

		ajax_helper::send_json(array(
			'status'         => 'preview',
			'PREVIEW_TITLE'  => $this->user->lang['PREVIEW'],
			'PREVIEW_TEXT'   => $this->preview_message,
			'PREVIEW_ATTACH' => $this->preview_attachments,
		));
	}

	/**
	 * Attachments preview
	 *
	 * @param \parse_message $message_parser Message parser object
	 * @param int            $forum_id       Forum ID
	 */
	public function preview_attachments($message_parser, $forum_id)
	{
		if (sizeof($message_parser->attachment_data))
		{
			$update_count = array();
			$attachment_data = $message_parser->attachment_data;

			parse_attachments($forum_id, $this->preview_message, $attachment_data, $update_count, true);
			$this->build_attach_box($attachment_data);

			unset($attachment_data);
		}
	}

	/**
	 * Build attach box for not-inline attachments
	 *
	 * @param array $attachment_data
	 */
	public function build_attach_box($attachment_data)
	{
		$this->preview_attachments = '';
		foreach ($attachment_data as $i => $attachment)
		{
			$this->preview_attachments .= '<dd>' . $attachment . '</dd>';
		}
		if (!empty($this->preview_attachments))
		{
			$this->preview_attachments = '<dl class="attachbox"><dt>' . $this->user->lang['ATTACHMENTS'] . '</dt>' . $this->preview_attachments . '</dl>';
		}
	}
}
