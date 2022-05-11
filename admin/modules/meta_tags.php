<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2017 by MashhadTeam                                    */
/* http://www.phpnuke.ir                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

global $db, $admin_file;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	function seo()
	{
		global $hooks, $db, $admin, $admin_file, $mode, $nuke_configs;

		$hooks->add_filter("set_page_title", function(){return array("seo" => _SEO_ADMIN);});
		
		$site_meta_tags = stripslashes($nuke_configs['site_meta_tags']);
		$site_description = stripslashes($nuke_configs['site_description']);
		$site_keywords = ($nuke_configs['site_keywords'] != '') ? explode(",", stripslashes($nuke_configs['site_keywords'])):array();
		$sitename = $nuke_configs['sitename'];
		$nukeurl = $nuke_configs['nukeurl'];
		$gtset = $nuke_configs['gtset'];
		$userurl = $nuke_configs['userurl'];
		$contents = '';
		$contents .="
		<script type=\"text/javascript\">
		$(function() {
			$('#container').tabs({hide: { effect: 'fade', duration: 300 }});                            
		});
		</script>";
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		
		$seo_tabs_list = $hooks->apply_filters("meta_tags_tablist", array(
			"main" => _MAIN_SETTINGS,
			"engines" => _SEARCH_ENGINS,
			"analytics" => _GOOGLE_ANALYTICS,
			"feeds" => _WEBSITE_FEEDS,
			"pings" => _PING_SETTINGS,
		));
		$contents .= "
		<div id=\"container\" align=\"center\">
			<ul>";
			foreach($seo_tabs_list as $key => $seo_tabs_list_item)
			{
				$contents .= "<li><a href=\"#$key\"><span>$seo_tabs_list_item</span></a></li>";
			}
			$contents .= "
			</ul>";

			if(isset($seo_tabs_list['main']))
			{
				$check1 = ($gtset == 1) ? "checked":"";
				$check2 = ($gtset == 0) ? "checked":"";

				$check11 = ($nuke_configs['breadcrumb_cat'] == 1) ? "checked":"";
				$check12 = ($nuke_configs['breadcrumb_cat'] == 0) ? "checked":"";
					
				$sel1 = ($userurl == 1) ? "checked":"";
				$sel2 = ($userurl == 2) ? "checked":"";
				$sel3 = ($userurl == 3) ? "checked":"";
				$sel4 = ($userurl == 4) ? "checked":"";
				$sel5 = ($userurl == 5) ? "checked":"";
				$sel6 = ($userurl == 6) ? "checked":"";
				
				/************ main setting **********/	
				$contents .= "
				<div id=\"main\" style=\"line-height:20px;\">
					<div class=\"text-center\"><font class='option'><b>" . _GENSITEINFO . "</b></font></div>
					<form action='".$admin_file.".php' method='post'>
						<table border=\"0\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
							<tr>
								<th style=\"width:200px\">"._ENABLE_SEO_FUTURES."</th>
								<td>
									<input data-label=\"" . _YES . "\" type=\"radio\" class=\"styled\" name=\"config_fields[gtset]\" value=\"1\" $check1> &nbsp; <input data-label=\"" . _NO . "\" type=\"radio\" class=\"styled\" name=\"config_fields[gtset]\" value=\"2\" $check2>
									".bubble_show(_SEOONLYONLINUXSERVERS)."
								</td>
							</tr>
							<tr>
								<th>"._KEYWORDS."</th>
								<td>
									<select class=\"styledselect-select tag-input\" name=\"config_fields[site_keywords][]\" multiple=\"multiple\" style=\"width:100%\">";
									if(isset($site_keywords) && !empty($site_keywords))
									{
										foreach($site_keywords as $site_keyword)
											$contents .= "<option value=\"$site_keyword\" selected>$site_keyword</option>\n";
									}
									$contents .= "</select>	
									".bubble_show(_KEYWORDS_OFFER)."
								</td>
							</tr>
							<tr>
								<th>"._BRIEF_SITEDECRIPTION."</th>
								<td>
									<textarea class=\"form-textarea\" name='config_fields[site_description]' cols='70' rows='4' placeholder=\""._BRIEF_SITEDECRIPTION_PH."\">$site_description</textarea>
								</td>
							</tr>
							<tr>
								<th>"._METATAGS."</th>
								<td>
									<textarea class=\"form-textarea dleft\" name='config_fields[site_meta_tags]' cols='70' rows='4' placeholder=\""._METATAGS."\">$site_meta_tags</textarea>
									".bubble_show(_METATAGS_OFFER)."
								</td>
							</tr>
							<tr>
								<th>"._PERMALINKFORMAT."</th>
								<td>
								<table width=\"100%\">
										<tr>
											<td width=\"200\"><input data-label=\""._NAMEANDDAY."\" type=\"radio\" class=\"styled\" name=\"config_fields[userurl]\" value=\"1\" $sel1></td>
											<td>".bubble_show("<div style=\"direction:ltr;\">".$nukeurl."1395/01/01/"._ARTICLETITLE."/</div>")."</td>
										</tr>
										<tr>
											<td width=\"200\"><input data-label=\""._NAMEANDMONTH."\" type=\"radio\" class=\"styled\" name=\"config_fields[userurl]\" value=\"2\" $sel2></td>
											<td>".bubble_show("<div style=\"direction:ltr;\">".$nukeurl."1395/01/"._ARTICLETITLE."/</div>")."</td>
										</tr>
										<tr>
											<td width=\"200\"><input data-label=\""._NAMEANDSID."\" type=\"radio\" class=\"styled\" name=\"config_fields[userurl]\" value=\"3\" $sel3></td>
											<td>".bubble_show("<div style=\"direction:ltr;\">".$nukeurl."1234/"._ARTICLETITLE."/</div>")."</td>
										</tr>
										<tr>
											<td width=\"200\"><input data-label=\""._NAMEONLY."\" type=\"radio\" class=\"styled\" name=\"config_fields[userurl]\" value=\"4\" $sel4></td>
											<td>".bubble_show("<div style=\"direction:ltr;\">".$nukeurl.""._ARTICLETITLE."/</div>")."</td>
										</tr>
										<tr>
											<td width=\"200\"><input data-label=\""._NAMEANDCATEGORY."\" type=\"radio\" class=\"styled\" name=\"config_fields[userurl]\" value=\"5\" $sel5></td>
											<td>".bubble_show("<div style=\"direction:ltr;\">".$nukeurl.""._CATEGORY."/"._ARTICLETITLE."/</div>")."</td>
										</tr>
									</table>		
								</td>
							</tr>
							<tr>
								<th style=\"width:200px\">"._BREADCRUMB_CAT."</th>
								<td>
									<input data-label=\"" . _YES . "\" type=\"radio\" class=\"styled\" name=\"config_fields[breadcrumb_cat]\" value=\"1\" $check11> &nbsp; <input data-label=\"" . _NO . "\" type=\"radio\" class=\"styled\" name=\"config_fields[breadcrumb_cat]\" value=\"0\" $check12>
								</td>
							</tr>
							<input type='hidden' name='op' value='saveseo'><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<tr>
								<td></td>
								<td><input class=\"form-submit\" type='submit' value='" . _SAVECHANGES . "'></td>
							</tr>
						</table>
					</form>
				</div>";
			}
		
			if(isset($seo_tabs_list['engines']))
			{
				/********** Search engines **********/
				
				$gverify = filter($nuke_configs['gverify'], "nohtml");
				$alexverify = filter($nuke_configs['alexverify'], "nohtml");
				$yverify = filter($nuke_configs['yverify'], "nohtml");
				$ganalytic = filter($nuke_configs['ganalytic'], "nohtml");
				$gcse = filter($nuke_configs['gcse'], "nohtml");
				
				$contents .= "
				<div id=\"engines\">
					<form action='".$admin_file.".php' method='post'>
						<table border=\"0\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
							<tr>
								<th style=\"width:150px\">"._GOOGLE_VERIFYCODE." </th>
								<td>
									<input type='text' class=\"inp-form\" name='config_fields[gverify]' value='$gverify' size='40' maxlength='255' placeholder=\""._YOUR_CODE."\">
									".bubble_show(_GOOGLE_VERIFYCODE_HELP)."
								</td>
							</tr>
							<tr>
								<th>"._ALEXA_VERIFYCODE." </th>
								<td>
									<input type='text' class=\"inp-form\" name='config_fields[alexverify]' value='$alexverify' size='40' maxlength='255' placeholder=\""._YOUR_CODE."\">
									".bubble_show(_ALEXA_VERIFYCODE_HELP)."
								</td>
							</tr>
							<tr>
								<th>"._YAHOO_VERIFYCODE."</th>
								<td>
									<input type='text' class=\"inp-form\" name='config_fields[yverify]' value='$yverify' size='40' maxlength='255' placeholder=\""._YOUR_CODE."\">
									".bubble_show(_YAHOO_VERIFYCODE_HELP)."
								</td>
							</tr>
							<tr>
								<th>"._GOOGLE_ANALYTICSCODE."</th>
								<td>
									<input type='text' class=\"inp-form\" name='config_fields[ganalytic]' value='$ganalytic' size='40' maxlength='255' placeholder=\""._YOUR_CODE."\">
									".bubble_show(_GOOGLE_ANALYTICSCODE_HELP)."
								</td>
							</tr>
							<tr>
								<th>"._GOOGLESEARCHCODE."</th>
								<td>
									<input type='text' class=\"inp-form\" name='config_fields[gcse]' value='$gcse' size='40' maxlength='255' placeholder=\""._YOUR_CODE."\">
									".bubble_show(_GOOGLESEARCHCODE_HELP)."
								</td>
							</tr>
							<tr>
								<td>
									<input type='hidden' name='op' value='savesets'>
									<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
									<input class=\"form-submit\" type='submit' value='" . _SAVECHANGES . "'>
								</td>
							</tr>
						</table>
					</form>
				</div>";
			}
		
			if(isset($seo_tabs_list['analytics']))
			{
				/******* GooGle Analytics ******/
				$contents .= "
				<div id=\"analytics\">
					<div style=\"text-align:center;width: auto;margin: 0px;\">
						<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" height=\"570\" width=\"800\">
							<param name=\"movie\" value=\"includes/visitors_overview.swf\">
							<param name=\"quality\" value=\"high\">
							<embed src=\"includes/visitors_overview.swf\" quality=\"high\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" height=\"570\" width=\"800\">
						</object>
					</div>
					<div style=\"clear:both;\"></div>
				</div>";
			}
		
			if(isset($seo_tabs_list['feeds']))
			{
				/********* FEEDS **********/			
				$feedlink = linkToGT("index.php?modname=Feed");
				
				$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
				
				$contents .= "
				<div id=\"feeds\">
					<table width=\"100%\" class=\"id-form product-table no-border\">
						<tr>
							<th style=\"width:200px\">"._MAIN_SITE_FEED."</th>
							<td>
								<input class=\"inp-form dleft\" type=\"text\" name=\"feeds\" value=\"$feedlink\" style=\"width:400px;\" readonly />
								".bubble_show("<a target=\"_blank\" href=\"$feedlink\">"._LATEST_FEED."</a>")."
							</td>
						</tr>";

						foreach($nuke_categories_cacheData as $module_name => $cat_data)
						{
							if(!is_active($module_name))
								continue;
							$new_module = '';
							$contents .= "
							<tr>
								<th rowspan=\"".(sizeof($cat_data)+1)."\">"._MODULE_FEED." $module_name</th>
								<td>
							</tr>";

							foreach($cat_data as $catid => $catdata)
							{
								$catname_url = filter($catdata['catname_url'], "nohtml");
								$cattext = filter(category_lang_text($catdata['cattext']), "nohtml");
								
								$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($catid, $nuke_categories_cacheData[$module_name], "parent_id", "catname_url"))), "nohtml"), array("/"));
							
								$attrs = array(
									"title" => "{CAT_TEXT}",
									"id" => "category-{CATID}"
								);
								$catlink_arr = category_link($module_name, $cat_title, $attrs, 3);
								$catlink = rtrim(end($catlink_arr), "/")."/feed/";
								
								if($new_module != $module_name)
								{
									$new_module = $module_name;
								}
								
								$contents .= "
								<tr>
									<td>
										<input class=\"inp-form dleft\" type=\"text\" name=\"feeds\" value=\"".$catlink."\" style=\"width:400px;\" readonly />
										".bubble_show("<a target=\"_blank\" href=\"$catlink\">$cattext</a>")."
									</td>
								</tr>";
							}
						}
				
				$contents .= "</table>
				</div>";
			}
		
			if(isset($seo_tabs_list['pings']))
			{
				/********* pings **********/
				
				$active_pings = $nuke_configs['active_pings'];
				$ping_sites = $nuke_configs['ping_sites'];
				$nuke_configs['ping_options'] = (isset($nuke_configs['ping_options']) && $nuke_configs['ping_options'] != '') ? unserialize($nuke_configs['ping_options']):array("limit_ping" => 0, "limit_number" => 1, "limit_time" => 15);
				$limit_ping_chk = ($nuke_configs['ping_options']['limit_ping'] == 1) ? "checked":"";	
				$check1 = ($active_pings == 1) ? "checked":"";
				$check2 = ($active_pings == 0) ? "checked":"";
				
				$contents .= "
				<div id=\"pings\">
					<form action='".$admin_file.".php' method='post'>
						<table width=\"100%\" class=\"id-form product-table no-border\">
							<tr>
								<th>"._SITES_LINK."</th>
								<td>
									<textarea class=\"form-textarea dleft\" name='config_fields[ping_sites]' cols='70' rows='8' placeholder=\""._SITES_LINK."\">$ping_sites</textarea>
									".bubble_show(_SITES_LINK_HELP)."
								</td>
							</tr>
							<tr>
								<th style=\"width:200px\">"._ACTIVATE_PING."</th>
								<td>
									<input type=\"radio\" data-label=\"" . _YES . "\" class=\"styled\" name=\"config_fields[active_pings]\" value=\"1\" $check1> &nbsp; 
									<input data-label=\"" . _NO . "\" type=\"radio\" class=\"styled\" name=\"config_fields[active_pings]\" value=\"0\" $check2>
								</td>
							</tr>
							<tr>
								<th>"._SPAM_FILTERBY." </th>
								<td>
									".sprintf(_PING_PER_TIME, "<input type='number' min=\"1\" max=\"10\" class=\"inp-form\" name='config_fields[ping_options][limit_number]' value='".$nuke_configs['ping_options']['limit_number']."' size='10'>", "<input type='number' min=\"1\" class=\"inp-form\" name='config_fields[ping_options][limit_time]' value='".$nuke_configs['ping_options']['limit_time']."' size='5'>")."
									".bubble_show("<div style=\"margin-top:-7px;\"><span style=\"float:"._TEXTALIGN1.";margin-top:7px\">"._PUBLISH_AT_FUTURE."</span> <input data-label=\""._YES."\" type=\"checkbox\" class=\"styled\" name=\"config_fields[ping_options][limit_ping]\" value=\"1\" $limit_ping_chk /></div>")."
								</td>
							</tr>
							<tr>
								<td>
									<input type='hidden' name='op' value='savepings'>
									<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
									<input class=\"form-submit\" type='submit' value='" . _SAVECHANGES . "'>
								</td>
							</tr>
						</table>
					</form>
				</div>";
			}
		$contents = $hooks->apply_filters("meta_tags_contents", $contents, $seo_tabs_list);
		$contents .= "</div>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function saveseo($config_fields)
	{
		global $db, $admin_file, $hooks;
		$breadcrumb_cat = intval($config_fields['breadcrumb_cat']);
		$userurl = intval($config_fields['userurl']);
		$gtset = intval($config_fields['gtset']);
		$site_meta_tags = addslashes($config_fields['site_meta_tags']);
		$site_description = addslashes($config_fields['site_description']);

		if(isset($config_fields['site_keywords']) && !empty($config_fields['site_keywords']))
		{
			$site_keywords = implode(",", $config_fields['site_keywords']);
		}
		
		$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE 
			WHEN config_name = 'gtset' THEN ?
			WHEN config_name = 'userurl' THEN ?
			WHEN config_name = 'breadcrumb_cat' THEN ?
			WHEN config_name = 'site_description' THEN ?
			WHEN config_name = 'site_keywords' THEN ?
			WHEN config_name = 'site_meta_tags' THEN ?
		END
		WHERE config_name IN('gtset', 'userurl', 'breadcrumb_cat', 'site_description', 'site_keywords', 'site_meta_tags')", [$gtset, $userurl, $breadcrumb_cat, $site_description, $site_keywords, $site_meta_tags]);
		$hooks->do_action("meta_tags_saveseo", $config_fields);
		cache_system('nuke_configs');
		add_log(_SEOSAVELOG, 1);
        Header("Location: ".$admin_file.".php?op=seo#main");
	}
	
	function savepings($config_fields)
	{
		global $db, $admin_file, $hooks;
		$ping_sites = str_replace("\r", "", $config_fields['ping_sites']);
		$active_pings = intval($config_fields['active_pings']);
			
		$ping_options = (is_array($config_fields['ping_options']) && !empty($config_fields['ping_options'])) ? $config_fields['ping_options']:array("limit_ping" => 0, "limit_number" => 1, "limit_time" => 15);
		
		$ping_options['limit_ping'] = (isset($config_fields['ping_options']['limit_ping']) && $config_fields['ping_options']['limit_ping'] == 1) ? 1:0;
		
		$ping_options = phpnuke_serialize($ping_options);
		
		$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE 
			WHEN config_name = 'ping_sites' THEN ?
			WHEN config_name = 'active_pings' THEN ?
			WHEN config_name = 'ping_options' THEN ?
		END
		WHERE config_name IN('ping_sites', 'active_pings', 'ping_options')", [$ping_sites, $active_pings, $ping_options]);
		$hooks->do_action("meta_tags_savepings", $config_fields);
		cache_system('nuke_configs');
		add_log(_PINGSAVELOG, 1);
        Header("Location: ".$admin_file.".php?op=seo#pings");
	}
	
	function savesets($config_fields)
	{
		global $db, $admin_file, $hooks;
		$gverify = filter($config_fields['gverify'], "nohtml", 1);
		$alexverify = filter($config_fields['alexverify'], "nohtml", 1);
		$yverify = filter($config_fields['yverify'], "nohtml", 1);
		$ganalytic = filter($config_fields['ganalytic'], "nohtml", 1);
		$gcse = filter($config_fields['gcse'], "nohtml", 1);
		
		$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE 
			WHEN config_name = 'gverify' THEN ?
			WHEN config_name = 'alexverify' THEN ?
			WHEN config_name = 'yverify' THEN ?
			WHEN config_name = 'ganalytic' THEN ?
			WHEN config_name = 'gcse' THEN ?
		END
		WHERE config_name IN('gverify', 'alexverify', 'yverify', 'ganalytic', 'gcse')", [$gverify, $alexverify, $yverify, $ganalytic, $gcse]);
		$hooks->do_action("meta_tags_savesets", $config_fields);
		cache_system('nuke_configs');
		add_log(_SEARCHENGSAVELOG, 1);

		Header("Location: ".$admin_file.".php?op=seo#engines");
	}

	$config_fields = (isset($config_fields)) ? $config_fields:array();
	
	switch($op)
	{
		default:
			seo();
		break;
		case "saveseo":
			saveseo($config_fields);
		break;
		case "savesets":
			savesets($config_fields);
		break;
		case "savepings":
			savepings($config_fields);
		break;
	}
}
else
	header("location: ".$admin_file.".php");

?>