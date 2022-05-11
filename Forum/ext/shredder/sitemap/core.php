<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class core
{
	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*

	/** @var \phpbb\auth\auth */
	private $auth;

	/** @var \phpbb\config\config */
	private $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	private $db;

	/** @var ContainerInterface */
	private $phpbb_container;

	/** @var \phpbb\extension\manager */
	private $phpbb_extension_manager;

	/** @var \phpbb\user */
	private $user;

	/** @var string */
	private $phpbb_root_path;
	private $php_ext;

	private $path;
	private $dir;

	private $priority;
	private $frequency;
	private $seo;
	private $appender;

	public function __construct(\shredder\sitemap\appender $appender, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\db\driver\driver_interface $db, ContainerInterface $phpbb_container, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\user $user, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->db = $db;
		$this->phpbb_container = $phpbb_container;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->path = generate_board_url() . '/';
		$this->dir = 'store/shredder/';
		$this->appender = $appender;

		$this->priority = array(
			POST_ANNOUNCE => $this->config['sitemap_seo_prior_' . POST_ANNOUNCE . ''],
			POST_NORMAL => $this->config['sitemap_seo_prior_' . POST_NORMAL . ''],
			POST_GLOBAL => $this->config['sitemap_seo_prior_' . POST_GLOBAL . ''],
			POST_STICKY => $this->config['sitemap_seo_prior_' . POST_STICKY . ''],
		);

		$this->frequency = array(
			POST_ANNOUNCE => $this->config['sitemap_seo_freq_' . POST_ANNOUNCE . ''],
			POST_NORMAL => $this->config['sitemap_seo_freq_' . POST_NORMAL . ''],
			POST_GLOBAL => $this->config['sitemap_seo_freq_' . POST_GLOBAL . ''],
			POST_STICKY => $this->config['sitemap_seo_freq_' . POST_STICKY . ''],
		);
	}

	public function sitemap_file_get_contents($seqno)
	{
		$file = $this->root_path . $this->dir . $seqno . '.xml';

		if (file_exists($file))
		{
			return file_get_contents($file);
		}

		return false;
	}

	/**
	* Generate sitemap
	*/
	public function generate_sitemap()
	{
		global $phpbb_dispatcher;

		$excl = ($this->config_text->get('sitemap_seo_excluded')) ? explode(',', $this->config_text->get('sitemap_seo_excluded')) : array();
		$limit = $this->config['sitemap_seo_url_limit'];

		// Try to create "sitemap store" directory if it does not exist
		if (!file_exists($this->root_path . $this->dir))
		{
			@mkdir($this->root_path . $this->dir, 0777);
			phpbb_chmod($this->root_path . $this->dir, CHMOD_READ | CHMOD_WRITE);
		}

		if (file_exists($this->root_path . $this->dir) && is_dir($this->root_path . $this->dir))
		{
			// Purge all files in "sitemap store" directory if they are old
			try
			{
				$iterator = new \DirectoryIterator($this->root_path . $this->dir);
			}
			catch (\Exception $e)
			{
				return;
			}

			foreach ($iterator as $fileInfo)
			{
				if ($fileInfo->isDot())
				{
					continue;
				}

				$file = $fileInfo->getFilename();

				if (strpos($file, 'htaccess') === false)
				{
					@unlink($this->root_path . $this->dir . $file);
				}
			}
		}
		else
		{
			throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
		}

		// Write out .htaccess file
		$ht_file = '<Files *>'."\r\n";
		$ht_file .= "\t".'Order Allow,Deny'."\r\n";
		$ht_file .= "\t".'Deny from All'."\r\n";
		$ht_file .= '</Files>';

		if (!file_exists($this->root_path . $this->dir . '.htaccess'))
		{
			$written = true;

			if (!($fp = @fopen($this->root_path . $this->dir . '.htaccess', 'w')))
			{
				$written = false;
			}

			if (!(@fwrite($fp, $ht_file)))
			{
				$written = false;
			}

			@fclose($fp);

			if ($written)
			{
				phpbb_chmod($this->root_path . $this->dir . '.htaccess', CHMOD_READ);
			}
			else
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}
		}

		// Check out other extensions for compatibility
		if ($this->phpbb_extension_manager->is_enabled('phpbbseo/usu'))
		{
			$seo_core = $this->phpbb_container->get('phpbbseo.usu.core');
		}

		$this->seo = (isset($seo_core)) ? $seo_core->seo_opt['url_rewrite'] : 0;

		$with_f = (isset($this->config['phpbbex_version'])) ? true : false;

		if ($this->phpbb_extension_manager->is_enabled('shredder/seo_topic_url'))
		{
			$with_f = ($this->config['stc_mode']) ? true : false;
		}

		// Build sitemap
		$f_beg = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
		$f_beg .= '<?xml-stylesheet type="text/xsl" href="' . $this->path . 'ext/shredder/sitemap/styles/sitemap.xsl"?>'."\r\n";
		$f_beg .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
		$f_end = '</urlset>';

		// Simulate guest permissions so a guest user can create sitemap. Backup the original data first
		$backup = array(
			'user_data'	=> $this->user->data,
			'auth'		=> $this->auth
		);

		$sql = 'SELECT * FROM ' . USERS_TABLE . '
				WHERE user_id = ' . ANONYMOUS;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->user->data = $row;
			$this->auth->acl($this->user->data);
		}
		$this->db->sql_freeresult($result);

		// Select basic info
		$sql = 'SELECT f.forum_id, f.forum_type, f.forum_name, f.forum_topics_per_page, COUNT(t.topic_id) AS forum_topics
			FROM ' . FORUMS_TABLE . ' f, ' . TOPICS_TABLE . ' t
			WHERE t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.forum_id = f.forum_id
			GROUP BY f.forum_id, f.forum_type, f.forum_name, f.forum_topics_per_page';
		$result = $this->db->sql_query($sql);

		$f_ids = array(0);
		while ($row = $this->db->sql_fetchrow($result))
		{
			if (!in_array($row['forum_id'], $excl) && $this->auth->acl_get('f_list', $row['forum_id']) && $row['forum_type'] == FORUM_POST)
			{
				$f_ids[] = $row['forum_id'];
				$forums[$row['forum_id']] = $row;
			}
			unset($row);
		}
		$this->db->sql_freeresult($result);

		// Generate forums list
		$glob = $ann = $times = array();

		$list_ary = $this->auth->acl_getf('f_list', true);
		$list_ary = array_unique(array_keys($list_ary));

		// Extract the backup to set the $this->user and $this->auth constants to what they were before
		$this->auth = $backup['auth'];
		$this->user->data = $backup['user_data'];

		unset($backup);

		// Get max global announcement time
		$sql = 'SELECT MAX(topic_last_post_time) AS max_glob_time
			FROM ' . TOPICS_TABLE . '
			WHERE topic_type = ' . POST_GLOBAL . '
				AND topic_visibility = ' . ITEM_APPROVED . '
				AND ' . $this->db->sql_in_set('forum_id', $list_ary);
		$result = $this->db->sql_query($sql);
		$glob = array((int) $this->db->sql_fetchfield('max_glob_time'));
		$this->db->sql_freeresult($result);

		// Bring forums to sitemap
		$sql = 'SELECT forum_id, topic_id, topic_type, topic_last_post_time
			FROM ' . TOPICS_TABLE . '
			WHERE topic_visibility = ' . ITEM_APPROVED . '
				AND ' . $this->db->sql_in_set('forum_id', $f_ids) . '
			ORDER BY forum_id,
			CASE
				WHEN (topic_type = ' . POST_ANNOUNCE . ' OR topic_type = ' . POST_GLOBAL . ') THEN 1
				' . ((defined('POST_ARTICLE')) ? 'WHEN topic_type = ' . POST_ARTICLE . ' THEN 2' : '') . '
				WHEN topic_type = ' . POST_STICKY . ' THEN 3
				ELSE 4
			END, topic_last_post_time DESC';
		$result = $this->db->sql_query($sql);

		$page = $topic = $all_f = 0;

		while ($data = $this->db->sql_fetchrow($result))
		{
			$t_type = (int) $data['topic_type'];

			if ($t_type == POST_ANNOUNCE || $t_type == POST_GLOBAL)
			{
				$ann[] = $data['topic_last_post_time'];
			}
			else
			{
				++$topic;
				$times[] = $data['topic_last_post_time'];
			}
			++$all_f;

			$per = ($forums[$data['forum_id']]['forum_topics_per_page']) ? $forums[$data['forum_id']]['forum_topics_per_page'] : $this->config['topics_per_page'];

			if ($topic == $per || $forums[$data['forum_id']]['forum_topics'] == $all_f)
			{
				$f_id = (int) $data['forum_id'];

				if ($this->seo)
				{
					if ($page == 0)
					{
						$seo_core->set_url($forums[$f_id]['forum_name'], $f_id, $seo_core->seo_static['forum']);
						$f_url = append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $f_id, true, '');
					}
					else
					{
						$f_url = append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $f_id . "&amp;start=" . ($page * $per), true, '');
					}
				}
				else
				{
					if ($page == 0)
					{
						$f_url = $this->path . "viewforum.$this->php_ext?f=$f_id";
					}
					else
					{
						$f_url = $this->path . "viewforum.$this->php_ext?f=$f_id&amp;start=" . ($page * $per);
					}
				}

				$this->appender->append($f_url, (int) max(array_merge($glob, $ann, $times)), $this->config['sitemap_seo_freq_f'], $this->config['sitemap_seo_prior_f']);

				unset($f_url);

				++$page;
				$times = array();
				$topic = 0;

				if ($forums[$f_id]['forum_topics'] == $all_f)
				{
					$page = $all_f = 0;
					$ann = array();
				}
			}
			unset($data);
		}
		$this->db->sql_freeresult($result);

		// Generate topics list
		$seo_add = ($this->seo) ? ', t.topic_url, t.topic_title' : '';

		$next_cycle = true;
		$cycle = 0;
		$sql_page_size = $this->config['sitemap_seo_batch_size'];

		$times = array();
		$per = $this->config['posts_per_page'];
		$page = $post = 0;

		while ($next_cycle)
		{
			// set false to break cycle if no rows fetched
			$next_cycle = false;

			$sql = 'SELECT t.topic_id' . $seo_add . ', t.topic_type, t.topic_last_post_id, t.forum_id, p.post_id, p.post_modified
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_visibility = ' . ITEM_APPROVED . ' AND p.post_visibility = ' . ITEM_APPROVED . '
					AND t.topic_status <> ' . ITEM_MOVED . '
					AND ' . $this->db->sql_in_set('t.forum_id', $f_ids) . '
					AND t.topic_id = p.topic_id 
				ORDER BY t.topic_id, p.post_time';
			$result = $this->db->sql_query_limit($sql, $sql_page_size, $sql_page_size * $cycle);

			while ($data = $this->db->sql_fetchrow($result))
			{
				// promote process to the next cycle
				$next_cycle = true;

				++$post;
				$times[] = $data['post_modified'];

				if ($post == $per || $data['post_id'] == $data['topic_last_post_id'])
				{
					$t_id = (int) $data['topic_id'];
					$f_id = (int) $data['forum_id'];
					$t_type = (int) $data['topic_type'];

					if ($this->seo)
					{
						if ($page == 0)
						{
							$seo_core->prepare_iurl($data, 'topic', $t_type == POST_GLOBAL ? $seo_core->seo_static['global_announce'] : $seo_core->seo_url['forum'][$f_id]);
							$t_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $f_id . '&amp;t=' . $t_id, true, '');
						}
						else
						{
							$t_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $f_id . '&amp;t=' . $t_id . "&amp;start=" . ($page * $per), true, '');
						}
					}
					else
					{
						$f_part = ($with_f) ? "f=$f_id&amp;" : '';

						if ($page == 0)
						{
							$t_url = $this->path . "viewtopic.$this->php_ext?" . $f_part . "t=$t_id";
						}
						else
						{
							$t_url = $this->path . "viewtopic.$this->php_ext?" . $f_part . "t=$t_id&amp;start=" . ($page * $per);
						}
					}

					$this->appender->append($t_url, max($times), $this->frequency[$t_type], $this->priority[$t_type]);

					unset($t_url);

					++$page;
					$times = array();
					$post = 0;

					if ($data['post_id'] == $data['topic_last_post_id'])
					{
						$page = 0;
					}
				}
				unset($data);
			}
			$this->db->sql_freeresult($result);
			++$cycle;
		}

		/**
		* Event to allow other extensions append links to sitemap
		*
		* @event shredder.sitemap.extra_sitemap_content
		* @since 1.1.0
		*/
		$phpbb_dispatcher->dispatch('shredder.sitemap.extra_sitemap_content');

		return $this->appender->get_content();
	}
}
