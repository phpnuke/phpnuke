<?php

namespace rin\editor\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\request\request_interface */
	protected $request;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\config\db_text */
	protected $config_text;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected $root_path;

	public function htmlspecialchars_uni($message)
	{
		$message = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $message);
		$message = str_replace("<", "&lt;", $message);
		$message = str_replace(">", "&gt;", $message);
		$message = str_replace("\"", "&quot;", $message);
		return $message;
	}

	/**
	 * Load common files during user setup
	 *
	 * @param \phpbb\event\data $event The event object
	 * @access public
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'rin/editor',
			'lang_set' => 'rce',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function __construct(\phpbb\config\db_text $config_text, \phpbb\auth\auth $auth, \phpbb\request\request_interface $request, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\config\config $config, \phpbb\user $user, $root_path, $php_ext)
	{
		$this->config_text = $config_text;
		$this->auth = $auth;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->db = $db;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		$Default_Event = array(
			'core.user_setup' => 'load_language_on_setup',
			'core.display_custom_bbcodes' => 'initialize_rceditor',
			'core.viewtopic_modify_page_title' => 'initialize_rceditor',
			'core.viewtopic_post_rowset_data' => 'initialize_rcequickquote',
			'core.text_formatter_s9e_parser_setup' => 'rce_bbcode_perm',
			'core.text_formatter_s9e_configure_after' => 'rce_rmv_ignws',
			'core.text_formatter_s9e_render_after' => 'rce_parse_change',
		);

		return $Default_Event;
	}

	public function initialize_rcequickquote($event)
	{
		if ($this->config['RCE_quickquote'])
		{
			$data = $event['rowset_data'];

			$this->template->assign_block_vars('RCE_POST_ROW',array(
				'RCE_POST_ID'		=> $data['post_id'],
				'RCE_USERNAME'		=> $data['username'],
				'RCE_POST_TIME'		=> $data['post_time'],
				'RCE_USER_ID'		=> $data['user_id'],
			));
		}
		else
		{
			return;
		}
	}

	public function rce_rmv_ignws($event)
	{
		$event['configurator']->rulesGenerator->remove('IgnoreWhitespaceAroundBlockElements');
	}

	public function rce_parse_change($event)
	{
		$event['html'] = preg_replace("#(</?(?:html|address|article|aside|canvas|dd|dl|dt|fieldset|figcaption|figure|footer|h1|h2|h3|h4|h5|h6|header|hgroup|main|nav|noscript|output|pre|section|video|head|body|div|p|form|table|thead|tbody|tfoot|tr|td|th|ul|ol|li|div|p|blockquote|cite|hr)[^>]*>)\s*<br>#i", "$1", $event['html']);
		$event['html'] = preg_replace("#(&nbsp;)+(</?(?:html|address|article|aside|canvas|dd|dl|dt|fieldset|figcaption|figure|footer|h1|h2|h3|h4|h5|h6|header|hgroup|main|nav|noscript|output|pre|section|video|head|body|div|p|form|table|thead|tbody|tfoot|tr|td|th|ul|ol|li|div|p|blockquote|cite|hr)[^>]*>)#i", "$2", $event['html']);
	}

	public function rce_get($key)
	{
		$map = $this->rce_get_array(array($key));

		return isset($map[$key]) ? $map[$key] : null;
	}

	public function rce_get_array(array $keys)
	{
		$sql = 'SELECT *
			FROM ' . CONFIG_TEXT_TABLE . '
			WHERE ' . $this->db->sql_in_set('config_name', $keys, false, true);
		if ((int) $this->config['RCE_cache'])
		{
			$result = $this->db->sql_query($sql, (int) $this->config['RCE_cache']);
		}
		else
		{
			$result = $this->db->sql_query($sql);
		}
		$map = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$map[$row['config_name']] = $row['config_value'];
		}
		$this->db->sql_freeresult($result);

		return $map;
	}

	public function rce_perm_check($tag)
	{
		$bbcodes = json_decode($this->rce_get('RCE_bbcode_permission'), true);
		if (!is_array($bbcodes))
		{
			$bbcodes = explode(',', $bbcodes);
		}
		if (isset($bbcodes['RCE_bbcode_permission_'.$tag]))
		{
			$bbcode_group_value = $bbcodes['RCE_bbcode_permission_'.$tag];
			if (!is_array($bbcode_group_value))
			{
				$bbcode_group_value = explode(',', $bbcode_group_value);
			}
			if (in_array((int) $this->user->data['group_id'],$bbcode_group_value))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}

	public function initialize_rceditor($event, $eventname)
	{
		//check if it will load editor in quickreply and avoid load editor files in index, mchat and ajaxchat
		$rceqenb = true;
		$bbcode_status = $smilies_status = $img_status = $url_status = $flash_status = $quote_status = '';
		$quick_quote_page = false;

		$rcepreurl = $this->request->server('PHP_SELF');
		$rcepreadurl = explode('/', $rcepreurl);
		end($rcepreadurl);
		$rceadurl = prev($rcepreadurl);
		if ($rceadurl!='adm')
		{
			$rceurl = substr($rcepreurl, strrpos($rcepreurl, '/') + 1);
			if (((!$this->config['RCE_enb_quick'] || !$this->config['allow_quick_reply']) && $eventname == 'core.viewtopic_modify_page_title') || ($rceurl == 'index.php' || $rceurl == 'mchat' || $rceurl == 'chat'))
			{
				 $rceqenb = false;
				 $this->template->assign_vars(array('RCE_LOAD'	=> $rceqenb,));
				 return;
			}
		}

		//in quickreply need additional information
		if ($eventname == 'core.viewtopic_modify_page_title')
		{
			$bbcode_status = ($this->config['allow_bbcode'] && $this->auth->acl_get('f_bbcode', $this->request->variable('f', 0))) ? true : false;
			$smilies_status	= ($this->config['allow_smilies'] && $this->auth->acl_get('f_smilies', $this->request->variable('f', 0))) ? true : false;
			$img_status	= ($bbcode_status && $this->auth->acl_get('f_img', $this->request->variable('f', 0))) ? true : false;
			$url_status	= ($this->config['allow_post_links']) ? true : false;
			$flash_status = ($bbcode_status && $this->auth->acl_get('f_flash', $this->request->variable('f', 0)) && $this->config['allow_post_flash']) ? true : false;
			$quote_status = true;
			$quick_quote_page = true;
		}

		//permission check
		$rce_default_bbcode = array('s' => 1, 'sub' => 1, 'sup' => 1, 'align=' => 1, 'font=' => 1, 'hr' => 1, 'youtube' => 1);
		$bbcode_disp_array = array();
		$rce_default_noperm_bbcode = array();

		$sql = 'SELECT display_on_posting, bbcode_tag, bbcode_helpline
			FROM ' . BBCODES_TABLE . '';

		if ((int) $this->config['RCE_cache'])
		{
			$result = $this->db->sql_query($sql, (int) $this->config['RCE_cache']);
		}
		else
		{
			$result = $this->db->sql_query($sql);
		}

		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($this->rce_perm_check($row['bbcode_tag']))
			{
				if (isset($rce_default_bbcode[$row['bbcode_tag']]) && !$row['display_on_posting'])
				{
					$rce_default_noperm_bbcode[$row['bbcode_tag']] = $row['bbcode_tag'];
				}
				else if (!isset($rce_default_bbcode[$row['bbcode_tag']]) && $row['display_on_posting'])
				{
					$bbcode_disp_array[$row['bbcode_tag']] = $row['bbcode_helpline'];
				}
			}
			else
			{
				if (isset($rce_default_bbcode[$row['bbcode_tag']]))
				{
					$rce_default_noperm_bbcode[$row['bbcode_tag']] = $row['bbcode_tag'];
				}
			}
		}

		//add custom bbcode to rin editor
		foreach ($bbcode_disp_array as $bbcode_disp_array_name => $bbcode_disp_array_value)
		{
			if (substr($bbcode_disp_array_name, -1) == "=")
			{
				$this->template->assign_block_vars('RCE_RULES_DES', array('rule' => rtrim($bbcode_disp_array_name, '='), 'help' => $bbcode_disp_array_value));
			}
			else
			{
				$this->template->assign_block_vars('RCE_RULES', array('rule' => $bbcode_disp_array_name, 'help' => $bbcode_disp_array_value));
			}
		}

		//rmv plugin or button (without permission or false in display_on_posting)
		foreach ($rce_default_noperm_bbcode as $rce_default_noperm_bbcode_name)
		{
			switch ($rce_default_noperm_bbcode_name)
			{
				case 's':
					$this->template->assign_block_vars('RCE_RMV_BUTTONS', array('rule' => 'Strike'));
					break;
				case 'sub':
					$this->template->assign_block_vars('RCE_RMV_BUTTONS', array('rule' => 'Subscript'));
					break;
				case 'sup':
					$this->template->assign_block_vars('RCE_RMV_BUTTONS', array('rule' => 'Superscript'));
					break;
				case 'align=':
					$this->template->assign_block_vars('RCE_RMV_PLUGIN', array('rule' => 'justify'));
					break;
				case 'font=':
					$this->template->assign_block_vars('RCE_RMV_BUTTONS', array('rule' => 'Font'));
					break;
				case 'hr':
					$this->template->assign_block_vars('RCE_RMV_PLUGIN', array('rule' => 'horizontalrule'));
					break;
				case 'youtube':
					$this->template->assign_block_vars('RCE_RMV_PLUGIN', array('rule' => 'youtube'));
					break;
			}
		}

		//autogrow
		if ((int) $this->config['RCE_height'] ==  (int) $this->config['RCE_max_height'])
		{
			$this->template->assign_block_vars('RCE_RMV_PLUGIN', array('rule' => 'autogrow'));
		}

		//smile
		$sql = 'SELECT smiley_url, code, display_on_posting, emotion
			FROM ' . SMILIES_TABLE . '
			GROUP BY smiley_url';
		if ((int) $this->config['RCE_cache'])
		{
			$result = $this->db->sql_query($sql, (int) $this->config['RCE_cache']);
		}
		else
		{
			$result = $this->db->sql_query($sql);
		}
		while ($row = $this->db->sql_fetchrow($result))
		{
			if (intval($row['display_on_posting']))
			{
				$this->template->assign_block_vars('RCE_EMOTICONS', array('code' => $this->htmlspecialchars_uni($row['code']), 'url' => $this->root_path . $this->config['smilies_path'] . '/' . $row['smiley_url'], 'name' => $row['emotion']));
			}
			else
			{
				$this->template->assign_block_vars('RCE_EMOTICONS_PLUS', array('code' => $this->htmlspecialchars_uni($row['code']), 'url' => $this->root_path . $this->config['smilies_path'] . '/' . $row['smiley_url'], 'name' => $row['emotion']));
			}
		}

		//fontsize
		$fontsizes = array(50, 85, 100, 150, 200);

		foreach ($fontsizes as &$fontsize)
		{
			$this->template->assign_block_vars('RCE_FONT_SIZES', array('size' => $fontsize));
		}

		//skin
		$style_pref = json_decode($this->rce_get('RCE_style_preference'), true);
		$skin_pref = json_decode($this->rce_get('RCE_skin_preference'), true);

		$skin = $txta = $txtab = '';
		if (empty($style_pref))
		{
			$skin = 'moonocolor';
		}
		else
		{
			if (!is_array($style_pref))
			{
				$style_pref = explode(',', $style_pref);
			}
			foreach ($style_pref as $style_pref_name => $style_pref_value)
			{
				if ((int) $this->user->data['user_style'] == (int) explode('_',$style_pref_name)[3])
				{
					$skin = $style_pref_value;
				}
			}
		}
		if (empty($skin))
		{
			$skin = 'moonocolor';
		}

		if (empty($skin_pref))
		{
			$txta = $this->root_path . 'ext/rin/editor/styles/all/template/js/contents.css';
			$txtab = 0;
		}
		else
		{
			if (!is_array($skin_pref))
			{
				$skin_pref = explode(',', $skin_pref);
			}
			foreach ($skin_pref as $skin_pref_name => $skin_pref_value)
			{
				if (((int) $this->user->data['user_style'] == (int) explode('_',$style_pref_name)[3]) && (int) $skin_pref_value)
				{
					$txta = $this->root_path . 'ext/rin/editor/styles/all/template/js/contents_black.css';
					$txtab = 1;
				}
			}
		}
		if (empty($txta))
		{
			$txta = $this->root_path . 'ext/rin/editor/styles/all/template/js/contents.css';
			$txtab = 0;
		}

		$this->template->assign_vars(array(
			'RCE_LOAD'						=> $rceqenb,
			'RCE_QQ_PAGE'					=> $quick_quote_page,
			'RCE_LANGUAGE'					=> $this->config['RCE_language'],
			'RCE_MOBM_SOURCE'				=> $this->config['RCE_mobm_source'],
			'RCE_SMILEY_SC'					=> $this->config['RCE_smiley_sc'],
			'RCE_AUTOSAVE'					=> $this->config['RCE_autosave'],
			'RCE_AUTOSAVE_MESSAGE'			=> $this->config['RCE_autosave_message'],
			'RCE_HEIGHT'					=> $this->config['RCE_height'],
			'RCE_MAX_HEIGHT'				=> $this->config['RCE_max_height'],
			'RCE_IMGURAPI'					=> $this->config['RCE_imgurapi'],
			'RCE_SKIN'						=> $skin,
			'RCE_CONTENT_SKIN'				=> $txta,
			'RCE_QUICK_QUOTE'				=> $this->config['RCE_quickquote'],
			'RCE_SUP_SMENT'					=> $this->config['RCE_supsment'],
			'RCE_SUP_EXT'					=> $this->config['RCE_supext'],
			'RCE_DES_NOPOP'					=> $this->config['RCE_desnopop'],
			'RCE_PARTIAL'					=> $this->config['RCE_partial'],
			'RCE_SELTEXT'					=> $this->config['RCE_seltxt'],
			'RCE_RMV_COLOR'					=> $this->config['RCE_rmv_acp_color'],
			'RCE_ROOT_PATH'					=> $this->root_path,
			'RCE_SMILEY_PATH'				=> $this->root_path . $this->config['smilies_path'] . '/',
			'RCE_MAX_NAME_CARACT'			=> $this->config['max_name_chars'],
			'RCE_MAX_FONT_SIZE'				=> $this->config['max_post_font_size'],
			'RCE_BBCODE_STATUS'				=> $bbcode_status,
			'RCE_SMILIES_STATUS'			=> $smilies_status,
			'RCE_IMG_STATUS'				=> $img_status,
			'RCE_URL_STATUS'				=> $url_status,
			'RCE_FLASH_STATUS'				=> $flash_status,
			'RCE_QUOTE_STATUS'				=> $quote_status,
			'RCE_TXTA_BLACK'				=> $txtab,
			'RCE_USER_LANGUAGE'				=> $this->user->data['user_lang'],
		));
	}

	public function rce_bbcode_perm($event)
	{
		$bbcodes = json_decode($this->rce_get('RCE_bbcode_permission'), true);
		if (empty($bbcodes))
		{
			return;
		}
		else
		{
			if (!is_array($bbcodes))
			{
				$bbcodes = explode(',', $bbcodes);
			}
			foreach ($bbcodes as $bbcode_name => $bbcode_group_value)
			{
				if (!is_array($bbcode_group_value))
				{
					$bbcode_group_value = explode(',', $bbcode_group_value);
				}
				if (!in_array((int) $this->user->data['group_id'],$bbcode_group_value))
				{
					$bbcode_name = rtrim($bbcode_name, '=');
					$bbcode_name = explode('_',$bbcode_name)[3];
					$event['parser']->disable_bbcode($bbcode_name);
				}
			}
		}
	}
}
