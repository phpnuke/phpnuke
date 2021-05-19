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
* get_set trait
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
trait get_set
{
	// -> Cache functions
	/**
	* forum_id(&$forum_id, $forum_uri = '')
	* will tell the forum id from the uri or the forum_uri GET var by checking the cache.
	*/
	public function get_forum_id(&$forum_id, $forum_uri = '')
	{
		if (empty($forum_uri))
		{
			$forum_uri = $this->request->variable('forum_uri', '');

			if (!empty($this->request))
			{
				$this->request->overwrite('forum_uri', null, \phpbb\request\request_interface::REQUEST);
				$this->request->overwrite('forum_uri', null, \phpbb\request\request_interface::GET);
			}
			else
			{
				unset($_GET['forum_uri'], $_REQUEST['forum_uri']);
			}
		}

		if (empty($forum_uri) || $forum_uri == $this->seo_static['global_announce'])
		{
			return 0;
		}

		if ($id = @array_search($forum_uri, $this->cache_config['forum_urls']))
		{
			$forum_id = max(0, (int) $id);
		}
		else if ($id = $this->get_url_info('forum', $forum_uri, 'id'))
		{
			$forum_id = max(0, (int) $id);
		}
		else if (!empty($this->forum_redirect))
		{
			if (isset($this->forum_redirect[$forum_uri]))
			{
				$forum_id = max(0, (int) $this->forum_redirect[$forum_uri]);
			}
		}

		return $forum_id;
	}

	/**
	* Will unset all default var stored in $filter array.
	* Example $filter = array('st' => 0, 'sk' => 't', 'sd' => 'a', 'hilit' => '');
	*/
	public function filter_get_var($filter = [])
	{
		if (!empty($this->get_vars))
		{
			foreach ($this->get_vars as $paramkey => $paramval)
			{
				if (isset($filter[$paramkey]))
				{
					if ($filter[$paramkey] == $this->get_vars[$paramkey] || !isset($this->get_vars[$paramkey]))
					{
						unset($this->get_vars[$paramkey]);
					}
				}
			}
		}

		return;
	}

	/**
	* get_canonical
	* Returns the canonical url if ever built
	* Beware with ssl :
	* 	Since we want zero duplicate, the canonical element will only use https when ssl is forced
	* 	(eg set as THE server protocol in config) and will use http in other cases.
	*/
	public function get_canonical()
	{
		return !empty($this->seo_path['canonical']) ? $this->sslify($this->seo_path['canonical'], $this->ssl['forced']) : '';
	}

	/**
	* set_title($type, $title, $id, $parent = '')
	* Set title for url injection
	*/
	public function set_title($type, $title, $id, $parent = '')
	{
		return empty($this->seo_url[$type][$id]) ? ($this->seo_url[$type][$id] = ($parent ? $parent . '/' : '') . $this->format_url($title, $this->seo_static[$type])) : $this->seo_url[$type][$id];
	}

	/**
	* set_cond($bool, $type = 'bool_redir', $or = true)
	* Helps out grabbing boolean vars
	*/
	public function set_cond($bool, $type = 'do_redir', $or = true)
	{
		if ($or)
		{
			$this->seo_opt['zero_dupe'][$type] = (boolean) ($bool || $this->seo_opt['zero_dupe'][$type]);
		}
		else
		{
			$this->seo_opt['zero_dupe'][$type] = (boolean) ($bool && $this->seo_opt['zero_dupe'][$type]);
		}

		return;
	}

	/**
	* Set the do_redir_post option right
	*/
	public function set_do_redir_post()
	{
		switch ($this->seo_opt['zero_dupe']['post_redir'])
		{
			case 'guest':
				if (empty($this->user->data['is_registered']))
				{
					$this->seo_opt['zero_dupe']['do_redir_post'] = true;
				}

				break;
			case 'all':
				$this->seo_opt['zero_dupe']['do_redir_post'] = true;

				break;
			case 'off': // Do not redirect
				$this->seo_opt['zero_dupe']['do_redir'] = false;
				$this->seo_opt['zero_dupe']['go_redir'] = false;
				$this->seo_opt['zero_dupe']['do_redir_post'] = false;

				break;
			default:
				$this->seo_opt['zero_dupe']['do_redir_post'] = false;

				break;
		}

		return $this->seo_opt['zero_dupe']['do_redir_post'];
	}
}
