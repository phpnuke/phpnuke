<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\acp;

/**
* @package acp
*/
class sitemap_module
{
	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

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

	/** @var ContainerInterface */
	protected $phpbb_container;

	/** @var string */
	public $u_action;

	function main($id, $mode)
	{
		global $cache, $config, $db, $request, $template, $user, $phpbb_root_path, $phpEx, $phpbb_container;

		$this->driver = $cache->get_driver();
		$this->config = $config;
		$this->config_text = $phpbb_container->get('config_text');
		$this->db = $db;
		$this->log = $phpbb_container->get('log');
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;

		$this->tpl_name = 'acp_sitemap';
		$this->page_title = $this->user->lang['SEOMAP'];
		add_form_key('acp_seo_sitemap');
		$action = $this->request->variable('action', '');
		$error = array();

		$sitemap_url = generate_board_url() . '/sitemap.xml';

		if ($action == 'sync')
		{
			// Try to override limits
			@set_time_limit(0);
			$mem_limit = @ini_get('memory_limit');

			if (!empty($mem_limit))
			{
				$unit = strtolower(substr($mem_limit, -1, 1));
				$mem_limit = (int) $mem_limit;

				if ($unit == 'k')
				{
					$mem_limit = floor($mem_limit / 1024);
				}
				else if ($unit == 'g')
				{
					$mem_limit *= 1024;
				}
				else if (is_numeric($unit))
				{
					$mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
				}

				$mem_limit = max(128, $mem_limit) . 'M';
			}
			else
			{
				$mem_limit = '128M';
			}

			@ini_set('memory_limit', $mem_limit);

			$start = $this->request->variable('start', 0);

			// Limit updated posts to a specified value
			$limit = 5000;

			// Do the sync
			$sql = 'SELECT post_id FROM ' . POSTS_TABLE . '
					ORDER BY post_id ASC';
			$result = $this->db->sql_query_limit($sql, $limit, $start);

			$post_ids = array();

			while ($row = $this->db->sql_fetchrow($result))
			{
				$post_ids[] = (int) $row['post_id'];
			}
			$this->db->sql_freeresult($result);

			if (sizeof($post_ids))
			{
				$upd_sql = 'UPDATE ' . POSTS_TABLE . ' SET post_modified =
					CASE
						WHEN post_time > post_edit_time THEN post_time
						ELSE post_edit_time
					END
					WHERE ' . $this->db->sql_in_set('post_id', $post_ids) . '
					AND post_modified = 0';
				$this->db->sql_query($upd_sql);
			}

			$sql = 'SELECT COUNT(post_id) AS postcount FROM ' . POSTS_TABLE;
			$result = $this->db->sql_query($sql, 3600);
			$count = (int) $this->db->sql_fetchfield('postcount');
			$this->db->sql_freeresult($result);

			if ($count > ($start + $limit))
			{
				$percent = round((($start + $limit) / $count) * 100, 2);
				$message = sprintf($this->user->lang['SEOMAP_SYNC_PROCESS'], $percent, ($start + $limit), $count);

				$next = $start + $limit;
				meta_refresh(1, append_sid("{$phpbb_root_path}adm/index.$phpEx?i=-shredder-sitemap-acp-sitemap_module&amp;action=sync&amp;start=$next"));

				trigger_error($message . '<br />');
			}
			else
			{
				$this->driver->destroy('_sitemap_seo_file');

				trigger_error(sprintf($this->user->lang['SEOMAP_SYNC_COMPLETE'], append_sid("{$phpbb_root_path}adm/index.$phpEx?i=-shredder-sitemap-acp-sitemap_module&amp;mode=settings")));
			}
		}
		else
		{
			$sql = 'SELECT 1 FROM ' . POSTS_TABLE . '
				WHERE post_modified = 0';
			$this->db->sql_query_limit($sql, 1);

			if ($this->db->sql_affectedrows())
			{
				$error[] = sprintf($this->user->lang['SEOMAP_SYNC_REQ'], append_sid("{$phpbb_root_path}adm/index.$phpEx?i=-shredder-sitemap-acp-sitemap_module&amp;action=sync"));
			}

			if ($this->request->is_set_post('submit'))
			{
				if (!check_form_key('acp_seo_sitemap'))
				{
					trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$this->config->set('sitemap_seo_cache', max(0, $this->request->variable('sitemap_seo_cache', 0)));
				$this->config->set('sitemap_seo_url_limit', min(50000, max(1, $this->request->variable('sitemap_seo_url_limit', 0))));
				$this->config->set('sitemap_seo_batch_size', $this->request->variable('sitemap_seo_batch_size', $this->config['sitemap_seo_batch_size']));
				$this->config_text->set('sitemap_seo_excluded', implode(',', $this->request->variable('sitemap_seo_excluded', array(0))));
				$this->config->set('sitemap_seo_prior_0', min(1, max(0, $this->request->variable('sitemap_seo_prior_0', $this->config['sitemap_seo_prior_0']))));
				$this->config->set('sitemap_seo_prior_1', min(1, max(0, $this->request->variable('sitemap_seo_prior_1', $this->config['sitemap_seo_prior_1']))));
				$this->config->set('sitemap_seo_prior_2', min(1, max(0, $this->request->variable('sitemap_seo_prior_2', $this->config['sitemap_seo_prior_2']))));
				$this->config->set('sitemap_seo_prior_3', min(1, max(0, $this->request->variable('sitemap_seo_prior_3', $this->config['sitemap_seo_prior_3']))));
				$this->config->set('sitemap_seo_prior_4', min(1, max(0, $this->request->variable('sitemap_seo_prior_4', $this->config['sitemap_seo_prior_4']))));
				$this->config->set('sitemap_seo_prior_f', min(1, max(0, $this->request->variable('sitemap_seo_prior_f', $this->config['sitemap_seo_prior_f']))));
				$this->config->set('sitemap_seo_freq_0', $this->request->variable('sitemap_seo_freq_0', $this->config['sitemap_seo_freq_0']));
				$this->config->set('sitemap_seo_freq_1', $this->request->variable('sitemap_seo_freq_1', $this->config['sitemap_seo_freq_1']));
				$this->config->set('sitemap_seo_freq_2', $this->request->variable('sitemap_seo_freq_2', $this->config['sitemap_seo_freq_2']));
				$this->config->set('sitemap_seo_freq_3', $this->request->variable('sitemap_seo_freq_3', $this->config['sitemap_seo_freq_3']));
				$this->config->set('sitemap_seo_freq_4', $this->request->variable('sitemap_seo_freq_4', $this->config['sitemap_seo_freq_4']));
				$this->config->set('sitemap_seo_freq_f', $this->request->variable('sitemap_seo_freq_f', $this->config['sitemap_seo_freq_f']));

				$this->driver->destroy('_sitemap_seo_file');

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEOMAP_SETTINGS_UPDATED');
				trigger_error($this->user->lang['SEOMAP_SAVED'] . adm_back_link($this->u_action));
			}
		}

		$this->template->assign_vars(array(
			'SEOMAP_VERSION'			=> sprintf($this->user->lang['SEOMAP_VERSION'], '<strong>' . $this->config['sitemap_seo_version'] . '</strong>'),
			'SEOMAP_CACHE_TIME'			=> $this->config['sitemap_seo_cache'],
			'SEOMAP_URL'				=> sprintf($this->user->lang['SEOMAP_URL'], $sitemap_url, $sitemap_url),
			'SEOMAP_URL_COUNT'			=> sprintf($this->user->lang['SEOMAP_URL_COUNT'], '<strong>' . $this->config['sitemap_seo_url_count'] . '</strong>'),
			'SEOMAP_URL_LIMIT'			=> $this->config['sitemap_seo_url_limit'],
			'SEOMAP_BATCH_SIZE'			=> $this->config['sitemap_seo_batch_size'],
			'SEOMAP_EXCLUDED_FORUMS'	=> make_forum_select(explode(',', $this->config_text->get('sitemap_seo_excluded')), false, false, true),
			'SEOMAP_PRIORITY_0'			=> $this->config['sitemap_seo_prior_0'],
			'SEOMAP_PRIORITY_1'			=> $this->config['sitemap_seo_prior_1'],
			'SEOMAP_PRIORITY_2'			=> $this->config['sitemap_seo_prior_2'],
			'SEOMAP_PRIORITY_3'			=> $this->config['sitemap_seo_prior_3'],
			'SEOMAP_PRIORITY_4'			=> $this->config['sitemap_seo_prior_4'],
			'SEOMAP_PRIORITY_F'			=> $this->config['sitemap_seo_prior_f'],
			'SEOMAP_FREQ_0'				=> $this->config['sitemap_seo_freq_0'],
			'SEOMAP_FREQ_1'				=> $this->config['sitemap_seo_freq_1'],
			'SEOMAP_FREQ_2'				=> $this->config['sitemap_seo_freq_2'],
			'SEOMAP_FREQ_3'				=> $this->config['sitemap_seo_freq_3'],
			'SEOMAP_FREQ_4'				=> $this->config['sitemap_seo_freq_4'],
			'SEOMAP_FREQ_F'				=> $this->config['sitemap_seo_freq_f'],

			'ERROR_MSG'				=> implode('<br />', $error),
			'S_ARTICLE'					=> defined('POST_ARTICLE'),
			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'U_ACTION'					=> $this->u_action,
		));
	}
}
