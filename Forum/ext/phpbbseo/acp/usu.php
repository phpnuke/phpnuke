<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpBB-SEO.ir
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\acp;

/**
* phpBB_SEO Class
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
class usu
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbbseo\usu\core\core */
	protected $core;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\log\log */
	protected $phpbb_log;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var \phpbb\language\language */
	protected $language;

	var $u_action;
	var $new_config = [];
	var $dyn_select = [];
	var $forum_ids = [];
	var $array_type_cfg = [];
	var $multiple_options = [];
	var $modrtype_lang = [];
	var $lengh_limit = 20;
	var $word_limit = 3;
	var $seo_unset_opts = [];

	function main($id, $mode)
	{
		global $config, $db, $user, $template, $request, $language, $phpbb_log;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $phpbb_container;

		$this->config = $config;
		$this->core = $phpbb_container->get('phpbbseo.usu.core');
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_log = $phpbb_log;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
		$this->language = $language;

		$this->user->add_lang_ext('phpbbseo/usu', 'acp_usu');

		$action	= $this->request->variable('action', '');

		$submit = $this->request->is_set_post('submit');
		$cancel = $this->request->variable('cancel', '');

		$form_key = 'acp_seo_usu';
		add_form_key($form_key);
		$display_vars = [];

		// --> Zero Dupe
		if (@isset($this->core->seo_opt['zero_dupe']))
		{
			$this->multiple_options['zero_dupe']['post_redir_values'] = ['off' => 'off', 'post' => 'post', 'guest' => 'guest', 'all' => 'all']; // do not change

			$this->multiple_options['zero_dupe']['post_redir_lang'] = [
				'off'	=> $this->language->lang('ACP_ZERO_DUPE_OFF'),
				'post'	=> $this->language->lang('ACP_ZERO_DUPE_MSG'),
				'guest'	=> $this->language->lang('ACP_ZERO_DUPE_GUEST'),
				'all'	=> $this->language->lang('ACP_ZERO_DUPE_ALL')
			];
		}

		// <-- Mod rewrite selector
		if ($this->core->modrtype == 1)
		{
			$this->seo_unset_opts = ['cache_layer', 'rem_ids'];
		}
		else if (!$this->core->seo_opt['cache_layer'])
		{
			$this->seo_unset_opts = ['rem_ids'];
		}

		$this->modrtype_lang = $this->set_phpbb_seo_links();
		$this->multiple_options['modrtype_lang'] = $this->modrtype_lang['titles'];

		if (@isset($this->core->seo_opt['modrtype']))
		{
			$this->multiple_options['modrtype_values'] = [1 => 1, 2 => 2, 3 => 3]; // do not change;
		}

		// <-- Mod rewrite selector
		foreach ($this->seo_unset_opts as $opt)
		{
			if ($optkey = array_search($opt, $this->core->cache_config['dynamic_options']))
			{
				unset($this->core->cache_config['dynamic_options'][$optkey]);
			}
		}

		// We need shorter URLs with Virtual Folder Trick
		if ($this->core->seo_opt['virtual_folder'])
		{
			$this->lengh_limit = 20;
			$this->word_limit = 3;
		}
		else
		{
			$this->lengh_limit = 30;
			$this->word_limit = 5;
		}

		$related_installed = false;

		switch ($mode)
		{
			case 'settings':

				$display_vars['vars'] = [];
				$display_vars['title'] = 'ACP_PHPBB_SEO_CLASS';
				$display_vars['ACP_PHPBB_SEO_CLASS_EXPLAIN'] = $this->language->lang('ACP_PHPBB_SEO_CLASS_EXPLAIN', $this->modrtype_lang['ulink'], $this->modrtype_lang['uforumlink'], '</p><hr/><p><b>' . $this->language->lang('ACP_PHPBB_SEO_MODE') . ' : ' . $this->modrtype_lang['link'] . ' - ( ' . $this->modrtype_lang['forumlink'] . ' )</b></p><hr/><p>');

				$i = 2;
				$display_vars['vars']['legend1'] = 'ACP_PHPBB_SEO_CLASS';

				foreach ($this->core->cache_config['dynamic_options'] as $optionname => $optionvalue)
				{
					if (is_string($optionvalue) && @is_bool($this->core->seo_opt[$optionvalue]))
					{
						if ($optionvalue == 'virtual_root')
						{
							if (!$this->core->seo_path['phpbb_script'])
							{
								continue;
							}

							if (empty($this->config['force_server_vars']))
							{
								// we assume that the force server var will not break everything
								// since this is in use in all cases in USU
								$this->config->set('force_server_vars', 1);
							}
						}

						$display_vars['vars'][$optionvalue] = ['lang' => $optionvalue, 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true, 'lang_explain' => $optionvalue . '_explain'];
						$this->new_config[$optionvalue] = $this->core->seo_opt[$optionvalue];
					}
					else if (@isset($this->multiple_options[$optionvalue . '_values']))
					{
						$this->dyn_select[$optionvalue] = $this->multiple_options[$optionvalue . '_values'];
						$display_vars['vars'][$optionvalue] = ['lang' => $optionvalue, 'validate' => 'string', 'type' => 'select', 'method' => 'select_string', 'explain' => true, 'lang_explain' => $optionvalue . '_explain'];
						$this->new_config[$optionvalue] = $this->core->seo_opt[$optionvalue];
					}
					else if (is_array($optionvalue))
					{
						$display_vars['vars']['legend' . $i] = $optionname;
						$i++;

						foreach ($optionvalue as $key => $value)
						{
							$this->array_type_cfg[$optionname . '_' . $key] = ['main' => $optionname, 'sub' => $key];

							if (is_bool($value))
							{
								$display_vars['vars'][$optionname . '_' . $key] = ['lang' => $optionname . '_' . $key, 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true, 'lang_explain' => $optionname . '_' . $key . '_explain'];
								$this->new_config[$optionname . '_' . $key] = $this->core->seo_opt[$optionname][$key];
							}
							else if (@isset($this->multiple_options[$optionname][$key . '_values']))
							{
								$this->dyn_select[$optionname . '_' . $key] = $this->multiple_options[$optionname][$key . '_values'];
								$display_vars['vars'][$optionname . '_' . $key] = ['lang' => $optionname . '_' . $key, 'validate' => 'string', 'type' => 'select', 'method' => 'select_string', 'explain' => true, 'lang_explain' => $optionname . '_' . $key . '_explain'];
								$this->new_config[$optionname . '_' . $key] = $this->core->seo_opt[$optionname][$key];
							}
							else
							{
								$display_vars['vars'][$optionname . '_' . $key] = ['lang' => $optionname . '_' . $key, 'validate' => 'string:0:50', 'type' => 'text:50:50', 'explain' => true, 'lang_explain' => $optionname . '_' . $key . '_explain'];
								$this->new_config[$optionname . '_' . $key] = $this->core->seo_opt[$optionname][$key];
							}
						}
					}
				}

				break;

			case 'forum_url':

				$forbidden = [
					$this->core->seo_static['forum'],
					$this->core->seo_static['global_announce'],
					$this->core->seo_static['user'],
					$this->core->seo_static['topic'],
					$this->core->seo_static['atopic'],
					$this->core->seo_static['utopic'],
					$this->core->seo_static['leaders'],
					$this->core->seo_static['post'],
					$this->core->seo_static['group'],
					$this->core->seo_static['npost'],
					$this->core->seo_static['index'],
				];

				if ($this->core->modrtype == 1 || !$this->core->seo_opt['cache_layer'])
				{
					trigger_error($this->language->lang('ACP_NO_FORUM_URL') . preg_replace('`(&amp;|&|\?)mode=forum_url`i', '', adm_back_link($this->u_action)));

					break;
				}

				$display_vars['title'] = 'ACP_FORUM_URL';
				$display_vars['ACP_FORUM_URL_EXPLAIN'] = $this->language->lang('ACP_FORUM_URL_EXPLAIN') . '</p><hr/><p><b>' . $this->language->lang('ACP_PHPBB_SEO_VERSION') . ' : ' . $this->modrtype_lang['link'] . ' - ( ' . $this->modrtype_lang['forumlink'] . ' )</b></p><hr/><p>';

				$display_vars['vars'] = [];
				$display_vars['vars']['legend1'] = 'ACP_FORUM_URL';

				$sql = "SELECT forum_id, forum_name
					FROM " . FORUMS_TABLE . "
					ORDER BY left_id ASC";
				$result = $this->db->sql_query($sql);

				$forum_url_title = $error_cust = '';

				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->forum_ids[$row['forum_id']] = $row['forum_name'];
				}

				$this->db->sql_freeresult($result);

				// take care of deleted forums
				foreach ($this->core->cache_config['forum_urls'] as $fid => $null)
				{
					if (!isset($this->forum_ids[$fid]))
					{
						unset($this->core->cache_config['forum_urls'][$fid]);
					}
				}

				foreach ($this->forum_ids as $forum_id => $forum_name)
				{
					$error_cust = '';

					// Is the URL cached already ?
					if (empty($this->core->cache_config['forum_urls'][$forum_id]))
					{
						// Suggest the one from the title
						$forum_url_title = $this->core->format_url($forum_name, $this->core->seo_static['forum']);

						if (!in_array($forum_url_title, $forbidden))
						{
							if (array_search($forum_url_title, $this->core->cache_config['forum_urls']))
							{
								$this->new_config['forum_url' . $forum_id] = $forum_url_title .  $this->core->seo_delim['forum'] . $forum_id;
								$error_cust = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_DUPE', $forum_url_title) . '</li>';
							}
							else
							{
								$this->new_config['forum_url' . $forum_id] = $forum_url_title . (@$this->core->cache_config['settings']['rem_ids'] ? '': $this->core->seo_delim['forum'] . $forum_id);
							}
						}
						else
						{
							$this->new_config['forum_url' . $forum_id] = $forum_url_title . $this->core->seo_delim['forum'] . $forum_id;
							$error_cust = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_RESERVED', $forum_url_title) . '</li>';
						}

						$title = '<b style="color:red">' . $forum_name . ' - ID ' . $forum_id . '</b>';
						$status_msg = '<b style="color:red">' . $this->language->lang('SEO_CACHE_URL_NOT_OK') . '</b>';
						$status_msg .= '<br/><span style="color:red">' . $this->language->lang('SEO_CACHE_URL') . '&nbsp;:</span>&nbsp;' . $this->new_config['forum_url' . $forum_id] . $this->core->seo_ext['forum'];

						$display_vars['vars']['forum_url' . $forum_id] = [
							'lang'					=> $title,
							'validate'				=> 'string',
							'type'					=> 'custom',
							'method'				=> 'forum_url_input',
							'explain'				=> true,
							'lang_explain_custom'	=> $status_msg,
							'append'				=> $this->seo_advices($this->new_config['forum_url' . $forum_id], $forum_id, false, $error_cust),
						];
					}
					else
					{
						// Cached
						$this->new_config['forum_url' . $forum_id] = $this->core->cache_config['forum_urls'][$forum_id];

						$title = '<b style="color:green">' . $forum_name . ' - ID ' . $forum_id . '</b>';
						$status_msg = '<span style="color:green">' . $this->language->lang('SEO_CACHE_URL_OK') . '&nbsp;:</span>&nbsp;<b style="color:green">' . $this->new_config['forum_url' . $forum_id] . '</b>';
						$status_msg .= '<br/><span style="color:green">' . $this->language->lang('SEO_CACHE_URL') . '&nbsp;:</span>&nbsp;' . $this->new_config['forum_url' . $forum_id] . $this->core->seo_ext['forum'];

						$display_vars['vars']['forum_url' . $forum_id] = [
							'lang'					=> $title,
							'validate'				=> 'string:0:100',
							'type'					=> 'custom',
							'method'				=> 'forum_url_input',
							'explain'				=> true,
							'lang_explain_custom'	=> $status_msg,
							'append'				=> $this->seo_advices($this->new_config['forum_url' . $forum_id], $forum_id, true),
						];
					}
				}

				break;

			case 'server':

				$display_vars['title'] = 'ACP_REWRITE_CONF';
				$display_vars['ACP_REWRITE_CONF_EXPLAIN'] =  $this->language->lang('ACP_REWRITE_CONF_EXPLAIN') . '</p><hr/><p><b>' . $this->language->lang('ACP_PHPBB_SEO_VERSION') . ' : ' . $this->modrtype_lang['link'] . ' - ( ' . $this->modrtype_lang['forumlink'] . ' )</b></p><p>';

				$display_vars['vars'] = [];
				$display_vars['vars']['legend1'] = 'ACP_REWRITE_CONF';
				if ($this->core->seo_path['phpbb_script'] && !$this->core->seo_opt['virtual_root'])
				{
					$display_vars['vars']['rbase'] = ['lang' => 'SEO_SERVER_CONF_RBASE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true];
				}
				$display_vars['vars']['save'] = ['lang' => 'SEO_SERVER_CONF_SAVE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true];
				$display_vars['vars']['more_options'] = ['lang' => 'SEO_MORE_OPTION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true];
				$this->new_config['save'] = false;
				$cfg_array = ($this->request->is_set('config')) ? utf8_normalize_nfc($this->request->variable('config', ['' => ''], true)) : $this->new_config;
				$this->new_config['more_options'] = isset($cfg_array['more_options']) ? $cfg_array['more_options'] : false;
				$this->new_config['slash'] = isset($cfg_array['slash']) ? $cfg_array['slash'] : false;
				$this->new_config['wslash'] = isset($cfg_array['wslash']) ? $cfg_array['wslash'] : false;
				$this->new_config['rbase'] = isset($cfg_array['rbase']) ? $cfg_array['rbase'] : false;

				if ($this->new_config['more_options'])
				{
					$display_vars['vars']['slash'] = ['lang' => 'SEO_SERVER_CONF_SLASH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true];
					$display_vars['vars']['wslash'] = ['lang' => 'SEO_SERVER_CONF_WSLASH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true];
				}

				// Dirty yet simple templating
				$display_vars['ACP_REWRITE_CONF_EXPLAIN'] = $this->language->lang('ACP_REWRITE_CONF_EXPLAIN') . $this->seo_server_conf();

				$this->template->assign_vars([
					'S_SEO_HTACCESS'	=> 1,
				]);

				break;

			case 'extended':

				$display_vars = [
					'title'	=> 'ACP_SEO_EXTENDED',
					'vars'	=> [
						'legend1'		=> 'SEO_EXTERNAL_LINKS',
						'seo_ext_links'		=> ['lang' => 'SEO_EXTERNAL_LINKS', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true, 'default' => 1],
						'seo_ext_subdomain'	=> ['lang' => 'SEO_EXTERNAL_SUBDOMAIN', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true, 'default' => 0],
						'seo_ext_classes'	=> ['lang' => 'SEO_EXTERNAL_CLASSES', 'validate' => 'string', 'type' => 'text:40:250', 'explain' => true, 'default' => ''],
					],
				];

				// Related topics
				if (!empty($this->config['seo_related_on']))
				{
					$related_installed = true;

					$this->user->add_lang_ext('phpbbseo/usu', 'acp_usu_install');

					$display_vars['vars'] += [
						'legend2'			=> 'SEO_RELATED_TOPICS',
						'seo_related'			=> ['lang' => 'SEO_RELATED', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true, 'append' => !empty($this->config['seo_related']) ? '<br/>' . (!empty($this->config['seo_related_fulltext']) ? $this->language->lang('FULLTEXT_INSTALLED') : $this->language->lang('FULLTEXT_NOT_INSTALLED')) : '', 'default' => 0],
						'seo_related_check_ignore'	=> ['lang' => 'SEO_RELATED_CHECK_IGNORE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true, 'default' => 0],
						'seo_related_limit'		=> ['lang' => 'SEO_RELATED_LIMIT', 'validate' => 'int:2:25', 'type' => 'text:3:4', 'explain' => true, 'default' => 5],
						'seo_related_allforums'		=> ['lang' => 'SEO_RELATED_ALLFORUMS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true, 'default' => 0],
					];
				}

				// dynamic meta tag mod
				if (!empty($this->config['seo_meta_on']))
				{
					$display_vars['vars'] += [
						'legend3' 			=> 'SEO_META',
						'seo_meta_title'		=> ['lang' => 'SEO_META_TITLE', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => $this->config['sitename']],
						'seo_meta_desc'			=> ['lang' => 'SEO_META_DESC', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => $this->config['site_desc']],
						'seo_meta_desc_limit'		=> ['lang' => 'SEO_META_DESC_LIMIT', 'validate' => 'int:5:40', 'type' => 'text:3:4', 'explain' => true, 'default' => 25],
						'seo_meta_bbcode_filter'	=> ['lang' => 'SEO_META_BBCODE_FILTER', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => 'img|url|flash|code'],
						'seo_meta_keywords'		=> ['lang' => 'SEO_META_KEYWORDS', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => $this->config['site_desc']],
						'seo_meta_keywords_limit'	=> ['lang' => 'SEO_META_KEYWORDS_LIMIT', 'validate' => 'int:5:40', 'type' => 'text:3:4', 'explain' => true, 'default' => 15],
						'seo_meta_min_len'		=> ['lang' => 'SEO_META_MIN_LEN', 'validate' => 'int:0:10', 'type' => 'text:3:4', 'explain' => true, 'default' => 2],
						'seo_meta_check_ignore'		=> ['lang' => 'SEO_META_CHECK_IGNORE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true, 'default' => 0],
						'seo_meta_lang'			=> ['lang' => 'SEO_META_LANG', 'validate' => 'lang', 'type' => 'select', 'method' => 'language_select', 'params' => ['{CONFIG_VALUE}'], 'explain' => true,  'default' => $this->config['default_lang']],
						'seo_meta_copy'			=> ['lang' => 'SEO_META_COPY', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => $this->config['sitename']],
						'seo_meta_file_filter'		=> ['lang' => 'SEO_META_FILE_FILTER', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => 'ucp'],
						'seo_meta_get_filter'		=> ['lang' => 'SEO_META_GET_FILTER', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => 'style,hilit,sid'],
						'seo_meta_robots'		=> ['lang' => 'SEO_META_ROBOTS', 'validate' => 'string:0:225', 'type' => 'text:25:150', 'explain' => true, 'default' => 'index,follow'],
						'seo_meta_noarchive'		=> ['lang' => 'SEO_META_NOARCHIVE', 'validate' => 'string:0:225', 'multiple_validate' => 'int', 'type' => 'custom', 'method' => 'select_multiple', 'params' => ['{CONFIG_VALUE}', '{KEY}', $this->forum_select()], 'explain' => true, 'default' => ''],

						'seo_meta_og'			=> ['lang' => 'SEO_META_OG', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true, 'default' => 0],
					];

					// Open Graph
					if (!empty($this->config['seo_meta_og']))
					{
						$display_vars['vars'] += [
							'fb_app_id'	=> ['lang' => 'SEO_META_FB_APP_ID', 'validate' => 'string:0:225', 'type' => 'text:40:250', 'explain' => true, 'default' => ''],
						];
					}
				}

				// Optimal title
				if (!empty($this->config['seo_optimal_title_on']))
				{
					$display_vars['vars'] += [
						'legend'		=> 'SEO_PAGE_TITLES',
						'seo_append_sitename'	=> ['lang' => 'SEO_APPEND_SITENAME', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true, 'default' => 0],
					];
				}

				// install if necessary
				foreach ($display_vars['vars'] as $config_name => $config_setup)
				{
					if (strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					if (!isset($this->config[$config_name]))
					{
						$this->config->set($config_name, $config_setup['default']);
						unset($display_vars['vars'][$config_name]['default']);
					}
				}

				$this->new_config = $this->config;
				break;

			case 'sync_url':
				$sync_url = $this->request->variable('sync', '');
				$redirect_url = "{$phpbb_admin_path}index.$phpEx?i=-phpbbseo-usu-acp-usu&mode=sync_url";
				$go = max(0, $this->request->variable('go', 0));

				if ($cancel || !$go)
				{
					trigger_error($this->language->lang('SYNC_WARN') . '<br/><br/><b> &bull; <a href="' . append_sid($redirect_url, "go=1&amp;sync=sync") . '">' . $this->language->lang('SYNC_TOPIC_URLS') . '</a><br/><br/> &bull; <a href="' . append_sid($redirect_url, "go=1&amp;sync=reset") . '" >' . $this->language->lang('SYNC_RESET_TOPIC_URLS') . '</a></b>');
				}

				$starttime = microtime(true);
				$start = max(0, $this->request->variable('start', 0));
				$limit = max(100, $this->request->variable('limit', 0));

				// Do not go over 1000 topic in a row
				$limit = min(1000, $limit);

				$poll_processed = 0;
				$forum_data = [];
				$url_updated = 0;

				if ($sync_url === 'sync')
				{
					// get all forum info
					$sql = 'SELECT forum_id, forum_name FROM ' . FORUMS_TABLE;
					$result = $db->sql_query($sql);
					while ($row = $db->sql_fetchrow($result))
					{
						$forum_data[$row['forum_id']] = $row['forum_name'];
						$this->core->set_url($row['forum_name'], $row['forum_id'], $this->core->seo_static['forum']);
					}
					$db->sql_freeresult($result);

					// let's work
					$sql = 'SELECT * FROM ' . TOPICS_TABLE . '
						ORDER BY topic_id ASC';
					$result = $db->sql_query_limit($sql, $limit, $start);
					while ($row = $db->sql_fetchrow($result))
					{
						$forum_id = (int) $row['forum_id'];
						$topic_id = (int) $row['topic_id'];
						$_parent = $row['topic_type'] == POST_GLOBAL ? $this->core->seo_static['global_announce'] : $this->core->seo_url['forum'][$forum_id];
						if ( !$this->core->check_url('topic', $row['topic_url'], $_parent))
						{
							if (!empty($row['topic_url']))
							{
								// Here we get rid of the seo delim (-t) and put it back even in simple mod
								// to be able to handle all cases at once
								$_url = preg_replace('`' . $this->core->seo_delim['topic'] . '$`i', '', $row['topic_url']);
								$_title = $this->core->get_url_info('topic', $_url . $this->core->seo_delim['topic'] . $topic_id, 'title');
							}
							else
							{
								$_title = $this->core->modrtype > 2 ? censor_text($row['topic_title']) : '';
							}
							unset($this->core->seo_url['topic'][$topic_id]);
							$row['topic_url'] = $this->core->get_url_info('topic', $this->core->prepare_url( 'topic', $_title, $topic_id, $_parent, (( empty($_title) || ($_title == $this->core->seo_static['topic']) ) ? true : false) ), 'url');
							unset($this->core->seo_url['topic'][$topic_id]);
							if ($row['topic_url'])
							{
								// Update the topic_url field for later re-use
								$sql = "UPDATE " . TOPICS_TABLE . " SET topic_url = '" . $db->sql_escape($row['topic_url']) . "'
									WHERE topic_id = $topic_id";
								$db->sql_query($sql);
								$url_updated++;
							}
						}
					}
					$db->sql_freeresult($result);
					$sql = 'SELECT count(topic_id) as topic_cnt FROM ' . TOPICS_TABLE;
					$result = $db->sql_query($sql);
					$cnt = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					if ($cnt['topic_cnt'] > ($start + $limit))
					{
						$endtime = microtime(true);
						$duration = $endtime - $starttime;
						$speed = round($limit/$duration, 2);
						$percent = round((($start + $limit) / $cnt['topic_cnt']) * 100, 2);
						$message = $language->lang('SYNC_PROCESSING', $percent, ($start + $limit), $cnt['topic_cnt'], $limit, $speed, round($duration, 2) , round((($cnt['topic_cnt'] - $start)/$speed)/60, 2));
						if ($url_updated)
						{
							$message.= $language->lang('SYNC_ITEM_UPDATED', '<br/>' . $url_updated);
						}
						$new_limit = ($duration < 10) ? $limit + 50 : $limit - 10;
						meta_refresh(1, append_sid($redirect_url, 'go=1&amp;start=' . ($start + $limit) . "&amp;limit=$new_limit&amp;sync=sync"));
						trigger_error("$message<br/>");
					}
					else
					{
						trigger_error($language->lang('SYNC_COMPLETE') . $language->lang('RETURN_INDEX', '<br/><br/><a href="' . append_sid($redirect_url) . '" >', '</a>'));
					}
				}
				else if ($sync_url === 'reset')
				{
					if (confirm_box(true))
					{
						$sql = "UPDATE " . TOPICS_TABLE . " SET topic_url = ''";
						$db->sql_query($sql);
						trigger_error($language->lang('SYNC_RESET_COMPLETE') . '<br/><br/><b> &bull; <a href="' . append_sid($redirect_url, "go=1&amp;sync=sync") . '">' . $language->lang('SYNC_TOPIC_URLS') . '</a><br/><br/> &bull; ' . $language->lang('RETURN_INDEX', '<a href="' . append_sid($redirect_url) . '" >', '</a></b>'));
					}
					else
					{
						confirm_box(false, $language->lang('CONFIRM_OPERATION'), build_hidden_fields(['go' => '1', 'sync' => 'reset']), 'confirm_body.html');
					}
				}
				else
				{
					trigger_error($language->lang('SYNC_WARN') . '<br/><br/><b> &bull; <a href="' . append_sid($redirect_url, "go=1&amp;sync=sync") . '">' . $language->lang('SYNC_TOPIC_URLS') . '</a><br/><br/> &bull; <a href="' . append_sid($redirect_url, "go=1&amp;sync=reset") . '" >' . $language->lang('SYNC_RESET_TOPIC_URLS') . '</a></b>');
				}
				break;
			default:
				trigger_error('NO_MODE', E_USER_ERROR);
			break;
		}

		$error = [];
		$seo_msg = [];
		$cfg_array = ($this->request->is_set('config')) ? utf8_normalize_nfc($this->request->variable('config', ['' => ''], true)) : $this->new_config;

		if ($submit && !check_form_key($form_key))
		{
			$error[] = $this->language->lang('FORM_INVALID');
		}

		// We validate the complete config if whished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		// Do not write values if there is an error
		if (!empty($error))
		{
			$submit = false;
		}

		$additional_notes = '';

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $cfg_setup)
		{
			if (strpos($config_name, 'legend') !== false || (!isset($cfg_array[$config_name]) && @$cfg_setup['method'] != 'select_multiple'))
			{
				continue;
			}

			// Handle multiple select options
			if (!empty($cfg_setup['method']) && $cfg_setup['method'] == 'select_multiple')
			{
				if (isset($_POST['multiple_' . $config_name]))
				{
					$m_values = utf8_normalize_nfc($this->request->variable('multiple_' . $config_name, ['' => '']));
					$validate_int = !empty($cfg_setup['multiple_validate']) && $cfg_setup['multiple_validate'] == 'int' ? true : false;

					foreach ($m_values as $k => $v)
					{
						if ($validate_int)
						{
							$v = max(0, (int) $v);
						}

						if (empty($v))
						{
							unset($m_values[$k]);
						}
						else
						{
							$m_values[$k] = $v;
						}
					}

					sort($m_values);

					$this->new_config[$config_name] = $m_values;
					$config_value = implode(',', $m_values);

					if (strlen($config_value) > 255)
					{
						$error[] = $this->language->lang('SETTING_TOO_LONG', $this->language->lang($cfg_setup['lang']), 255);
					}

					$submit = empty($error);
				}
				else
				{
					if ($submit)
					{
						$this->new_config[$config_name] = [];
						$config_value = '';
					}
					else
					{
						$config_value = $this->new_config[$config_name];
						$this->new_config[$config_name] = !empty($config_value) ? explode(',', $config_value) : [];
					}
				}
			}
			else
			{
				$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];
			}

			if ($submit)
			{
				// In case we deal with forum URLs
				if ($mode == 'forum_url' && preg_match('`^forum_url([0-9]+)$`', $config_name, $matches))
				{
					// Check if this is an actual forum_id
					if (isset($this->forum_ids[$matches[1]]))
					{
						$forum_id = intval($matches[1]);
						$config_value = $this->core->format_url($config_value, $this->core->seo_static['forum']);

						// Remove delim if required
						while (preg_match('`^[a-z0-9_-]+' . $this->core->seo_delim['forum'] . '[0-9]+$`i', $config_value))
						{
							$config_value = preg_replace('`^([a-z0-9_-]+)' . $this->core->seo_delim['forum'] . '[0-9]+$`i', '\\1', $config_value);

							if (@$this->core->cache_config['settings']['rem_ids'])
							{
								$seo_msg['SEO_ADVICE_DELIM_REM'] = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_DELIM_REM') . '</li>';
							}
						}

						// Forums cannot end with the pagination param
						while (preg_match('`^[a-z0-9_-]+' . $this->core->seo_delim['start'] . '[0-9]+$`i', $config_value))
						{
							$config_value = preg_replace('`^([a-z0-9_-]+)' . $this->core->seo_delim['start'] . '[0-9]+$`i', "\\1", $config_value);
							$seo_msg['SEO_ADVICE_START'] = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_START') . '</li>';
						}

						// Only update if the value is not a static one for forums
						if (!in_array($config_value, $forbidden))
						{
							// and updated (sic)
							if ($config_value != @$this->core->cache_config['forum_urls'][$forum_id])
							{
								// and if not already set
								if (!array_search($config_value, $this->core->cache_config['forum_urls']))
								{
									$this->core->cache_config['forum_urls'][$forum_id] = $config_value . (@$this->core->cache_config['settings']['rem_ids'] ? '': $this->core->seo_delim['forum'] . $forum_id);
								}
								else
								{
									$seo_msg['SEO_ADVICE_DUPE_' . $forum_id] = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_DUPE', $config_value) . '</li>';
								}
							}
						}
						else
						{
							$seo_msg['SEO_ADVICE_RESERVED_' . $forum_id] = '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_RESERVED', $config_value) . '</li>';
						}
					}
				}
				else if ($mode == 'settings')
				{
					if (isset($this->array_type_cfg[$config_name]) && isset($this->core->seo_opt[$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']]))
					{
						if (is_bool($this->core->seo_opt[$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']]))
						{
							$this->core->cache_config['settings'][$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']] = ($config_value == 1) ? true : false;
						}
						else if (is_numeric($this->core->seo_opt[$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']]))
						{
							$this->core->cache_config['settings'][$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']] = intval($config_value);
						}
						else if (is_string($this->core->seo_opt[$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']]))
						{
							$this->core->cache_config['settings'][$this->array_type_cfg[$config_name]['main']][$this->array_type_cfg[$config_name]['sub']] = $config_value;
						}
					}
					else if (isset($this->core->seo_opt[$config_name]))
					{
						if (is_bool($this->core->seo_opt[$config_name]))
						{
							$this->core->cache_config['settings'][$config_name] = ($config_value == 1) ? true : false;
						}
						else if (is_numeric($this->core->seo_opt[$config_name]))
						{
							$this->core->cache_config['settings'][$config_name] = intval($config_value);
						}
						else if (is_string($this->core->seo_opt[$config_name]))
						{
							$this->core->cache_config['settings'][$config_name] = $config_value;
						}
					}

					// Let's make sure that the proper field was added to the topic table
					if ($config_name === 'sql_rewrite' && $config_value == 1 && !$this->core->seo_opt['sql_rewrite'])
					{
						$db_tools = new \phpbb\db\tools\tools($this->db);
						$db_tools->db->sql_return_on_error(true);

						if (!$db_tools->sql_column_exists(TOPICS_TABLE, 'topic_url'))
						{
							$db_tools->sql_column_add(TOPICS_TABLE, 'topic_url', ['VCHAR:255', '']);
						}

						$additional_notes = $this->language->lang('SYNC_TOPIC_URL_NOTE', '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-phpbbseo-usu-acp-usu&amp;mode=sync_url') . '">', '</a>');

						if ($db_tools->db->get_sql_error_triggered())
						{
							$error[] = '<b>' . $this->language->lang('sql_rewrite') . '</b> : ' . $this->language->lang('SEO_SQL_ERROR') . ' [ ' . $db_tools->db->get_sql_layer() . ' ] : ' . $db_tools->db->sql_error_returned['message'] . ' [' . $db_tools->db->sql_error_returned['code'] . ']' . '<br/>' . $this->language->lang('SEO_SQL_TRY_MANUALLY') . '<br/>' . $db_tools->db->sql_error_sql;
							$submit = false;
						}

						$db_tools->db->sql_return_on_error(false);
					}
				}
				else if ($mode == 'extended')
				{
					$this->config->set($config_name, $config_value);
				}
			}
		}

		if (sizeof($error))
		{
			$submit = false;
		}

		if ($submit)
		{
			if ($mode == 'server')
			{
				if ($this->new_config['save'])
				{
					$this->seo_server_conf(false);

					$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEO_LOG_CONFIG_' . strtoupper($mode), time());
				}
			}
			else if ($mode == 'extended')
			{
				$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEO_LOG_CONFIG_' . strtoupper($mode), time());

				trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
			}
			else
			{
				// config
				$file = $this->core->cache_config['file'];
				ksort($this->core->cache_config['forum_urls']);

				$update = '<'.'?php' . "\n" . '/**' . "\n" . '* phpBB SEO' . "\n" . '* www.phpBB-SEO.ir' . "\n" . '* @package phpBB SEO USU' . "\n" . '*/' . "\n" . 'if (!defined(\'IN_PHPBB\')) {' . "\n\t" . 'exit;' . "\n" . '}' . "\n";
				$update .= '$settings = ' . preg_replace('`[\s]+`', ' ', var_export($this->core->cache_config['settings'], true)) . ';'. "\n";
				$update .= '$forum_urls = ' . preg_replace('`[\s]+`', ' ', var_export($this->core->cache_config['forum_urls'], true)) . ';';

				if ($this->write_cache($file, $update))
				{
					global $msg_long_text;

					$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEO_LOG_CONFIG_' . strtoupper($mode), time());

					$msg = !empty($seo_msg) ? '<br /><h1 style="color:red;text-align:left;">' . $this->language->lang('SEO_VALIDATE_INFO') . '</h1><ul style="text-align:left;">' . implode(' ', $seo_msg) . '</ul><br />' : '';

					$msg_long_text = $this->language->lang('SEO_CACHE_MSG_OK') . $msg . adm_back_link($this->u_action);

					if ($additional_notes)
					{
						$msg_long_text .= "<br/><br/>$additional_notes";
					}

					trigger_error(false);
				}
				else
				{
					trigger_error($this->language->lang('SEO_CACHE_MSG_FAIL') . adm_back_link($this->u_action));
				}
			}
		}

		$this->tpl_name = 'acp_board';
		$this->page_title = $display_vars['title'];

		$l_title_explain = $this->language->lang($display_vars['title'] . '_EXPLAIN');

		if ($mode != 'extended')
		{
			$l_title_explain .= $mode == 'server' ? '' : $this->check_cache_folder($this->core->seo_opt['cache_folder']);
		}

		$this->template->assign_vars([
			'L_TITLE'			=> $this->language->lang($display_vars['title']),
			'L_TITLE_EXPLAIN'	=> $l_title_explain,

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),

			'U_ACTION'			=> $this->u_action,
		]);

		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$this->template->assign_block_vars('options', [
					'S_LEGEND'		=> true,
					'LEGEND'		=> $this->language->is_set($vars) ? $this->language->lang($vars) : $vars,
				]);

				continue;
			}

			$type = explode(':', $vars['type']);
			$l_explain = '';

			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = $this->language->is_set($vars['lang_explain']) ? $this->language->lang($vars['lang_explain']) : $vars['lang_explain'];
			}
			else if ($vars['explain'] && isset($vars['lang_explain_custom']))
			{
				$l_explain = $vars['lang_explain_custom'];
			}
			else if ($vars['explain'])
			{
				$l_explain = $this->language->is_set($vars['lang'] . '_EXPLAIN') ? $this->language->lang($vars['lang'] . '_EXPLAIN') : '';
			}

			$this->template->assign_block_vars('options', [
				'KEY'			=> $config_key,
				'TITLE'			=> $this->language->is_set($vars['lang']) ? $this->language->lang($vars['lang']) : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars),
			]);

			unset($display_vars['vars'][$config_key]);
		}
	}

	/**
	*  forum_url_check validation
	*/
	function forum_url_input($value, $key)
	{
		return '<input id="' . $key . '" type="text" size="40" maxlength="255" name="config[' . $key . ']" value="' . $value . '" /> ';
	}

	/**
	*  select_string custom select string
	*/
	function select_string($value, $key)
	{
		$select_ary = $this->dyn_select[$key];
		$html = '';

		foreach ($select_ary as $sel_value)
		{
			if (@isset($this->array_type_cfg[$key]))
			{
				$selected = ($sel_value == @$this->core->seo_opt[$this->array_type_cfg[$key]['main']][$this->array_type_cfg[$key]['sub']]) ? ' selected="selected"' : '';
				$sel_title = @isset($this->multiple_options[$this->array_type_cfg[$key]['main']][$this->array_type_cfg[$key]['sub'] . '_lang'][$sel_value]) ? $this->multiple_options[$this->array_type_cfg[$key]['main']][$this->array_type_cfg[$key]['sub'] . '_lang'][$sel_value] : $sel_value;
			}
			else
			{
				$selected = ($sel_value == @$this->core->cache_config['settings'][$key]) ? ' selected="selected"' : '';
				$sel_title = @isset($this->multiple_options[$key . '_lang'][$sel_value]) ? $this->multiple_options[$key . '_lang'][$sel_value] : $sel_value;
			}

			$html .= '<option value="' . $sel_value . '"' . $selected . '>' . $sel_title . '</option>';
		}

		return $html;
	}

	/**
	*  seo_advices Always needed :-)
	*/
	function seo_advices($url, $forum_id, $cached = false, $error_cust = '')
	{
		$seo_advice = '';

		// Check how well is the URL SEO wise
		if (!empty($error_cust))
		{
			$seo_advice .= $error_cust;
		}

		if (strlen($url) > $this->lengh_limit)
		{
			// Size
			$seo_advice .= '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_LENGTH') . '</li>';
		}

		if (preg_match('`^[a-z0-9_-]+' . $this->core->seo_delim['forum'] . '[0-9]+$`i', $url))
		{
			// With delimiter and id
			if (@$this->core->cache_config['settings']['rem_ids'])
			{
				$seo_advice .= '<li style="color:red">&nbsp;' . $this->language->lang('SEO_ADVICE_DELIM') . '</li>';
			}
		}

		if ($this->core->seo_static['forum'] == $url)
		{
			// default
			$seo_advice .= '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_DEFAULT') . '</li>';
		}

		// Check the number of word
		$url_words = explode('-', $url);

		if (count($url_words) > $this->word_limit)
		{
			$seo_advice .= '<li>&nbsp;' . $this->language->lang('SEO_ADVICE_WORDS') . '</li>';
		}

		return $seo_advice ? '<ul  style="color:red">' . $seo_advice . '</ul>' : '';
	}

	/**
	*  seo_server_conf The evil one ;-)
	*/
	function seo_server_conf($html = true)
	{
		// get mods server_conf tpls
		$mods_ht = $this->get_mods_server_conf();
		$default_slash = '/';
		$wierd_slash = '';
		$phpbb_path = trim($this->core->seo_path['phpbb_script'], '/');
		$show_rewritebase_opt = false;
		$rewritebase = '';
		$wierd_slash = $this->new_config['wslash'] ? '/' : '';
		$default_slash = $this->new_config['slash'] ? '' : '/';

		$red_slash = '<b style="color:red">/</b>';

		if (!empty($phpbb_path))
		{
			$phpbb_path = $phpbb_path . '/';

			if ($this->new_config['rbase'])
			{
				$rewritebase = $phpbb_path;
				$default_slash = $this->new_config['slash'] ? '/' : '';
			}

			$show_rewritebase_opt = $this->core->seo_opt['virtual_root'] ? false : true;
		}

		$colors = [
			'color'		=> '<b style="color:%1$s">%2$s</b>',
			'static'	=> '#A020F0',
			'ext'		=> '#6A5ACD',
			'delim'		=> '#FF00FF',
		];

		$spritf_tpl = [
			'paginpage'	=> '/?(<b style="color:' . $colors['static'] . '">%1$s</b>([0-9]+)<b style="color:' . $colors['ext'] . '">%2$s</b>)?',
			'pagin'		=> '(<b style="color:' . $colors['delim'] . '">%1$s</b>([0-9]+))?<b style="color:' . $colors['ext'] . '">%2$s</b>',
			'static'	=> sprintf($colors['color'] , $colors['static'], '%1$s'),
			'ext'		=> sprintf($colors['color'] , $colors['ext'], '%1$s'),
			'delim'		=> sprintf($colors['color'] , $colors['delim'], '%1$s'),
		];

		$modrtype = [
			1		=> 'SIMPLE',
			2		=> 'MIXED',
			3		=> 'ADVANCED',
			'type'	=> intval($this->core->modrtype),
		];

		if (!empty($default_slash) && $this->new_config['more_options'])
		{
			$default_slash = $red_slash;
		}

		if (!empty($wierd_slash) && $this->new_config['more_options'])
		{
			$wierd_slash = $red_slash;
		}

		// The tpl array
		$rewrite_tpl_vars = [];

		// handle the suffixes properly in RegEx
		// set up pagination RegEx
		// set up ext bits
		$seo_ext = [
			// force '/' for both / and empty ext to add /? in RegEx (which allows both cases)
			'pagination'	=> trim($this->core->seo_ext['pagination'], '/') ? str_replace('.', '\\.', $this->core->seo_ext['pagination']) : '/',
		];

		$reg_ex_page = sprintf($spritf_tpl['paginpage'], $this->core->seo_static['pagination'], $seo_ext['pagination'] . ($seo_ext['pagination'] === '/' ? '?' : ''));

		foreach ($this->core->seo_ext as $type => $value)
		{
			$_value = trim($value, '/');
			// force '/' for both / and empty ext to add /? in RegEx (which allows both cases)
			$seo_ext[$type] = $_value ? str_replace('.', '\\.', $value) : '/';
			$rewrite_tpl_vars['{' . strtoupper($type) . '_PAGINATION}'] = $_value ? sprintf($spritf_tpl['pagin'], $this->core->seo_delim['start'], $seo_ext[$type]) : $reg_ex_page;
			// use url/? to allow both url and url/ to work as expected
			$rewrite_tpl_vars['{EXT_' . strtoupper($type) . '}'] = sprintf($spritf_tpl['ext'] , $seo_ext[$type]) . ($_value ? '' : '?');
		}

		$rewrite_tpl_vars['{PAGE_PAGINATION}'] = $reg_ex_page;

		// static bits
		foreach ($this->core->seo_static as $type => $value)
		{
			if (!is_array($this->core->seo_static[$type]))
			{
				$rewrite_tpl_vars['{STATIC_' . strtoupper($type) . '}'] = sprintf($spritf_tpl['static'], $this->core->seo_static[$type]);
			}
		}

		// delim bits
		foreach ($this->core->seo_delim as $type => $value)
		{
			$rewrite_tpl_vars['{DELIM_' . strtoupper($type) . '}'] = sprintf($spritf_tpl['delim'], $this->core->seo_delim[$type]);
		}

		$hostname = str_replace(['https://', 'http://'], ['', ''], trim($this->core->seo_path['root_url'], '/ '));
		$hostname_no_www = '';

		if (preg_match('`^www\.`', $hostname))
		{
			$hostname_no_www = preg_replace('`^www\.`', '', $hostname);
		}

		$hostname_escaped = str_replace('.', '\\.', $hostname);
		// common server_conf vars
		$rewrite_tpl_vars += [
			'{HOSTNAME}'		=> $hostname,
			'{HOSTNAME_ESCAPED}'	=> $hostname_escaped,
			'{REWRITEBASE}'		=> $rewritebase,
			'{PHP_EX}'		=> $this->php_ext,
			'{PHPBB_LPATH}'		=> ($this->new_config['rbase'] || $this->core->seo_opt['virtual_root']) ? '' : $phpbb_path,
			'{PHPBB_RPATH}'		=> $this->new_config['rbase'] ? '' : $phpbb_path,
			'{DEFAULT_SLASH}'	=> $default_slash,
			'{WIERD_SLASH}'		=> $wierd_slash,
			'{RED_SLASH}'		=> $this->new_config['more_options'] ? $red_slash : '/',
			'{MOD_RTYPE}'		=> $modrtype[$modrtype['type']],
		];

		// prettify rules
		$prettify_common = [
			'comments' => [
				'find' => [
					'`^(\s*)(#.*)$`m',
					'`^(\s*)(//.*)$`m',
					'`^(\s*)(/\*.*\*/)$`Um',
				],
				'replace' => '\1<b style="color:blue">\2</b>',
			],
			'rewrite' => [
				'find' => '`^(\s*)(rewrite|RewriteRule|RewriteCond|RewriteBase|RewriteEngine)`m',
				'replace' => '\1<b style="color:green">\2</b>',
			],
		];

		$rewrite_conf = [
			'apache' => [
				'header' => "<IfModule mod_rewrite.c>
	# You may need to un-comment the following lines
	# Options +FollowSymlinks
	# To make sure that rewritten dir or file (/|.html) will not load dir.php in case it exist
	# Options -MultiViews
	# REMEBER YOU ONLY NEED TO STARD MOD REWRITE ONCE
	RewriteEngine On

	# Uncomment the statement below if you want to make use of
	# HTTP authentication and it does not already work.
	# This could be required if you are for example using PHP via Apache CGI.
	# RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]

	# REWRITE BASE
	RewriteBase /{REWRITEBASE}

	# HERE IS A GOOD PLACE TO FORCE CANONICAL DOMAIN
	# Define fully qualified ssl aware protocol
	# RewriteCond %{SERVER_PORT}s ^(443(s)|[0-9]+s)$
	# RewriteRule ^.*$ - [env=HttpFullProto:http%2://]
	# RewriteCond %{HTTP_HOST} !^{HOSTNAME_ESCAPED}$ [NC]
	# RewriteRule ^(.*)$ %{ENV:HttpFullProto}{HOSTNAME}/{REWRITEBASE}$1 [QSA,L,R=301]

	# DO NOT GO FURTHER IF THE REQUESTED FILE / DIR DOES EXISTS
	RewriteCond %{REQUEST_FILENAME} -f [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule . - [L]",

				'footer' => '	#
	# The following 3 lines will rewrite URLs passed through the front controller
	# to not require app.php in the actual URL. In other words, a controller is
	# by default accessed at /app.php/my/controller, but can also be accessed at
	# /my/controller
	#
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}(.*)$ {DEFAULT_SLASH}{PHPBB_RPATH}app.{PHP_EX} [QSA,L]

</IfModule>

# With Apache 2.4 the "Order, Deny" syntax has been deprecated and moved from
# module mod_authz_host to a new module called mod_access_compat (which may be
# disabled) and a new "Require" syntax has been introduced to mod_authz_host.
# We could just conditionally provide both versions, but unfortunately Apache
# does not explicitly tell us its version if the module mod_version is not
# available. In this case, we check for the availability of module
# mod_authz_core (which should be on 2.4 or higher only) as a best guess.
<IfModule mod_version.c>
	<IfVersion < 2.4>
		<Files "config.php">
			Order Allow,Deny
			Deny from All
		</Files>
		<Files "common.php">
			Order Allow,Deny
			Deny from All
		</Files>
	</IfVersion>
	<IfVersion >= 2.4>
		<Files "config.php">
			Require all denied
		</Files>
		<Files "common.php">
			Require all denied
		</Files>
	</IfVersion>
</IfModule>
<IfModule !mod_version.c>
	<IfModule !mod_authz_core.c>
		<Files "config.php">
			Order Allow,Deny
			Deny from All
		</Files>
		<Files "common.php">
			Order Allow,Deny
			Deny from All
		</Files>
	</IfModule>
	<IfModule mod_authz_core.c>
		<Files "config.php">
			Require all denied
		</Files>
		<Files "common.php">
			Require all denied
		</Files>
	</IfModule>
</IfModule>',
				'prettify' => [
					'struct' => [
						'find' => [
							'`^(\s*)(\&lt;(IfModule|IfVersion|Files)([^>]+)\&gt;)$`Um',
							'`^(\s*)(\&lt;/(IfModule|IfVersion|Files)\&gt;)$`Um',
							'`(\s+)(\[[A-Z0-9,=]+\])$`Um'
						],
						'replace' => [
							'\1<b style="color:brown">&lt;\3</b><b style="color:#FF00FF">\4</b><b style="color:brown">&gt;</b>',
							'\1<b style="color:brown">\2</b>',
							'\1<b style="color:brown">\2</b>',
						],
					],
				],
				'header_title' => $this->language->lang('SEO_APACHE_CONF'),
				'header_message' => $show_rewritebase_opt && $this->new_config['rbase'] ?
					$this->language->lang('SEO_HTACCESS_FOLDER_MSG', '<em style="color:#000">' . $this->core->seo_path['phpbb_url'] . '</em>') :
					$this->language->lang('SEO_HTACCESS_ROOT_MSG', '<em style="color:#000">' . $this->core->seo_path['root_url'] . '</em>'),
				'filename' => '.htaccess',
				'rewrite_padding' => '	',
			],

			// ngix
			'ngix' => [
				'header' => '# Sample nginx configuration file for phpBB.
# Global settings have been removed, copy them
# from your system\'s nginx.conf.
# Tested with nginx 0.8.35.

# If you want to use the X-Accel-Redirect feature,
# add the following to your config.php.
#
#  define(\'PHPBB_ENABLE_X_ACCEL_REDIRECT\', true);
#
# See http://wiki.nginx.org/XSendfile for the details
# on X-Accel-Redirect.
http {
	# Compression - requires gzip and gzip static modules.
	gzip on;
	gzip_static on;
	gzip_vary on;
	gzip_http_version 1.1;
	gzip_min_length 700;

	# Compression levels over 6 do not give an appreciable improvement
	# in compression ratio, but take more resources.
	gzip_comp_level 6;

	# IE 6 and lower do not support gzip with Vary correctly.
	gzip_disable "msie6";
	# Before nginx 0.7.63:
	#gzip_disable "MSIE [1-6]\.";

	# Catch-all server for requests to invalid hosts.
	# Also catches vulnerability scanners probing IP addresses.
	server {
		# default specifies that this block is to be used when
		# no other block matches.
		listen 80 default;

		server_name bogus;
		return 444;
		root /var/empty;
	}

	# The actual board domain.
	server {
		#listen 80;
		server_name {HOSTNAME};

		root /{REWRITEBASE};

		location / {
			# phpbb uses index.htm
			index index.php index.html index.htm;

			# multi domain ssl aware canonical hostname
			# if ($host != {HOSTNAME}) {
			# 	rewrite ^ $scheme://{HOSTNAME}$request_uri permanent;
			# }

			# DO NOT GO FURTHER IF THE REQUESTED FILE / DIR DOES EXISTS
			if (-e $request_filename) {
				break;
			}',
				// footer
				'footer' => '			#
			# The following 3 lines will rewrite URLs passed through the front controller
			# to not require app.php in the actual URL. In other words, a controller is
			# by default accessed at /app.php/my/controller, but can also be accessed at
			# /my/controller
			#
			if (!-e $request_filename) {
				rewrite ^{WIERD_SLASH}{PHPBB_RPATH}(.*)$ {DEFAULT_SLASH}{PHPBB_RPATH}app.{PHP_EX} last;
			}

		}
		# Deny access to internal phpbb files.
		location ~ /(config\.php|common\.php|includes|cache|files|store|images/avatars/upload) {
			deny all;
			# deny was ignored before 0.8.40 for connections over IPv6.
			# Use internal directive to prohibit access on older versions.
			internal;
		}

		# Deny access to version control system directories.
		location ~ /\.svn|/\.git {
			deny all;
			internal;
		}
	}

	# If running php as fastcgi, specify php upstream.
	upstream php {
		server unix:/tmp/php.sock;
	}
}',
				'prettify' => [
					'struct' => [
						'find' => [
							'`(break|last|permanent);$`m',
							'`^(\s*)(location|http|server(_name)?|return|root|listen|index |upstream|gzip(_[a-z_]+)?)`m',
							'`^(\s*)if \(([^)]+)\) {$`Um',
						],
						'replace' => [
							'<b style="color:brown">\1</b>;',
							'\1<b style="color:brown">\2</b>',
							'\1<b style="color:brown">if </b>(<b style="color:#FF00FF">\2</b>) {',
						],
					],
				],
				'translate' => [
					'find' => [
						'RewriteRule',
						'[QSA,L,NC]',
						'[QSA,L,NC,R=301]',
						'{WIERD_SLASH}',
						'{DEFAULT_SLASH}',
					],
					'replace' => [
						'rewrite',
						'last;',
						'permanent;',
						$wierd_slash ? '' : '{RED_SLASH}',
						$rewritebase ? ($this->new_config['slash'] ? '' : '{RED_SLASH}') : '{RED_SLASH}',
					],
				],
				'header_title' => $this->language->lang('SEO_NGIX_CONF'),
				'header_message' => $this->language->lang('SEO_NGIX_CONF_EXPLAIN'),
				'filename' => 'ngix.conf',
				'rewrite_padding' => '			',
			],
		];

		$rewrite_rules = [];
		if (!empty($this->core->seo_static['index']))
		{
			$rewrite_rules += [
				'forum_index' => '# FORUM INDEX
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_INDEX}{EXT_INDEX}$ {DEFAULT_SLASH}{PHPBB_RPATH}index.{PHP_EX} [QSA,L,NC]',
			];
		}
		else
		{
			$rewrite_rules += [
				'forum_index' => '# FORUM INDEX REWRITERULE WOULD STAND HERE IF USED. "forum" REQUIRES TO BE SET AS FORUM INDEX
# RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}forum\.html$ {DEFAULT_SLASH}{PHPBB_RPATH}index.{PHP_EX} [QSA,L,NC]',
			];
		}
		$rewrite_rules += [
			'forum' => '# FORUM ALL MODES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}({STATIC_FORUM}|[a-z0-9_-]*{DELIM_FORUM})([0-9]+){FORUM_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewforum.{PHP_EX}?f=$2&start=$4 [QSA,L,NC]',
				'topic_vfolder' => '# TOPIC WITH VIRTUAL FOLDER ALL MODES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}({STATIC_FORUM}|[a-z0-9_-]*{DELIM_FORUM})([0-9]+)/({STATIC_TOPIC}|[a-z0-9_-]*{DELIM_TOPIC})([0-9]+){TOPIC_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewtopic.{PHP_EX}?f=$2&t=$4&start=$6 [QSA,L,NC]',

			'topic_nofid' => '# TOPIC WITHOUT FORUM ID & DELIM ALL MODES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}([a-z0-9_-]*)/?({STATIC_TOPIC}|[a-z0-9_-]*{DELIM_TOPIC})([0-9]+){TOPIC_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewtopic.{PHP_EX}?forum_uri=$1&t=$3&start=$5 [QSA,L,NC]',
		];
		if ($this->core->seo_opt['profile_noids'])
		{
			$rewrite_rules += [
				'profile' => '# PROFILES THROUGH USERNAME
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_USER}/([^/]+)/?$ {DEFAULT_SLASH}{PHPBB_RPATH}memberlist.{PHP_EX}?mode=viewprofile&un=$1 [QSA,L,NC]',

				'user_messages' => '# USER MESSAGES THROUGH USERNAME
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_USER}/([^/]+)/(topics|posts){USER_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?author=$1&sr=$2&start=$4 [QSA,L,NC]',
			];
		}
		else
		{
			$rewrite_rules += [
				'profile' => '# PROFILES ALL MODES WITH ID
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}({STATIC_USER}|[a-z0-9_-]*{DELIM_USER})([0-9]+){EXT_USER}$ {DEFAULT_SLASH}{PHPBB_RPATH}memberlist.{PHP_EX}?mode=viewprofile&u=$2 [QSA,L,NC]',

				'user_messages' => '# USER MESSAGES ALL MODES WITH ID
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}({STATIC_USER}|[a-z0-9_-]*{DELIM_USER})([0-9]+){DELIM_SR}(topics|posts){USER_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?author_id=$2&sr=$3&start=$5 [QSA,L,NC]',
			];
		}

		$rewrite_rules += [
			'group' => '# GROUPS ALL MODES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}({STATIC_GROUP}|[a-z0-9_-]*{DELIM_GROUP})([0-9]+){GROUP_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}memberlist.{PHP_EX}?mode=group&g=$2&start=$4 [QSA,L,NC]',
			'posts' => '# POSTS
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_POST}([0-9]+){EXT_POST}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewtopic.{PHP_EX}?p=$1 [QSA,L,NC]',

			'active_topics' => '# ACTIVE TOPICS
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_ATOPIC}{ATOPIC_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?search_id=active_topics&start=$2&sr=topics [QSA,L,NC]',

			'unanswered_topics' => '# UNANSWERED TOPICS
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_UTOPIC}{UTOPIC_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?search_id=unanswered&start=$2&sr=topics [QSA,L,NC]',

			'new_posts' => '# NEW POSTS
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_NPOST}{NPOST_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?search_id=newposts&start=$2&sr=topics [QSA,L,NC]',

			'unread_posts' => '# UNREAD POSTS
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_URPOST}{URPOST_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}search.{PHP_EX}?search_id=unreadposts&start=$2 [QSA,L,NC]',

			'the_team' => '# THE TEAM
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}{STATIC_LEADERS}{EXT_LEADERS}$ {DEFAULT_SLASH}{PHPBB_RPATH}memberlist.{PHP_EX}?mode=team [QSA,L,NC]',

			'comment_more_rules' => '# HERE IS A GOOD PLACE TO ADD OTHER PHPBB RELATED REWRITERULES
',
		];

		// mods server_conf pos1
		if (!empty($mods_ht['pos1']))
		{
			$rewrite_rules += $mods_ht['pos1'];
		}

		$rewrite_rules += [
			'comment_forum_noid' => '# FORUM WITHOUT ID & DELIM ALL MODES
# THESE LINES MUST BE LOCATED AT THE END OF YOUR HTACCESS TO WORK PROPERLY',
		];

		if (trim($this->core->seo_ext['forum'],'/'))
		{
			$rewrite_rules += [
				'forum_noid' => [
					'apache' => 'RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}([a-z0-9_-]+?)(-([0-9]+))?{EXT_FORUM}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewforum.{PHP_EX}?forum_uri=$1&start=$3 [QSA,L,NC]',
					'ngix' => 'if (!-e $request_filename) {
	rewrite ^{WIERD_SLASH}{PHPBB_LPATH}([a-z0-9_-]+?)(-([0-9]+))?{EXT_FORUM}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewforum.{PHP_EX}?forum_uri=$1&start=$3 last;
}',
				],
			];
		}
		else
		{
			$rewrite_rules += [
				'forum_noid' => [
					'apache' => 'RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}([a-z0-9_-]+){FORUM_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewforum.{PHP_EX}?forum_uri=$1&start=$3 [QSA,L,NC]',
					'ngix' => 'if (!-e $request_filename) {
	rewrite ^{WIERD_SLASH}{PHPBB_LPATH}([a-z0-9_-]+){FORUM_PAGINATION}$ {DEFAULT_SLASH}{PHPBB_RPATH}viewforum.{PHP_EX}?forum_uri=$1&start=$3 last;
}',
				],
			];
		}

		$fix_left_match = '';
		$fix_301_redirect = ',R=301';
		if ($this->core->seo_opt['virtual_root'])
		{
			$fix_left_match = '.+/';
			// do not 301 css and other broken links as this would means many
			// just serve them as usual instead, it's no such big deal if assets
			// are served from two urls as long as it's not on the same page.
			// of course, better would be to fix links from start but it's not
			// that obvious as long as phpBB does not fully enforces PHPBB_USE_BOARD_URL_PATH (for exts etc ...)
			$fix_301_redirect = '';
		}

		$rewrite_rules += [
			'relative_files' => '# FIX RELATIVE PATHS : FILES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}' . $fix_left_match . '(style\.{PHP_EX}|ucp\.{PHP_EX}|mcp\.{PHP_EX}|faq\.{PHP_EX}|posting\.{PHP_EX}|download/file\.{PHP_EX}|report\.{PHP_EX}|adm/index\.{PHP_EX}|cron\.{PHP_EX})$ {DEFAULT_SLASH}{PHPBB_RPATH}$1 [QSA,L,NC' . $fix_301_redirect . ']',

			'relative_images' => '# FIX RELATIVE PATHS : IMAGES
RewriteRule ^{WIERD_SLASH}{PHPBB_LPATH}' . $fix_left_match . '.+/(styles/.*|images/.*|assets/.*|ext/.*)$ {DEFAULT_SLASH}{PHPBB_RPATH}$1 [QSA,L,NC' . $fix_301_redirect . ']',
		];

		// mods server_conf pos2
		if (!empty($mods_ht['pos2']))
		{
			$rewrite_rules += $mods_ht['pos2'];
		}

		// build rewrite conf
		$rewrite_conf_result = [];
		$html_output = '';
		foreach ($rewrite_conf as $engine => $setup)
		{
			$translate = !empty($setup['translate']) ? $setup['translate'] : false;

			if (!empty($translate))
			{
				$setup['header'] = str_replace($translate['find'], $translate['replace'], $setup['header']);
				$setup['footer'] = str_replace($translate['find'], $translate['replace'], $setup['footer']);
			}

			$rewrite_conf_result[$engine] = $setup['header'] . "\n";

			// add rewrite rules
			foreach ($rewrite_rules as $key => $rule)
			{
				if (is_array($rule))
				{
					$rule = $rule[$engine];
				}
				if (!empty($translate))
				{
					$rule = str_replace($translate['find'], $translate['replace'], $rule);
				}

				$rewrtie_padding = $setup['rewrite_padding'];
				$rewrite_conf_result[$engine] .= $rewrtie_padding . str_replace("\n", "\n$rewrtie_padding", $rule) . "\n";
			}

			$rewrite_conf_result[$engine] .= $setup['footer'] . "\n";

			// parse template variables
			$rewrite_conf_result[$engine] = [
				'html' => str_replace(array_keys($rewrite_tpl_vars), array_values($rewrite_tpl_vars), utf8_htmlspecialchars($rewrite_conf_result[$engine])),
			];
			$rewrite_conf_result[$engine]['raw'] = str_replace(['&lt;', '&gt;', '&amp;'], ['<', '>', '&'], strip_tags($rewrite_conf_result[$engine]['html']));
			if ($html)
			{
				// prettify
				foreach ($prettify_common as $type => $prettify)
				{
					$rewrite_conf_result[$engine]['html'] = preg_replace($prettify['find'], $prettify['replace'], $rewrite_conf_result[$engine]['html']);
				}

				if (!empty($setup['prettify']))
				{
					foreach ($setup['prettify'] as $_type => $_prettify)
					{
						$rewrite_conf_result[$engine]['html'] = preg_replace($_prettify['find'], $_prettify['replace'], $rewrite_conf_result[$engine]['html']);
					}
				}

				$rewrite_conf_result[$engine]['html_output'] = '<div class="content">
	<div id="' . $engine . '_toggle" title="' . $this->language->lang('SEO_SHOW') . '&nbsp;/&nbsp;' . $this->language->lang('SEO_HIDE') . '">
		<h3>' . $setup['header_title'] . '</h3>
		<b style="color:red">' . $setup['header_message'] . '</b><br><br>
	</div>
	<div id="' . $engine . '_code">
		<dl style="padding:5px;background-color:#FFFFFF;border:1px solid #d8d8d8;font-size:12px;">
			<dt style="border-bottom:1px solid #CCCCCC;margin-bottom:3px;font-weight:bold;display:block;">&nbsp;<a id="' . $engine . '_select">' . $this->language->lang('SEO_SELECT_ALL') . '</a></dt>
			<dd >
				<code style="padding-top:5px;line-height:1.3em;color:#8b8b8b;font-weight:bold;font-family: monospace;white-space: pre;" id="' . $engine . '_code_select">
' . str_replace(["\n", "\t"], ['<br>', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'], $rewrite_conf_result[$engine]['html']) . '
				</code>
			</dd>
		</dl>
	</div>
</div>';
				$html_output .= $rewrite_conf_result[$engine]['html_output'];
			}
		}

		if ($html)
		{
			// HTML output
			$html_output = '</p>' . $html_output;
			$html_output .= '<div style="padding:5px;margin-top:10px;background-color:#FFFFFF;border:1px solid #d8d8d8;font-size:12px;"><b>' . $this->language->lang('SEO_SERVER_CONF_CAPTION') . ':</b><ul style="margin-left:30px;margin-top:10px;font-weight:bold;font-size:12px;">
	<li style="color:blue">&nbsp;' . $this->language->lang('SEO_SERVER_CONF_CAPTION_COMMENT') . '</li>
	<li style="color:#A020F0">&nbsp;' . $this->language->lang('SEO_SERVER_CONF_CAPTION_STATIC') . '</li>
	<li style="color:#6A5ACD">&nbsp;' . $this->language->lang('SEO_SERVER_CONF_CAPTION_SUFFIX') . '</li>
	<li style="color:#FF00FF">&nbsp;' . $this->language->lang('SEO_SERVER_CONF_CAPTION_DELIM') . '</li>' . "\n";

			if ($this->new_config['more_options'])
			{
				$html_output .= '<li style="color:red">&nbsp;' . $this->language->lang('SEO_SERVER_CONF_CAPTION_SLASH') . '</li>' . "\n";
			}

			$html_output .= '</ul></div><p>' . "\n";
			$this->template->assign_vars([
				'SEO_REWRITE_ENGINES'	=> str_replace('"', "\"", $html_output),
			]);

			return $html_output;

		}
		else
		{
			// File output
			foreach ($rewrite_conf as $engine => $setup)
			{
				$file = $this->core->seo_opt['cache_folder'] . $setup['filename'];
				$update = $rewrite_conf_result[$engine]['raw'];
				$this->write_cache($file,  $update);
			}
		}
	}

	/**
	*  get_mods_server_conf Get all mods server_conf tpls
	*/
	function get_mods_server_conf()
	{
		$all_ht_tpl = ['pos1' => '', 'pos2' => ''];
		$path = PHPBB_SEO_USU_ROOT_DIR . 'htmods';

		if (!($dir = @opendir($path)))
		{
			return false;
		}

		while (($file = @readdir($dir)) !== false)
		{
			if (!trim($file, '. '))
			{
				continue;
			}

			if (preg_match('`^ht_([a-z0-9_-]+)\.' . $this->php_ext . '$`i', $file, $match))
			{
				$mode = $match[1];
				$class = 'ht_' . $mode;

				require_once("{$path}/{$file}");

				$module = new $class();

				if ($tpl = $module->get_tpl())
				{
					if (!empty($tpl['pos1']))
					{
						$all_ht_tpl['pos1'] .= $tpl['pos1'] . "\n";
					}

					if (!empty($tpl['pos2']))
					{
						$all_ht_tpl['pos2'] .= $tpl['pos2'] . "\n";
					}
				}
			}
		}

		return !empty($all_ht_tpl['pos1']) || !empty($all_ht_tpl['pos2']) ? $all_ht_tpl : false;
	}

	/**
	*  set_phpbb_seo_links Builds links to support threads
	*/
	function set_phpbb_seo_links()
	{
		$modrtype_lang = [];
		$this->core->modrtype = intval($this->core->modrtype);

		if ($this->core->modrtype < 1 || $this->core->modrtype > 3)
		{
			$this->core->modrtype = 1;
		}

		$modrtype_lang['titles'] = [
			1	=> $this->language->lang('ACP_SEO_SIMPLE'),
			2	=> $this->language->lang('ACP_SEO_MIXED'),
			3	=> $this->language->lang('ACP_SEO_ADVANCED'),
			'u'	=> $this->language->lang('ACP_ULTIMATE_SEO_URL'),
		];

		$modrtype_lang['title'] = $modrtype_lang['titles'][$this->core->modrtype];
		$modrtype_lang['utitle'] = $modrtype_lang['titles']['u'];
		$modrtype_lang['types'] = [1 => 'SIMPLE', 2 => 'MIXED', 1 => 'SIMPLE', 3 => 'ADVANCED'];
		$modrtype_lang['type'] = $modrtype_lang['types'][$this->core->modrtype];

		$modrtype_lang['modrlinks_en'] = [
			1	=> 'http://www.phpBB-SEO.ir/simple-seo-url/simple-phpbb-seo-url-t1566.html',
			2	=> 'http://www.phpBB-SEO.ir/mixed-seo-url/mixed-phpbb-seo-url-t1565.html',
			3	=> 'http://www.phpBB-SEO.ir/advanced-seo-url/advanced-phpbb-seo-url-t1219.html',
			'u'	=> 'http://www.phpBB-SEO.ir/phpbb-mod-rewrite/ultimate-seo-url-t4608.html',
		];

		$modrtype_lang['modrforumlinks_en'] = [
			1	=> 'http://www.phpBB-SEO.ir/simple-seo-url/',
			2	=> 'http://www.phpbb-seo.ir/mixed-seo-url/',
			3	=> 'http://www.phpBB-SEO.ir/advanced-seo-url/',
			'u'	=> 'http://www.phpBB-SEO.ir/phpbb-mod-rewrite/',
		];

		/**
		 * Disable this check
		 * https://github.com/phpbb-seo/usu/issues/38
		 */
		/** // Check if Default Lang is FR
		if (strpos($this->config['default_lang'], 'fr') !== false)
		{
			$modrtype_lang['linkurl'] = $modrtype_lang['modrlinks_fr'][$this->core->modrtype];
			$modrtype_lang['forumlinkurl'] = $modrtype_lang['modrforumlinks_fr'][$this->core->modrtype];
			$modrtype_lang['ulinkurl'] = $modrtype_lang['modrlinks_fr']['u'];
			$modrtype_lang['uforumlinkurl'] = $modrtype_lang['modrforumlinks_fr']['u'];
		}
		else
		{
			$modrtype_lang['linkurl'] = $modrtype_lang['modrlinks_en'][$this->core->modrtype];
			$modrtype_lang['forumlinkurl'] = $modrtype_lang['modrforumlinks_en'][$this->core->modrtype];
			$modrtype_lang['ulinkurl'] = $modrtype_lang['modrlinks_en']['u'];
			$modrtype_lang['uforumlinkurl'] = $modrtype_lang['modrforumlinks_en']['u'];
		}
		*/
		$modrtype_lang['linkurl'] = $modrtype_lang['modrlinks_en'][$this->core->modrtype];
		$modrtype_lang['forumlinkurl'] = $modrtype_lang['modrforumlinks_en'][$this->core->modrtype];
		$modrtype_lang['ulinkurl'] = $modrtype_lang['modrlinks_en']['u'];
		$modrtype_lang['uforumlinkurl'] = $modrtype_lang['modrforumlinks_en']['u'];

		$modrtype_lang['link'] = '<a href="' . $modrtype_lang['linkurl'] . '" title="' . $this->language->lang('ACP_PHPBB_SEO_VERSION') . ' ' . $modrtype_lang['title'] . '" onclick="window.open(this.href); return false;"><b>' . $modrtype_lang['title'] . '</b></a>';
		$modrtype_lang['forumlink'] = '<a href="' . $modrtype_lang['forumlinkurl'] . '" title="' . $this->language->lang('ACP_SEO_SUPPORT_FORUM') . '" onclick="window.open(this.href); return false;"><b>' . $this->language->lang('ACP_SEO_SUPPORT_FORUM') . '</b></a>';
		$modrtype_lang['ulink'] = '<a href="' . $modrtype_lang['ulinkurl'] . '" title="' . $this->language->lang('ACP_PHPBB_SEO_VERSION') . ' ' . $modrtype_lang['utitle'] . '" onclick="window.open(this.href); return false;"><b>' . $modrtype_lang['utitle'] . '</b></a>';
		$modrtype_lang['uforumlink'] = '<a href="' . $modrtype_lang['uforumlinkurl'] . '" title="' . $this->language->lang('ACP_SEO_SUPPORT_FORUM') . '" onclick="window.open(this.href); return false;"><b>' . $this->language->lang('ACP_SEO_SUPPORT_FORUM') . '</b></a>';

		return $modrtype_lang;
	}

	/**
	*  check_cache_folder Validates the cache folder status
	*/
	function check_cache_folder($cache_dir, $msg = true)
	{
		$exists = $write = $inner_write = false;
		$cache_msg = '';

		if (file_exists($cache_dir) && is_dir($cache_dir))
		{
			$exists = true;

			if (!is_writeable($cache_dir))
			{
				phpbb_chmod($cache_dir, CHMOD_READ | CHMOD_WRITE);

				$fp = @fopen($cache_dir . 'test_lock', 'wb');

				if ($fp !== false)
				{
					$write = true;
				}

				@fclose($fp);
				@unlink($cache_dir . 'test_lock');
			}
			else
			{
				$write = true;
			}

			// check if the config cache file is here already and writeable
			$check = $this->core->cache_config['file'];
			$checks = [
				"{$check}.old",
				"{$check}.current",
				"{$cache_dir}.htaccess",
				"{$cache_dir}.htaccess.old",
				"{$cache_dir}.htaccess.current",
				"{$cache_dir}ngix.conf",
				"{$cache_dir}ngix.conf.old",
				"{$cache_dir}ngix.conf.current",
			];

			// let's check all files
			$inner_write = true;

			foreach ($checks as $check)
			{
				if (file_exists($check))
				{
					if (!is_writeable($check))
					{
						$inner_write = false;

						phpbb_chmod($check, CHMOD_READ | CHMOD_WRITE);

						$fp = @fopen($check, 'wb');

						if ($fp !== false)
						{
							$inner_write = true;
						}

						@fclose($fp);
					}
				}
			}
		}

		if ($msg)
		{
			$exists = ($exists) ? '<b style="color:green">' . $this->language->lang('SEO_CACHE_FOUND') . '</b>' : '<b style="color:red">' . $this->language->lang('SEO_CACHE_NOT_FOUND') . '</b>';
			$write = ($write) ? '<br/> <b style="color:green">' . $this->language->lang('SEO_CACHE_WRITABLE') . '</b>' : (($exists) ? '<br/> <b style="color:red">' . $this->language->lang('SEO_CACHE_UNWRITABLE') . '</b>' : '');
			$inner_write = $inner_write ? '' : '<br/> <b style="color:red">' . $this->language->lang('SEO_CACHE_INNER_UNWRITABLE') . '</b>';
			$cache_msg = $this->language->lang('SEO_CACHE_STATUS', $cache_dir) . '<br/>' . $exists . $write . $inner_write;

			return '<br/><b>' . $this->language->lang('SEO_CACHE_FILE_TITLE') . ':</b><br/>' . $cache_msg . '<br/><br/>';
		}
		else
		{
			return ($exists && $write);
		}
	}

	/**
	* write_cache( ) will write the cached file and keep backups.
	*/
	function write_cache($file, $update)
	{
		if (!$this->core->cache_config['cache_enable'])
		{
			return false;
		}

		// Keep a backup of the previous settings
		@copy($file, $file . '.old');
		$handle = @fopen($file, 'wb');
		@fputs($handle, $update);
		@fclose ($handle);
		unset($update);
		@umask(0000);
		phpbb_chmod($file, CHMOD_READ | CHMOD_WRITE);

		// Keep a backup of the current settings
		@copy($file, $file . '.current');

		return true;
	}

	/**
	*  select_multiple($value, $key, $select_ary)
	*/
	function select_multiple($value, $key, $select_ary)
	{
		$size = min(12,count($select_ary));
		$html = '<select multiple="multiple" id="' . $key . '" name="multiple_' . $key . '[]" size="' . $size . '">';

		foreach ($select_ary as $sel_key => $sel_data)
		{
			if (empty($sel_data['disabled']))
			{
				$selected = array_search($sel_key, @$this->new_config[$key]) !== false ? 'selected="selected"' : '';
				$disabled = '';
			}
			else
			{
				$disabled = 'disabled="disabled" class="disabled-option"';
				$selected = '';
			}

			$sel_title = $sel_data['title'];
			$html .= "<option value=\"{$sel_key}\" {$disabled} {$selected}>{$sel_title}</option>";
		}

		return $html . '</select>';
	}

	/**
	*  forum_select() // custom forum select setup
	*/
	function forum_select($ignore_acl = true, $ignore_nonpost = false, $ignore_emptycat = false, $only_acl_post = false)
	{
		$select_ary = make_forum_select(false, false, $ignore_acl, $ignore_nonpost, $ignore_emptycat, $only_acl_post, true);

		foreach ($select_ary as $f_id => $f_data)
		{
			$select_ary[$f_id] = [
				'title'		=> $f_data['padding'] . $f_data['forum_name'],
				'disabled'	=> $f_data['disabled'],
			];
		}

		return $select_ary;
	}

	/**
	* Pick a language, any language ... or no language
	*/
	function language_select($default = '')
	{
		return '<option value="">' . $this->language->lang('DISABLED') . '</option>' . language_select($default);
	}
}
