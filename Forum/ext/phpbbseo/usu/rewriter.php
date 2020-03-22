<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu;

/**
* rewriter Class
* www.phpBB-SEO.org
* @package Ultimate phpBB SEO Friendly URL
*/
class rewriter
{
	/** @var \phpbbseo\usu\core */
	private $core;

	/* @var \phpbb\user */
	private $user;

	/**
	* Current $phpbb_root_path
	* @var string
	*/
	private $phpbb_root_path;

	/**
	* Constructor
	*
	* @param	\phpbbseo\usu\core			$core
	* @param	\phpbb\user					$user				User object
	* @param	string						$phpbb_root_path	Path to the phpBB root
	*/
	public function __construct(\phpbbseo\usu\core $core, \phpbb\user $user, $phpbb_root_path)
	{
		$this->core = $core;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/**
	* URL rewritting for viewtopic.php
	* With Virtual Folder Injection
	*/
	public function viewtopic()
	{
		$this->core->filter_url($this->core->stop_vars);
		$this->core->path = $this->core->seo_path['phpbb_urlR'];

		if (!empty($this->core->get_vars['p']))
		{
			$this->core->url = $this->core->seo_static['post'] . $this->core->get_vars['p'] . $this->core->seo_ext['post'];

			unset($this->core->get_vars['p'], $this->core->get_vars['f'], $this->core->get_vars['t'], $this->core->get_vars['start']);

			return;
		}

		if (isset($this->core->get_vars['t']) && !empty($this->core->seo_url['topic'][$this->core->get_vars['t']]))
		{
			$paginate_method_name = $this->core->paginate_method['topic'];

			// Filter default params
			$this->core->filter_get_var($this->core->get_filter['topic']);
			$this->core->$paginate_method_name($this->core->seo_ext['topic']);
			$this->core->url = $this->core->seo_url['topic'][$this->core->get_vars['t']] . $this->core->start;

			unset($this->core->get_vars['t'], $this->core->get_vars['f'], $this->core->get_vars['p']);

			return;
		}
		else if (!empty($this->core->get_vars['t']))
		{
			$paginate_method_name = $this->core->paginate_method['topic'];

			// Filter default params
			$this->core->filter_get_var($this->core->get_filter['topic']);
			$this->core->$paginate_method_name($this->core->seo_ext['topic']);
			$this->core->url = $this->core->seo_static['topic'] . $this->core->get_vars['t'] . $this->core->start;

			unset($this->core->get_vars['t'], $this->core->get_vars['f'], $this->core->get_vars['p']);

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_url'];

		return;
	}

	/**
	* URL rewritting for viewforum.php
	*/
	public function viewforum()
	{
		$this->core->path = $this->core->seo_path['phpbb_urlR'];
		$this->core->filter_url($this->core->stop_vars);

		if (!empty($this->core->get_vars['f']))
		{
			$paginate_method_name = $this->core->paginate_method['forum'];

			// Filter default params
			$this->core->filter_get_var($this->core->get_filter['forum']);
			$this->core->$paginate_method_name($this->core->seo_ext['forum']);

			if (empty($this->core->seo_url['forum'][$this->core->get_vars['f']]))
			{
				$this->core->url = $this->core->seo_static['forum'] . $this->core->get_vars['f'] . $this->core->start;
			}
			else
			{
				$this->core->url = $this->core->seo_url['forum'][$this->core->get_vars['f']] . $this->core->start;
			}

			unset($this->core->get_vars['f']);

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_url'];

		return;
	}

	/**
	* URL rewritting for memberlist.php
	* with nicknames and group name injection
	*/
	public function memberlist()
	{
		$this->core->path = $this->core->seo_path['phpbb_urlR'];

		if (@$this->core->get_vars['mode'] === 'viewprofile' && !@empty($this->core->seo_url['user'][$this->core->get_vars['u']]))
		{
			$this->core->url = $this->core->seo_url['user'][$this->core->get_vars['u']] . $this->core->seo_ext['user'];

			unset($this->core->get_vars['mode'], $this->core->get_vars['u']);

			return;
		}
		else if (@$this->core->get_vars['mode'] === 'group' && !@empty($this->core->seo_url['group'][$this->core->get_vars['g']]))
		{
			$paginate_method_name = $this->core->paginate_method['group'];

			$this->core->$paginate_method_name($this->core->seo_ext['group']);
			$this->core->url =  $this->core->seo_url['group'][$this->core->get_vars['g']] . $this->core->start;

			unset($this->core->get_vars['mode'], $this->core->get_vars['g']);

			return;
		}
		else if (@$this->core->get_vars['mode'] === 'team')
		{
			$this->core->url =  $this->core->seo_static['leaders'] . $this->core->seo_ext['leaders'];

			unset($this->core->get_vars['mode']);

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_url'];

		return;
	}

	/**
	* URL rewritting for search.php
	*/
	public function search()
	{
		if (isset($this->core->get_vars['fid']))
		{
			$this->core->get_vars = array();
			$this->core->url = $this->core->url_in;

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_urlR'];

		$user_id = !empty($this->core->get_vars['author_id']) ? $this->core->get_vars['author_id'] : (isset($this->core->seo_url['username'][rawurldecode(@$this->core->get_vars['author'])]) ? $this->core->seo_url['username'][rawurldecode(@$this->core->get_vars['author'])] : 0);

		if ($user_id && isset($this->core->seo_url['user'][$user_id]))
		{
			$sr = (@$this->core->get_vars['sr'] == 'topics' ) ? 'topics' : 'posts';

			$paginate_method_name = $this->core->paginate_method['user'];

			// Filter default params
			$this->core->filter_get_var($this->core->get_filter['search']);
			$this->core->$paginate_method_name($this->core->seo_ext['user']);
			$this->core->url = $this->core->seo_url['user'][$user_id] . $this->core->seo_delim['sr'] . $sr . $this->core->start;

			unset($this->core->get_vars['author_id'], $this->core->get_vars['author'], $this->core->get_vars['sr']);

			return;
		}
		else if ($this->core->seo_opt['profile_noids'] && !empty($this->core->get_vars['author']))
		{
			$sr = (@$this->core->get_vars['sr'] == 'topics') ? '/topics' : '/posts';

			// Filter default params
			$this->core->filter_get_var($this->core->get_filter['search']);
			$this->core->rewrite_pagination_page();
			$this->core->url = $this->core->seo_static['user'] . '/' . $this->core->seo_url_encode($this->core->get_vars['author']) . $sr . $this->core->start;

			unset($this->core->get_vars['author'], $this->core->get_vars['author_id'], $this->core->get_vars['sr']);

			return;
		}
		else if (!empty($this->core->get_vars['search_id']))
		{
			switch ($this->core->get_vars['search_id'])
			{
				case 'active_topics':
					$paginate_method_name = $this->core->paginate_method['atopic'];

					$this->core->filter_get_var($this->core->get_filter['search']);
					$this->core->$paginate_method_name($this->core->seo_ext['atopic']);
					$this->core->url = $this->core->seo_static['atopic'] . $this->core->start;

					unset($this->core->get_vars['search_id'], $this->core->get_vars['sr']);

					if (@$this->core->get_vars['st'] == 7)
					{
						unset($this->core->get_vars['st']);
					}

					return;
				case 'unanswered':
					$paginate_method_name = $this->core->paginate_method['utopic'];

					$this->core->filter_get_var($this->core->get_filter['search']);
					$this->core->$paginate_method_name($this->core->seo_ext['utopic']);
					$this->core->url = $this->core->seo_static['utopic'] . $this->core->start;

					unset($this->core->get_vars['search_id']);

					if (@$this->core->get_vars['sr'] == 'topics')
					{
						unset($this->core->get_vars['sr']);
					}

					return;
				case 'egosearch':
					$this->core->set_user_url($this->user->data['username'], $this->user->data['user_id']);
					$this->core->url = $this->core->seo_url['user'][$this->user->data['user_id']] . $this->core->seo_delim['sr'] . 'topics' . $this->core->seo_ext['user'];

					unset($this->core->get_vars['search_id']);

					return;
				case 'newposts':
					$paginate_method_name = $this->core->paginate_method['npost'];

					$this->core->filter_get_var($this->core->get_filter['search']);
					$this->core->$paginate_method_name($this->core->seo_ext['npost']);
					$this->core->url = $this->core->seo_static['npost'] . $this->core->start;

					unset($this->core->get_vars['search_id']);

					if (@$this->core->get_vars['sr'] == 'topics')
					{
						unset($this->core->get_vars['sr']);
					}

					return;
				case 'unreadposts':
					$paginate_method_name = $this->core->paginate_method['urpost'];

					$this->core->filter_get_var($this->core->get_filter['search']);
					$this->core->$paginate_method_name($this->core->seo_ext['urpost']);
					$this->core->url = $this->core->seo_static['urpost'] . $this->core->start;

					unset($this->core->get_vars['search_id']);

					if (@$this->core->get_vars['sr'] == 'topics')
					{
						unset($this->core->get_vars['sr']);
					}

					return;
			}
		}

		$this->core->path = $this->core->seo_path['phpbb_url'];

		return;
	}

	/**
	* URL rewritting for download/file.php
	*/
	public function phpbb_files()
	{
		$this->core->filter_url($this->core->stop_vars);
		$this->core->path = $this->core->seo_path['phpbb_filesR'];

		if (isset($this->core->get_vars['id']) && !empty($this->core->seo_url['file'][$this->core->get_vars['id']]))
		{
			$this->core->url = $this->core->seo_url['file'][$this->core->get_vars['id']];

			if (!empty($this->core->get_vars['t']))
			{
				$this->core->url .= $this->core->seo_delim['file'] . $this->core->seo_static['thumb'];
			}
			/*
			else if (@$this->core->get_vars['mode'] == 'view')
			{
				$this->core->url .= $this->core->seo_delim['file'] . 'view';
			}
			*/

			$this->core->url .= $this->core->seo_delim['file'] . $this->core->get_vars['id'];

			unset($this->core->get_vars['id'], $this->core->get_vars['t'], $this->core->get_vars['mode']);

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_files'];

		return;
	}

	/**
	* URL rewritting for index.php
	*/
	public function index()
	{
		$this->core->path = $this->core->seo_path['phpbb_urlR'];

		if ($this->core->filter_url($this->core->stop_vars))
		{
			$this->core->url = $this->core->seo_static['index'] . $this->core->seo_ext['index'];

			return;
		}

		$this->core->path = $this->core->seo_path['phpbb_url'];

		return;
	}
}
