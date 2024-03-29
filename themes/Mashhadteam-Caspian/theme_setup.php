<?php

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

global $nuke_configs;

$theme_aligns = array(
	"rtl" => array(),
	"ltr" => array()
);

$theme_setup = array(
	"default_css" => array(
		"<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/css/bootstrap.min.css\">",
		"".((_DIRECTION == 'rtl') ? "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css\">":"")."",
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/fonts/vazir/style.css\" />",
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/fonts/fontawesome/style.css\" />",
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.css\" />",
		"<link href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/style/bootstrap-social.css\" rel=\"stylesheet\">",
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/style/style.css\" />",
		"<link href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/style/green.css\" rel=\"stylesheet\" id=\"colour-scheme\">",
	),
	"default_js" => array(),
	"defer_js" => array(
		"<script type=\"text/javascript\">var phpnuke_url = '".$nuke_configs['nukeurl']."', phpnuke_cdnurl = '".$nuke_configs['nukecdnurl']."', phpnuke_theme = '".$nuke_configs['ThemeSel']."', nuke_lang = '".(($nuke_configs['multilingual'] == 1) ? $nuke_configs['currentlang']:$nuke_configs['language'])."', nuke_date = ".$nuke_configs['datetype'].";var theme_languages = { success_voted : '"._SUCCESS_VOTED."', try_again : '"._ERROR_TRY_AGAIN."'};var pn_csrf_token = '"._PN_CSRF_TOKEN."';
		</script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery.min.js\"></script>",
		"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.js\"></script>",
		"<!--[if lt IE 9]> <script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/html5shiv/dist/html5shiv.js\"></script><script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/respond/respond.min.js\"></script> <![endif]-->",
		"<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/js/bootstrap.min.js\"></script>",
		"<script type=\"text/javascript\" src=\" ".$nuke_configs['nukecdnurl']."includes/jrating/jRating.jquery.js\"></script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/clipboard.min.js\"></script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/script/script.js\"></script>",
	),
	"default_link_rel" => array(
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/114x114.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/72x72.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"57x57\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/57x57.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/default.png\">",
		"".((file_exists("themes/".$nuke_configs['ThemeSel']."/images/favicon.ico")) ? "<link rel=\"shortcut icon\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/favicon.ico\">":"")."",
	),
	"default_meta" => array(),
	"theme_nav_menus" => array(
		'primary' => _MAIN_MENU,
		'footer'  => _FOOTER_MENU,
	),
	"theme_widgets" => array(
		"right" => _RIGHT_BLOCKS,
		"left" => _LEFT_BLOCKS,
		"topcenter" => _TOPCENTER_BLOCKS,
		"bottomcenter" => _BOTTOMCENTER_BLOCKS,
	),
	"theme_boxes_templates" => "themes/".$nuke_configs['ThemeSel']."/theme_boxes.html",
	"theme_boxes_templates_classes" => array(
		"modules_boxes"	=> "row",
		"top_full_module_boxes" => "",
		"right_module_boxes" => array(
			"_r" => "col-md-4 col-md-pull-8",
			"_l_r" => "col-md-3 col-md-pull-6",
		),
		"middle_module_boxes" => array(
			"_full" => "text-right",
			"_l" => "col-md-8",
			"_r" => "col-md-8 col-md-push-4",
			"_l_r" => "col-md-6 col-md-push-3"
		),
		"left_module_boxes" => array(
			"_l" => "col-md-4",
			"_l_r" => "col-md-3",
		),
		"top_middle_module_boxes" => "",
		"main_middle_module_boxes" => "",
		"bottom_middle_module_boxes" => "",
		"bottom_full_module_boxes" => ""
	),
	'caspian_configs' => isset($nuke_configs['caspian_configs']) ? phpnuke_unserialize($nuke_configs['caspian_configs']):array(
		'active_slider' => 0,
		'slider_image' => array('','','','','','','','','','',''),
		'slider_title' => array('','','','','','','','','','',''),
		'slider_link' => array('','','','','','','','','','',''),
		'slider_desc' => array('','','','','','','','','','',''),
		'about_us' => '',
		'address' => '',
		'phone' => '',
		'mobile' => '',
		'fax' => '',
		'twitter' => '',
		'instagram' => '',
		'facebook' => '',
		'telegram' => '',
		'contact_us' => '',
	)
);

function caspian_theme_config()
{
	global $nuke_configs, $db, $admin_file, $theme_setup;
	
	$contents = '';
	$caspian_configs = $theme_setup['caspian_configs'];
	
	$contents .="
	<tr><th colspan=\"2\" style=\"text-align:center\">".sprintf(_THEME_SETTINGS, "Mashhadteam-Caspian")."</th></tr>
	<tr><th style=\"width:200px;\">"._ACTIVATE_SLIDER."</th><td>";
	$checked1 = ($caspian_configs['active_slider'] == 1) ? "checked":"";
	$checked2 = ($caspian_configs['active_slider'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[caspian_configs][active_slider]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[caspian_configs][active_slider]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th style=\"width:200px;\">"._THEME_COLOR."</th><td>";
	$sel1 = (isset($caspian_configs['theme_color']) && $caspian_configs['theme_color'] == 'green') ? "selected":"";
	$sel2 = (isset($caspian_configs['theme_color']) && $caspian_configs['theme_color'] == 'blue') ? "selected":"";
	$sel3 = (isset($caspian_configs['theme_color']) && $caspian_configs['theme_color'] == 'red') ? "selected":"";
	$contents .= "<select name=\"config_fields[caspian_configs][theme_color]\" class=\"styledselect-select\">
		<option value=\"green\"".$sel1.">"._THEME_GREEN."</option>
		<option value=\"blue\"".$sel2.">"._THEME_BLUE."</option>
		<option value=\"red\"".$sel3.">"._THEME_RED."</option>
	</select>
	</td></tr>";
	
	for($i=0; $i<10; $i++)
	{
		$contents .="
		<tr>
			<th style=\"width:200px;\">"._SLIDER_IMAGES_DATA."</th>
			<td>
				<input type=\"text\" placeholder=\""._PICTURE_LINK."\" class=\"inp-form-ltr\" name=\"config_fields[caspian_configs][slider_image][$i]\" value=\"".$caspian_configs['slider_image'][$i]."\" /> &nbsp; &nbsp;
				<input type=\"text\" placeholder=\""._TITLE."\" class=\"inp-form-ltr\" name=\"config_fields[caspian_configs][slider_title][$i]\" value=\"".$caspian_configs['slider_title'][$i]."\" /> &nbsp; &nbsp;
				<input type=\"text\" placeholder=\""._REFERRAL_LINK."\" class=\"inp-form-ltr\" name=\"config_fields[caspian_configs][slider_link][$i]\" value=\"".$caspian_configs['slider_link'][$i]."\" /> &nbsp; &nbsp;
				<input type=\"text\" placeholder=\""._DESCRIPTIONS."\" class=\"inp-form-ltr\" name=\"config_fields[caspian_configs][slider_desc][$i]\" value=\"".$caspian_configs['slider_desc'][$i]."\" />
			</td>
		</tr>";
	}
	$contents .="
	<tr><th colspan=\"2\" style=\"text-align:center\">"._THEME_FOOTER_DATA."</th></tr>
	<tr><th>"._ABOUTUS_TEXT."</th><td>";
	$contents .= wysiwyg_textarea('config_fields[caspian_configs][about_us]', $caspian_configs['about_us'], 'PHPNukeAdmin', 50, 5);
	$contents .= "
	</td></tr>
	<tr><th>"._POSTAL_ADDRESS."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][address]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['address']."\">
	</td></tr>
	<tr><th>"._LANDLINE_PHONE."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][phone]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['phone']."\">
	</td></tr>
	<tr><th>"._MOBILE_PHONE."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][mobile]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['mobile']."\">
	</td></tr>
	<tr><th>"._FAX."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][fax]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['fax']."\">
	</td></tr>
	<tr><th>"._TWITTER_LINK."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][twitter]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['twitter']."\">
	</td></tr>
	<tr><th>"._INSTAGRAM_LINK."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][instagram]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['instagram']."\">
	</td></tr>
	<tr><th>"._FACEBOOK_LINK."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][facebook]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['facebook']."\">
	</td></tr>
	<tr><th>"._TELEGRAM_LINK."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][telegram]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['telegram']."\">
	</td></tr>
	<tr><th>"._FEEDBACK_LINK."</th><td>
		<input type=\"text\" name=\"config_fields[caspian_configs][contact_us]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$caspian_configs['contact_us']."\">
	</td></tr>";
	 return $contents;
}

function caspian_data_cache($cache_systems)
{
	$cache_systems['caspian_data'] = array(
		'name'			=> "_CASPIAN_DATA",
		'table'			=> 'caspian',
		'auto_load'		=> true
	);
	return $cache_systems;
}

$hooks->add_filter("cache_systems", "caspian_data_cache", 10);

function caspian_settings($other_admin_configs){
	$other_admin_configs['themes']['Mashhadteam-Caspian'] = array("title" => _CASPIAN_THEME_SETTINGS, "function" => "caspian_theme_config", "God" => false);
	return $other_admin_configs;
}

$hooks->add_filter("other_admin_configs", "caspian_settings", 10);


function get_caspian_posts_media()
{
	global $nuke_configs, $hooks;
	
	$block_global_contents = array();
	$block_global_contents = $hooks->apply_filters("global_contents", $block_global_contents);

	$all_medias = array();
	$all_media_titles = array();
	$all_audio_links = array();
	$all_video_links = array();
	$first_audio = '';
	$first_video = '';
	$first_atitle = '';
	$first_vtitle = '';

	//fetch from text
	$text = (isset($block_global_contents['bodytext'])) ? $block_global_contents['bodytext']:"";
	
	if($text != '')
	{
		preg_match_all("#<a(.*)href=['|\"](.*)['|\"](.*)>(.*)</a>#isU", $text, $text_media);

		if(isset($text_media[2]) && !empty($text_media[2]))
		{
			$all_media_links = $text_media[2];
			$all_media_titles = $text_media[4];
			
			foreach($all_media_links as $key => $media_link)
			{
				$media_link_name = str_replace(array("%5B","%5D","%20"),array("[","]"," "),$media_link);

				$media_title_arr = explode("/", $media_link_name);
				$media_title_full = end($media_title_arr);
				
				$media_name_arr = explode(".", $media_link_name);
				$ext = end($media_name_arr);
				
				if(!in_array($ext, ["mp3","ogg","oga","mp4","m4v"]))
					continue;
				
				$media_title = strip_tags($all_media_titles[$key]);
				$media_title = (strtolower($media_title) == "download") ? $block_global_contents['title']:$media_title;
				$media_data = array($media_title, $media_link, $ext);

				if(in_array($ext, array("mp3","ogg","oga")))
				{
					$all_audio_links[$ext][] = $media_data;
				}
				if(in_array($ext, array("mp4","m4v")))
				{
					$all_video_links[$ext][] = $media_data;
				}
				
				$all_medias[$media_link] = $media_data;
			}
		}
		
		if(!empty($all_audio_links))
		{
			ksort($all_audio_links);
			$all_audio_links_arr = array_values($all_audio_links);
			
			$first_audio = (isset($all_audio_links_arr[0][0][1])) ? str_replace(" ","%20", $all_audio_links_arr[0][0][1]):"";
			$first_atitle = (isset($all_audio_links_arr[0][0][0])) ? $all_audio_links_arr[0][0][0]:"";
		}

		if(!empty($all_video_links))
		{
			ksort($all_video_links);	
			$all_video_links_arr = array_values($all_video_links);
			
			$first_video = (isset($all_video_links_arr[0][0][1])) ? str_replace(" ","%20", $all_video_links_arr[0][0][1]):"";
			$first_vtitle = (isset($all_video_links_arr[0][0][0])) ? $all_video_links_arr[0][0][0]:"";
		}
	}
	//fetch from text
	
	//fetch from post files
	if(isset($block_global_contents['download']) && $block_global_contents['download'] != '')
	{
		$post_media = (!is_array($block_global_contents['download'])) ? phpnuke_unserialize(stripslashes($block_global_contents['download'])):$block_global_contents['download'];
		
		$first_audio_sel = false;
		$first_video_sel = false;
		
		foreach($post_media as $file_data)
		{
			if(empty($file_data))
				continue;
				
			$filename = $file_data[0];
			$filelink = $file_data[1];
			$filesize = $file_data[2];
			$filedesc = $file_data[3];
			$filetype = (isset($file_data[3]) && $file_data[3] != '') ? $file_data[3]:'files';
			$media_name_arr = explode(".", $filelink);
			$ext = end($media_name_arr);
			$all_medias[$filelink] = array($filename, $filelink, $ext);
		
			if($filetype == 'audios' && !$first_audio_sel)
			{
				$first_audio = $filelink;
				$first_atitle = $filename;
				$first_audio_sel = true;
			}
			
			if($filetype == 'videos' && !$first_video_sel)
			{
				$first_video = $filelink;
				$first_vtitle = $filename;
				$first_video_sel = true;
			}
		}
	}
	//fetch from post files
	
	$poster = (isset($block_global_contents['post_image']) && $block_global_contents['post_image'] != '') ? $block_global_contents['post_image']:"";
	
	return array($all_medias, $poster, $first_audio, $first_video, $first_atitle, $first_vtitle);
}

$hooks->add_filter("get_theme_media_function", function($theme_get_media_function){return "get_caspian_posts_media";}, 10);

class caspian_nav_menus extends Walker
{

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent\t<".$args->list_type." class=\"".((isset($args->list_class) && $args->list_class != '') ? $args->list_class:"")."\">\n";
	}
		
	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\t</".$args->list_type.">\n";
	}
	
	public function start_el(&$output, $element, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		
		$classes	= empty($element->attributes->classes)	?'' : ' '.$element->attributes->classes;	
		$styles		= empty($element->attributes->styles)	?'' : $element->attributes->styles;	
		$xfn		= empty($element->attributes->xfn)		?'' : $element->attributes->xfn;	
		$target		= empty($element->attributes->target)	?'' : $element->attributes->target;

		$class_names = ' class="menu-item menu-item-'.$element->nid.''.filter($classes, "nohtml").''.(($this->has_children == 1 
	&& $depth != 0) ? ' dropdown-submenu':'').''.(($depth == 0) ? ' main-item':'').'"';
		$styles_codes = ($styles != '') ? ' style="'.$styles.'"':'';
		$xfn_rels_codes = ($xfn != '') ? ' rel="'.$xfn.'"':'';
		$idrel = ' id="menu-item-'.$element->nid.'"';		

		$id				= $element->nid;
		$title			= $element->title;
		$type			= $element->type;
		$part_id		= $element->part_id;
		$module			= $element->module;
		$url			= $element->url;
		
		if($type == 'categories' && $url == '' && $module != '')
		{
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($part_id, $args->nuke_categories[$module], "parent_id", "catname_url"))), "nohtml"), array("/"));
			$cat_link = category_link($module, $cat_title, $attrs=array(), 3);
			$url = end($cat_link);
		}
		
		$output .= $indent . '<li'.$class_names.$styles_codes.$idrel.'>';
		
		$link_before = sprintf($args->link_before, $url, $target, (($this->has_children == 1) ? "dropdown-toggle":""), (($this->has_children == 1) ? "dropdown":""));
		
		$item_output = $args->before . $link_before . $title . $args->link_after . $args->after;
		
		$output .= $item_output;
	}
	
	public function end_el(&$output, $element, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}
?>