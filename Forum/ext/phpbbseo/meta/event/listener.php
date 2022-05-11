<?php
/**
*
* @package Meta Tags phpBB SEO
* @version $$
* @copyright (c) 2014 www.phpbb-seo.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\meta\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	/* @var \phpbbseo\meta\core */
	protected $core;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbbseo\usu\core */
	protected $usu_core;

	/**
	* Constructor
	*
	* @param \phpbbseo\meta\core			$core				meta core object
	* @param \phpbb\db\driver\driver_interface	$db				Database object
	* @param \phpbbseo\usu\core			$usu_core			usu core objec
	*/
	public function __construct(\phpbbseo\meta\core $core, \phpbb\db\driver\driver_interface $db, \phpbbseo\usu\core $usu_core = null)
	{
		$this->core = $core;
		$this->db = $db;
		$this->usu_core = $usu_core;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_footer'			=> 'core_page_footer',
			'core.index_modify_page_title'		=> 'core_index_modify_page_title',
			'core.viewforum_modify_topics_data'	=> 'core_viewforum_modify_topics_data',
			'core.viewtopic_modify_post_row'	=> 'core_viewtopic_modify_post_row',
			'core.page_header'			=> 'core_page_header',
		);
	}

	public function core_page_header($event)
	{
		$this->core->collect('title', $event['page_title']);
	}

	public function core_page_footer($event)
	{
		if (!empty($this->usu_core))
		{
			$this->core->meta['canonical'] = $this->usu_core->get_canonical();
		}
		$this->core->build_meta();
	}

	public function core_index_modify_page_title($event)
	{
		$this->core->collect('description', $this->core->config['sitename'] . ' : ' .  $this->core->config['site_desc']);
		$this->core->collect('keywords', $this->core->config['sitename'] . ' ' . $this->core->meta['description']);
	}

	public function core_viewforum_modify_topics_data($event)
	{
		global $forum_data; // god save the hax

		$this->core->collect('description', $forum_data['forum_name'] . ' : ' . (!empty($forum_data['forum_desc']) ? $forum_data['forum_desc'] : $this->core->meta_def['description']));
		$this->core->collect('keywords', $forum_data['forum_name'] . ' ' . $this->core->meta['description']);
	}

	public function core_viewtopic_modify_post_row($event)
	{
		global $post_list; // god save the hax

		if ($event['current_row_number'] == 0)
		{
			$row = $event['row'];
			$topic_data = $event['topic_data'];

			$this->core->collect('description', censor_text($row['post_text']));

			if ($this->core->config['og'])
			{
				$post_row = $event['post_row'];
				$message = $post_row['MESSAGE'];

				if (strpos($message, '<img') !== false)
				{
					if (preg_match('`<img[^>]*src="(https?://[^"]+)"[^>]*>`Us', $message, $matches))
					{
						$this->core->collect('image', $matches[1]);
					}
				}
			}

			$m_kewrd = '';
			if ($this->core->config['topic_sql'])
			{
				$common_sql = $this->core->config['bypass_common'] ? '' : 'AND w.word_common = 0';
				// collect keywords from all post in page
				$post_id_sql = $this->db->sql_in_set('m.post_id', $post_list, false, true);
				$sql = "SELECT w.word_text
					FROM " . SEARCH_WORDMATCH_TABLE . " m, " . SEARCH_WORDLIST_TABLE . " w
					WHERE $post_id_sql
						AND w.word_id = m.word_id
						$common_sql
					ORDER BY w.word_count DESC";
				$result = $this->db->sql_query_limit($sql, min(25, (int) $this->core->config['keywordlimit']));
				while ( $meta_row = $this->db->sql_fetchrow($result) )
				{
					$m_kewrd .= ' ' . $meta_row['word_text'];
				}

				$this->db->sql_freeresult($result);
			}
			$this->core->collect('keywords', $topic_data['topic_title'] . ' ' . $row['post_subject'] . ' ' . (!empty($m_kewrd) ? $m_kewrd : $this->core->meta['description']));
		}
	}
}
