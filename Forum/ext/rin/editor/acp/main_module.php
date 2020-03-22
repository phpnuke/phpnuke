<?php

namespace rin\editor\acp;

class main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	protected $phpbb_log;

	public function main($id, $mode)
	{
		global $phpbb_root_path, $phpEx, $phpbb_log, $phpbb_container;

		$this->language = $phpbb_container->get('language');
		$this->template = $phpbb_container->get('template');
		$this->request	= $phpbb_container->get('request');
		$this->config	= $phpbb_container->get('config');
		$this->config_text = $phpbb_container->get('config_text');
		$this->user		= $phpbb_container->get('user');
		$this->db		= $phpbb_container->get('dbal.conn');
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext	= $phpEx;
		$this->phpbb_log  = $phpbb_log;

		$this->tpl_name = 'rin_editor_body';
		$this->page_title = $this->language->lang('ACP_RCE_TITLE');

		add_form_key('rin_editor_settings');

		$rce_config = array(
			'RCE_enb_quick'					=> array('default' => 1,					'validation' => array()),
			'RCE_language'					=> array('default' => '',					'validation' => array()),
			'RCE_mobm_source'				=> array('default' => 1,					'validation' => array()),
			'RCE_smiley_sc'					=> array('default' => 0,					'validation' => array()),
			'RCE_autosave'					=> array('default' => 1,					'validation' => array()),
			'RCE_autosave_message'			=> array('default' => 0,					'validation' => array()),
			'RCE_height'					=> array('default' => 250,					'validation' => array('num', false, 0, 1000)),
			'RCE_max_height'				=> array('default' => 500,					'validation' => array('num', false, 0, 1000)),
			'RCE_quickquote'				=> array('default' => 1,					'validation' => array()),
			'RCE_supsment'					=> array('default' => 0,					'validation' => array()),
			'RCE_supext'					=> array('default' => 0,					'validation' => array()),
			'RCE_desnopop'					=> array('default' => 0,					'validation' => array()),
			'RCE_partial'					=> array('default' => 1,					'validation' => array()),
			'RCE_seltxt'					=> array('default' => 0,					'validation' => array()),
			'RCE_rmv_acp_color'				=> array('default' => 0,					'validation' => array()),
			'RCE_cache'						=> array('default' => 0,					'validation' => array('num', false, 0, 86400)),
			'RCE_imgurapi'					=> array('default' => '',					'validation' => array('string', false, 0, 255)),
			'RCE_skin'						=> array('default' => 'moonocolor',			'validation' => array('string', false, 0, 255)),
		);

		if ($this->request->is_set_post('submit'))
		{
			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}

			$bbcode_array = $style_array = $skin_array = array();
			foreach ($this->request->variable_names() as $param_name => $param_val)
			{
				$param_val_array = explode('_',$param_val);
				if (count($param_val_array)>1)
				{
					if ($param_val_array[1] == 'bbcode')
					{
						$bbcode_array[$param_val] = $this->request->variable($param_val, array('' => ''), true);
					}
					if ($param_val_array[1] == 'style')
					{
						$style_array[$param_val] = $this->request->variable($param_val, '', true);
					}
					if ($param_val_array[1] == 'skin')
					{
						$skin_array[$param_val] = $this->request->variable($param_val, '', true);
					}
				}
			}

			$rce_new_config = array();
			$validation = array();
			foreach ($rce_config as $key => $value)
			{
				$rce_new_config[$key] = $this->request->variable($key, $value['default'], is_string($value['default']));
				if (!empty($value['validation']))
				{
					$validation[$key] = $value['validation'];
				}
			}

			$error = validate_data($rce_new_config, $validation);

			if (!check_form_key('rin_editor_settings'))
			{
				$error[] = 'FORM_INVALID';
			}

			if (empty($error))
			{
				// Set the options the user configured
				foreach ($rce_new_config as $config_name => $config_value)
				{
					if ($config_name == 'RCE_language')
					{
						if ($config_value == 'Default')
						{
							$config_value = '';
						}
					}
					$this->config->set($config_name, $config_value);
				}
				$this->config_text->set('RCE_bbcode_permission', json_encode($bbcode_array));
				$this->config_text->set('RCE_style_preference', json_encode($style_array));
				$this->config_text->set('RCE_skin_preference', json_encode($skin_array));
				// Add an entry into the log table
				$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'RCE_CONFIG_UPDATE', false, array($this->user->data['username']));

				trigger_error($this->language->lang('RCE_SETTING_SAVED') . adm_back_link($this->u_action));
			}

			// Replace "error" strings with their real, localised form
			$error = array_map(array($this->user, 'lang'), $error);
		}
		foreach (array_keys($rce_config) as $key)
		{
			$this->template->assign_var(strtoupper($key), $this->config[$key]);
		}

		$bbcode_group_set = json_decode($this->config_text->get('RCE_bbcode_permission'), true);

		$sql = 'SELECT bbcode_id, bbcode_tag
			FROM ' . BBCODES_TABLE . "
			ORDER BY bbcode_tag ASC";
		$result = $this->db->sql_query($sql);
		$s_bbcode_option = '';
		while ($row = $this->db->sql_fetchrow($result))
		{
			if (isset($bbcode_group_set["RCE_bbcode_permission_".$row['bbcode_tag']]))
			{
				$val = $bbcode_group_set["RCE_bbcode_permission_".$row['bbcode_tag']];
			}
			else
			{
				$val = '';
			}
			$s_bbcode_option .= '<option value="' . rtrim($row['bbcode_tag'], '=') . '">' . $row['bbcode_tag'] . '</option>';
			$this->template->assign_block_vars('RCE_BBCODE_TAGS', array('bbcode_name_trigger' => trim($row['bbcode_tag'], '='), 'bbcode_name' => "RCE_bbcode_permission_".$row['bbcode_tag'], 'bbcode_id' => $row['bbcode_id'], 'bbcode_tag' => $row['bbcode_tag'], 'group' => $this->select_groups($val, "RCE_bbcode_permission_".$row['bbcode_tag'])));
		}
		$this->db->sql_freeresult($result);

		$s_bbcode_options = '<select id="bbcode" name="bbcode">';
		$s_bbcode_options .= $s_bbcode_option;
		$s_bbcode_options .= '</select>';

		$style_pref = json_decode($this->config_text->get('RCE_style_preference'), true);
		$skin_pref = json_decode($this->config_text->get('RCE_skin_preference'), true);

		$sql = 'SELECT style_id, style_name
			FROM ' . STYLES_TABLE;
		$result = $this->db->sql_query($sql);
		$s_style_option = '';
		while ($row = $this->db->sql_fetchrow($result))
		{
			if (isset($style_pref["RCE_style_preference_".$row['style_id']]))
			{
				$val = $style_pref["RCE_style_preference_".$row['style_id']];
			}
			else
			{
				$val = 'moonocolor';
			}
			if (isset($skin_pref["RCE_skin_preference_".$row['style_id']]))
			{
				$val2 = (int) $skin_pref["RCE_skin_preference_".$row['style_id']];
			}
			else
			{
				$val2 = 0;
			}
			$s_style_option .= '<option value="' . $row['style_name'] . '">' . $row['style_name'] . '</option>';
			$this->template->assign_block_vars('RCE_STYLE_SKIN', array('name' => $row['style_name'], 'id' => $row['style_id'], 'skin' => $this->skin_folder($val, "RCE_style_preference_".$row['style_id']), 'black' => $this->yes_no_options($val2, "RCE_skin_preference_".$row['style_id'])));
		}
		$this->db->sql_freeresult($result);

		$s_style_options = '<select id="style" name="style">';
		$s_style_options .= $s_style_option;
		$s_style_options .= '</select>';

		$lang_opts = array('Default', 'af', 'ar', 'bg', 'bn', 'bs', 'ca', 'cs', 'cy', 'da', 'de', 'el', 'en', 'en-au', 'en-ca', 'en-gb', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fo', 'fr', 'fr-ca', 'gl', 'gu', 'he', 'hi', 'hr', 'hu', 'id', 'is', 'it', 'ja', 'ka', 'km', 'ko', 'ku', 'lt', 'lv', 'mk', 'mn', 'ms', 'nb', 'nl', 'no', 'pl', 'pt', 'pt-br', 'ro', 'ru', 'si', 'sk', 'sl', 'sq', 'sr', 'sr-latn', 'sv', 'th', 'tr', 'tt', 'ug', 'uk', 'vi', 'zh', 'zh-cn');
		$this->template->assign_vars(array(
			'RCE_LANG_SELECT'	=> $this->array_to_options($this->config['RCE_language'], $lang_opts, 'RCE_language'),
			'RCE_ROOT_PATH'		=> $this->phpbb_root_path,
			'U_ACTION'			=> $this->u_action,
			'RCE_BBCODE_OPTION'	=> $s_bbcode_options,
			'RCE_STYLE_OPTION'	=> $s_style_options
		));
	}

	public function skin_folder($val, $id)
	{
		if (function_exists('glob'))
		{
			return $this->array_to_options($val, glob($this->phpbb_root_path . 'ext/rin/editor/styles/all/template/js/skins/*' , GLOB_ONLYDIR), '' . $id . '');
		}
		else
		{
			return '<input type="text" name="' . $id . '" id="' . $id . '" size="50" maxlength="100" value="'. $val .'" />';
		}

	}

	public function array_to_options($value, $arr, $id)
	{
		$s_array_to_options = '<select id="' . $id . '" name="' . $id . '">';
		foreach ($arr as $opt)
		{
			$id_split = explode('_',$id);
			if (count($id_split)>1)
			{
				if ($id_split[1] == 'style')
				{
					$opt = explode('/',$opt);
					$opt = end($opt);
				}
			}
			$s_array_to_options .= '<option value="' . $opt . '"' . (($value == $opt) ? ' selected="selected"' : '') . '>' . $opt . '</option>';
		}

		$s_array_to_options .= '</select>';

		return $s_array_to_options;
	}

	public function select_groups($group, $key)
	{
		if (!is_array($group))
		{
			$group = explode(',', $group);
		}
		$sql = 'SELECT group_id, group_name, group_type
			FROM ' . GROUPS_TABLE . "
			ORDER BY group_type DESC, group_name ASC";
		$result = $this->db->sql_query($sql);
		$s_group_option = '';
		while ($row = $this->db->sql_fetchrow($result))
		{
			$selected = (in_array($row['group_id'], $group)) ? ' selected="selected"' : '';
			$s_group_option .= '<option' . (($row['group_type'] == GROUP_SPECIAL) ? ' class="sep"' : '') . ' value="' . $row['group_id'] . '"' . $selected . '>' . (($row['group_type'] == GROUP_SPECIAL) ? $this->language->lang('G_' . $row['group_name']) : $row['group_name']) . '</option>';
		}
		$this->db->sql_freeresult($result);

		$s_group_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		$s_group_options .= $s_group_option;
		$s_group_options .= '</select>';

		return $s_group_options;
	}

	public function yes_no_options($value, $id)
	{
		$checked = 'checked="checked" ';
		if ($value)
		{
			$val_yes = $checked;
			$val_no = '';
		}
		else
		{
			$val_yes = '';
			$val_no = $checked;
		}
		$yes_no_options = '<input type="radio" class="radio" name="' . $id . '" value="1" ' . $val_yes . '/> ' . $this->language->lang('YES') . ' &nbsp;';
		$yes_no_options .= '<input type="radio" class="radio" name="' . $id . '" value="0" ' . $val_no . '/> ' . $this->language->lang('NO') . '';

		return $yes_no_options;
	}
}
