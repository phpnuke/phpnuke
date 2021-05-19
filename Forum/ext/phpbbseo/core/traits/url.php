<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpBB-SEO.ir
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\core\traits;

/**
* url trait
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
trait url
{
	// --> URL rewriting functions <--
	/**
	* format_url($url, $type = 'topic')
	* Prepare Titles for URL injection
	*/
	public function format_url($url, $type = 'topic')
	{
		$url = preg_replace('`\[.*\]`U', '', $url);

		if (isset($this->url_replace['find']))
		{
			$url = str_replace($this->url_replace['find'], $this->url_replace['replace'], $url);
		}

		$url = htmlentities($url, ENT_COMPAT, 'UTF-8');
		$url = preg_replace($this->RegEx['url_find'], $this->RegEx['url_replace'], $url);
		$url = strtolower(trim($url, '-'));

		return empty($url) ? $type : $url;
	}

	/**
	* set_url($url, $id = 0, $type = 'forum', $parent = '')
	* Prepare url first part and checks cache
	*/
	public function set_url($url, $id = 0, $type = 'forum', $parent = '')
	{
		if (empty($this->seo_url[$type][$id]))
		{
			return ($this->seo_url[$type][$id] = !empty($this->cache_config[$type . '_urls'][$id]) ? $this->cache_config[$type . '_urls'][$id] : sprintf($this->sftpl[$type], $this->format_url($url, $this->seo_static[$type]) . $this->seo_delim[$type] . $id, $id));
		}

		return $this->seo_url[$type][$id];
	}

	/**
	* set_parent_urls(array & $forum_data)
	* set/check urls of current forum's parent(s)
	*/
	public function set_parent_urls(&$forum_data)
	{
		if (!empty($forum_data['forum_parents']))
		{
			$forum_parents = @unserialize($forum_data['forum_parents']);
			if (!empty($forum_parents))
			{
				foreach ($forum_parents as $fid => $data)
				{
					$this->set_url($data[0], $fid, 'forum');
				}
			}
		}
	}

	/**
	* prepare_url($type, $title, $id, $parent = '', $smpl = false)
	* Prepare url first part
	*/
	public function prepare_url($type, $title, $id, $parent = '', $smpl = false)
	{
		return empty($this->seo_url[$type][$id]) ? ($this->seo_url[$type][$id] = sprintf($this->sftpl[$type . ($smpl ? '_smpl' : '')], $parent, !$smpl ? $this->format_url($title, $this->seo_static[$type]) : '', $id)) : $this->seo_url[$type][$id];
	}

	/**
	* get_url_info($type, $url, $info = 'title')
	* Get info from url (title, id, parent etc ...)
	*/
	public function get_url_info($type, $url, $info = 'title')
	{
		$url = trim($url, '/ ');

		if (preg_match($this->RegEx[$type]['match'], $url, $matches))
		{
			return !empty($matches[$this->RegEx[$type][$info]]) ? $matches[$this->RegEx[$type][$info]] : '';
		}

		return '';
	}

	/**
	* check_url($type, $url, $parent = '')
	* Validate a prepared url
	*/
	public function check_url($type, $url, $parent = '')
	{
		if (empty($url))
		{
			return false;
		}

		$parent = !empty($parent) ? (string) $parent : '[a-z0-9/_-]+';

		return !empty($this->RegEx[$type]['check']) ? preg_match(sprintf($this->RegEx[$type]['check'], $parent), $url) : false;
	}

	/**
	* prepare_topic_url(&$topic_data, $topic_forum_id)
	* Prepare topic url with SQL based URL rewriting
	*/
	public function prepare_topic_url(&$topic_data, $topic_forum_id = 0)
	{
		$id = max(0, (int) $topic_data['topic_id']);

		if (empty($this->seo_url['topic'][$id]))
		{
			if (!empty($topic_data['topic_url']))
			{
				return ($this->seo_url['topic'][$id] = $topic_data['topic_url'] . $id);
			}
			else
			{
				if ($this->modrtype > 2)
				{
					$topic_data['topic_title'] = censor_text($topic_data['topic_title']);
				}

				$topic_forum_id = $topic_forum_id ? $topic_forum_id : $topic_data['forum_id'];
				$parent_forum = $topic_data['topic_type'] == POST_GLOBAL ? $this->seo_static['global_announce'] : (!empty($this->seo_url['forum'][$topic_forum_id]) ? $this->seo_url['forum'][$topic_forum_id] : (!empty($topic_data['forum_name']) ? $this->set_url($topic_data['forum_name'], $topic_forum_id, 'forum') : ''));

				return ($this->seo_url['topic'][$id] = sprintf($this->sftpl['topic' . ($this->modrtype > 2 ? '' : '_smpl')], $parent_forum, $this->modrtype > 2 ? $this->format_url($topic_data['topic_title'], $this->seo_static['topic']) : '', $id));
			}
		}

		return $this->seo_url['topic'][$id];
	}

	/**
	* prepare_forum_url(&$forum_data, $parent = '')
	* Prepare url first part and checks cache
	*/
	public function prepare_forum_url(&$forum_data)
	{
		$id = max(0, (int) $forum_data['forum_id']);

		if (empty($this->seo_url['forum'][$id]))
		{
			$this->seo_url['forum'][$id] = sprintf($this->sftpl['forum'], $this->format_url($forum_data['forum_name'], $this->seo_static['forum']) . $this->seo_delim['forum'] . $id, $id);
		}

		return $this->seo_url['forum'][$id];
	}

	/**
	* prepare_iurl($data, $type, $parent = '')
	* Prepare url first part (not for forums) with SQL based URL rewriting
	*/
	public function prepare_iurl(&$data, $type, $parent = '')
	{
		$id = max(0, (int) $data[$type . '_id']);

		if (empty($this->seo_url[$type][$id]))
		{
			if (!empty($data[$type . '_url']))
			{
				return ($this->seo_url[$type][$id] = $data[$type . '_url'] . $id);
			}
			else
			{
				return ($this->seo_url[$type][$id] = sprintf($this->sftpl[$type . ($this->modrtype > 2 ? '' : '_smpl')], $parent, $this->modrtype > 2 ? $this->format_url($data[$type . '_title'], $this->seo_static[$type]) : '', $id));
			}
		}

		return $this->seo_url[$type][$id];
	}

	/**
	* set_user_url($username, $user_id = 0)
	* Prepare profile url
	*/
	public function set_user_url($username, $user_id = 0)
	{
		if (empty($this->seo_url['user'][$user_id]))
		{
			$username = strip_tags($username);

			$this->seo_url['username'][$username] = $user_id;

			if ($this->seo_opt['profile_inj'])
			{
				if ($this->seo_opt['profile_noids'])
				{
					$this->seo_url['user'][$user_id] = $this->seo_static['user'] . '/' . $this->seo_url_encode($username);
				}
				else
				{
					$this->seo_url['user'][$user_id] = $this->format_url($username,  $this->seo_delim['user']) . $this->seo_delim['user'] . $user_id;
				}
			}
			else
			{
				$this->seo_url['user'][$user_id] = $this->seo_static['user'] . $user_id;
			}
		}
	}

	/**
	* Returns true if the user can edit urls
	* @access public
	*/
	public function url_can_edit($forum_id = 0)
	{
		if (empty($this->seo_opt['sql_rewrite']) || empty($this->user->data['is_registered']))
		{
			return false;
		}

		if ($this->auth->acl_get('a_'))
		{
			return true;
		}

		// un comment to grant url edit perm to moderators in at least a forums
		/*
		if ($this->auth->acl_getf_global('m_'))
		{
			return true;
		}
		*/

		$forum_id = max(0, (int) $forum_id);

		if ($forum_id && $this->auth->acl_get('m_', $forum_id))
		{
			return true;
		}

		return false;
	}

	/**
	* Will break if a $filter pattern is foundin $url.
	* Example $filter = array("view=", "mark=");
	*/
	public function filter_url($filter = [])
	{
		foreach ($filter as $patern)
		{
			if (strpos($this->url_in, $patern) !== false)
			{
				$this->get_vars = [];
				$this->url = $this->url_in;

				return false;
			}
		}

		return true;
	}

	/**
	* expected_url($path = '')
	* build expected url
	*/
	public function expected_url($path = '')
	{
		$path = empty($path) ? $this->phpbb_root_path : $path;
		$params = [];

		foreach ($this->seo_opt['zero_dupe']['redir_def'] as $get => $def)
		{
			if (($this->request->is_set($get, \phpbb\request\request_interface::GET) && $def['keep']) || !empty($def['force']))
			{
				$params[$get] = $def['val'];

				if (!empty($def['hash']))
				{
					$params['#'] = $def['hash'];
				}
			}
		}

		$this->page_url = append_sid($path . $this->seo_opt['req_file'] . '.' . $this->php_ext, $params, true, 0);

		return $this->page_url;
	}

	/**
	* url_rewrite($url, $params = false, $is_amp = true, $session_id = false)
	* builds and Rewrite URLs.
	* Allow adding of many more cases than just the
	* regular phpBB URL rewritting without slowing down the process.
	* Mimics append_sid with some shortcuts related to how url are rewritten
	*/
	public function url_rewrite($url, $params = false, $is_amp = true, $session_id = false, $is_route = false, $recache = false)
	{
		global $_SID, $_EXTRA_URL;

		if ($is_route)
		{
			return false;
		}

		$qs = $anchor = '';
		$amp_delim = ($is_amp) ? '&amp;' : '&';

		$this->get_vars = [];

		if (strpos($url, '#') !== false)
		{
			list($url, $anchor) = explode('#', $url, 2);

			$anchor = '#' . $anchor;
		}

		@list($this->path, $qs) = explode('?', $url, 2);

		if (is_array($params))
		{
			if (!empty($params['#']))
			{
				$anchor = '#' . $params['#'];

				unset($params['#']);
			}

			$qs .= ($qs ? $amp_delim : '') . $this->query_string($params, $amp_delim, '');
		}
		else if ($params)
		{
			if (strpos($params, '#') !== false)
			{
				list($params, $anchor) = explode('#', $params, 2);

				$anchor = '#' . $anchor;
			}

			$qs .= ($qs ? $amp_delim : '') . $params;
		}

		// Appending custom url parameter?
		if (!empty($_EXTRA_URL))
		{
			$qs .= ($qs ? $amp_delim : '') . implode($amp_delim, $_EXTRA_URL);
		}

		// Sid ?
		if ($session_id === false && !empty($_SID))
		{
			$qs .= ($qs ? $amp_delim : '') . "sid=$_SID";
		}
		else if ($session_id)
		{
			$qs .= ($qs ? $amp_delim : '') . "sid=$session_id";
		}

		// Build vanilla URL
		if (preg_match("`\.[a-z0-9]+$`i", $this->path))
		{
			$this->file = basename($this->path);
			$this->path = ltrim(str_replace($this->file, '', $this->path), '/');
		}
		else
		{
			$this->file = '';
			$this->path = ltrim($this->path, '/');
		}

		$this->url_in = $this->file . ($qs ? '?' . $qs : '');
		$url = $this->path . $this->url_in;

		if (!$recache && isset($this->seo_cache[$url]))
		{
			return $this->seo_cache[$url] . $anchor;
		}

		if (!$this->seo_opt['url_rewrite'] || defined('ADMIN_START'))
		{
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		if (isset($this->stop_dirs[$this->path]))
		{
			// add full url no matter what assuming concerned
			// scripts will always be phpBB ones
			$url = $this->seo_path['phpbb_url'] . preg_replace('`^.*?(' . trim($this->path, '/.') . '.*?)$`', '\1', $url);
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		$this->filename = trim(str_replace('.' . $this->php_ext, '', $this->file));

		if (isset($this->stop_files[$this->filename]))
		{
			// add full url no matter what assuming concerned
			// scripts will always be phpBB ones
			$url = $this->seo_path['phpbb_url'] . preg_replace('`^.*?(' . $this->filename . '\.' . $this->php_ext . '.*?)$`', '\1', $url);
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		parse_str(str_replace('&amp;', '&', $qs), $this->get_vars);

		// strip slashes if necessary
		if (defined('SEO_STRIP'))
		{
			$this->get_vars = array_map([$this, 'stripslashes'], $this->get_vars);
		}

		if (empty($this->user->data['is_registered']))
		{
			if ($this->seo_opt['rem_sid'])
			{
				unset($this->get_vars['sid']);
			}

			if ($this->seo_opt['rem_hilit'])
			{
				unset($this->get_vars['hilit']);
			}
		}

		$this->url = $this->file;

		if (!empty($this->rewrite_method[$this->path][$this->filename]))
		{
			$rewrite_method_name = $this->rewrite_method[$this->path][$this->filename];

			$this->$rewrite_method_name();

			return ($this->seo_cache[$url] = $this->path . $this->url . $this->query_string($this->get_vars, $amp_delim, '?')) . $anchor;
		}
		else
		{
			return ($this->seo_cache[$url] = $url) . $anchor;
		}
	}
}
