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

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{

	/*********************************************************/
	/* Language Functions                                    */
	/*********************************************************/

	function common()
	{
		global $admin_file, $op;
		$contents = '';
		$contents .="
		<p align=\"center\"><b>"._LANGUAGE_ADMIN."</b></p><br /><br />
		<p align=\"center\">
			[ <a href=\"".$admin_file.".php?op=language\">"._LANGUAGE_PHRASES_ADMIN."</a>";
			if($op != 'edit_language_word')
				$contents .="| <a href=\"#\" id=\"add_lang_button\">"._ADD_PHRASE."</a>";
			$contents .=" ]
		</p><br /><br />
			<script>
			$( \"#add_lang_button\" ).click(function( event ) {
				event.preventDefault();
				$('#add_lang').toggle('10000');
			});
			</script>
		";
		return $contents;
	}
	
	function language($new_word="", $new_word_equals="", $word_search="")
	{
		global $db, $hooks, $admin_file, $nuke_configs, $page;
		
		$nuke_languages_cacheData = get_cache_file_contents('nuke_languages');
	
		$pagetitle = _LANGUAGE_ADMIN;
		
		$pagetitle .= (isset($word_search) && $word_search != "") ? " - "._PHRASE_SEARCH." » ($word_search)":"";
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("comments" => $pagetitle);});
		
		if(isset($new_word) && $new_word != "" && isset($new_word_equals) && is_array($new_word_equals) && !empty($new_word_equals))
		{
			if (!array_key_exists($new_word,$nuke_languages_cacheData))
			{
				$db->table(LANGUAGES_TABLE)
					->insert([
						'main_word' => $new_word,
						'equals' => addslashes(serialize($new_word_equals))
					]);
					
				cache_system("nuke_languages");
				add_log(sprintf(_SAVE_NEW_PHRASE_LOG, $new_word), 1);
				header("location: ".$admin_file.".php?op=language");
			}
			else
			{
				header("location: ".$admin_file.".php?op=language&new_word=$new_word");
			}
			die();
		}
		
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= common();
		
		$languageslists = get_dir_list('language', 'files');
		$nuke_languages = get_languages_data('all');
		
		if(isset($new_word) && $new_word != "")
		{
			$contents .= "<br /><br /><p align=\"center\"><b><a href=\"".$admin_file.".php?op=edit_language_word&word=$new_word\">"._PHRASE_EXISTS."</a></b></p><br /><br />";
		}
		$contents .= "<form action=\"".$admin_file.".php\" method=\"post\">
		<table width=\"100%\" class=\"id-form product-table no-border\" id=\"add_lang\" style=\"display:none;\">
			<tr>
				<th style=\"width:200px;\">"._MAIN_PHRASE."</th>
				<td>
				<input type=\"text\" size=\"30\" class=\"inp-form dleft\" name=\"new_word\" />
				".bubble_show(_ONLY_ALPHAMETRICS)."
				</td>
			</tr>";
			foreach($languageslists as $languageslist)
			{
				if($languageslist != "")
				{
					if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
					$languageslist = str_replace(".php", "", $languageslist);
					$contents .= "
					<tr>
						<th>"._EQUAL_TO." ".ucfirst($languageslist)."</th>
						<td>
							<input class=\"inp-form".((isset($nuke_languages[$languageslist]['_DIRECTION']) && $nuke_languages[$languageslist]['_DIRECTION'] == 'ltr') ? " dleft":"")."\" type=\"text\" size=\"70\" name=\"new_word_equals[$languageslist]\" />
						</td>
					</tr>";
				}
			}
		$contents .= "
		<tr>
			<td colspan=\"2\"><input type=\"submit\" class=\"form-submit\" /></td>
		</tr>
		</table>
		<input type=\"hidden\" name=\"op\" value=\"language\" />
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
		
		$contents .= CloseAdminTable();
		
		$contents .= OpenAdminTable();
		$contents .= "
		<form action=\"".$admin_file.".php\" method=\"post\">
		<div class=\"aleft dleft\" style=\"margin-bottom:10px;\">
			<input type=\"text\" class=\"inp-form dleft\" name=\"word_search\" size=\"50\" placeholder=\""._PHRASE_SEARCH."\" value=\"$word_search\">
			<input type=\"hidden\" name=\"op\" value=\"language\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</div>
		</form>
		<table width=\"100%\" class=\"product-table\">
		<tr>
			<th class=\"table-header-repeat line-left\" style=\"width:250px;\">"._PHRASE."</th>
			<th class=\"table-header-repeat line-left\">"._EQUAL_TO_IN_CURLANG." (".$nuke_configs['language'].")</th>
			<th class=\"table-header-repeat line-left\" style=\"width:120px;\">"._OPERATION."</th>
		</tr>";
				
		$entries_per_page				= 20;
		$current_page					= (empty($page)) ? 1 : $page;
		$start_at						= ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = $admin_file.".php?op=language".((isset($word_search) && $word_search != '') ? "&word_search=$word_search":"");
		$direction = $nuke_languages[$nuke_configs['currentlang']]['_DIRECTION'];
		
		if(isset($word_search) && $word_search != '')
		{
			$searched_nuke_languages = array();			
			foreach($nuke_languages as $inlang => $language_values)
			{
				$language_values_by_arr = array_keys($language_values);
				
				foreach($language_values_by_arr as $v)
				{
					if(mb_stristr($v, $word_search) OR mb_stripos($v, $word_search) !== false OR mb_stristr($language_values[$v], $word_search) OR mb_stripos($language_values[$v], $word_search) !== false)
					{
						$searched_nuke_languages[$inlang][$v] = $language_values[$v];
						foreach($languageslists as $languageslist)
							if(isset($nuke_languages[$languageslist][$v]))
								$searched_nuke_languages[$languageslist][$v] = $nuke_languages[$languageslist][$v];
					}
				}
			}
			unset($nuke_languages);
			$nuke_languages = $searched_nuke_languages;
			unset($searched_nuke_languages);
		}
		
		$all_language_data = array();
		foreach($nuke_languages as $inlang => $language_values)
		{
			$all_language_data = array_merge($all_language_data, $language_values);
		}
		
		$newICounter = (($start_at + $entries_per_page) <= sizeof($all_language_data)) ? ($start_at + $entries_per_page) : sizeof($all_language_data);
		$nuke_languages_by_arr = array_keys($all_language_data);
		$j = 1;
		
		for($i=$start_at; $i < $newICounter; $i++)
		{
			$word = $nuke_languages_by_arr[$i];
			$equal_in_current_lang = (isset($nuke_languages[$nuke_configs['currentlang']][$word])) ? $nuke_languages[$nuke_configs['currentlang']][$word]:"";
			
			$contents .= "<tr>
				<td align=\"center\" dir=\"ltr\">".highlightWords($word, $word_search)."</td>
				<td align=\"center\">".highlightWords($equal_in_current_lang, $word_search)."</td>
				<td align=\"center\">";
					if(isset($nuke_languages_cacheData[$word]) && !empty($nuke_languages_cacheData[$word]))
						$contents .= " <a class=\"table-icon icon-2 info-tooltip\" href=\"".$admin_file.".php?op=delete_language_word&word=$word&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" onclick=\"return confirm('"._DELETE_PHRASE_CONFIRM."');\"></a>";
					$contents .= "<a class=\"table-icon icon-1 info-tooltip\" href=\"".$admin_file.".php?op=edit_language_word&word=$word\" title=\""._EDIT."\"></a>
					<a class=\"table-icon icon-7 info-tooltip thickbox\" href=\"#TB_inline?height=300&inlineId=word_data_".$j."&width=600\" title=\""._VIEW."\"></a>
					<div id=\"word_data_".$j."\" style=\"display:none;\">
						<p>
							<table width=\"100%\" class=\"product-table no-border\">";
								foreach($nuke_languages as $inlang => $language_values)
								{
									$contents .= "<tr>
										<th style=\"width:120px;\">$inlang : </th>
										<td".(($direction == 'ltr') ? " style=\"direction:ltr;\"":"").">".((isset($language_values[$word])) ? $language_values[$word]:"")."</td>
									</tr>";
								}
							$contents .= "</table>
						</p>
					</div>
				</td>
			</tr>";
			$j++;
		}
		$contents .= "<tr>
			<td colspan=\"3\" class=\"pagination\">";
				$contents .= admin_pagination(sizeof($all_language_data), $entries_per_page, $page, $link_to);
			$contents .= "</td>
			</tr>";
		$contents .= "</table>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function edit_language_word($word, $word_equals)
	{
		global $db, $hooks, $admin_file, $nuke_configs;

		$nuke_languages_cacheData = get_cache_file_contents('nuke_languages');
		
		if(isset($word) && $word != "" && isset($word_equals) && is_array($word_equals) && !empty($word_equals))
		{
			if (!array_key_exists($word,$nuke_languages_cacheData))
			{
				$db->table(LANGUAGES_TABLE)
					->insert([
						'main_word' => $word,
						'equals' => addslashes(serialize($word_equals))
					]);
					
				add_log(sprintf(_SAVE_NEW_PHRASE_LOG, $word), 1);
			}
			else
			{
				$db->table(LANGUAGES_TABLE)
					->where('main_word' , $word)
					->update([
						'equals' => addslashes(serialize($word_equals))
					]);
					
				add_log(sprintf(_UPDATE_PHRASE_LOG, $word), 1);
			}
			
			cache_system("nuke_languages");
			header("location: ".$admin_file.".php?op=language");
			die();
		}
		$pagetitle = ""._LANGUAGE_ADMIN_EDIT." (<span dir=\"ltr\">$word</span>)";
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("comments" => $pagetitle);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= common();
		
		$languageslists = get_dir_list('language', 'files');
		$nuke_languages = get_languages_data('all');
		
		$contents .= "
		<br /><br />
		<form action=\"".$admin_file.".php\" method=\"post\">
		<table width=\"100%\" class=\"id-form product-table no-border\" id=\"add_lang\">
			<tr>
				<th style=\"width:200px;\">"._MAIN_PHRASE."</th>
				<td class=\"dleft a"._TEXTALIGN1."\">
				<b>$word</b>
				<input type=\"hidden\" size=\"30\" name=\"word\" value=\"$word\" />
				</td>
			</tr>";
			foreach($languageslists as $languageslist)
			{
				if($languageslist != "")
				{
					if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
					$languageslist = str_replace(".php", "", $languageslist);
					$equal = (isset($nuke_languages_cacheData[$word][$languageslist])) ? $nuke_languages_cacheData[$word][$languageslist]:((isset($nuke_languages[$languageslist][$word]) ? $nuke_languages[$languageslist][$word]:""));
					
					$contents .= "
					<tr>
						<th>"._EQUAL_TO." ".ucfirst($languageslist)."</th>
						<td>
							<input class=\"inp-form".(($nuke_languages[$languageslist]['_DIRECTION'] == 'ltr') ? " dleft":"")."\" type=\"text\" size=\"70\" name=\"word_equals[$languageslist]\" value=\"".htmlentities($equal)."\" />
						</td>
					</tr>";
				}
			}
		$contents .= "
		<tr>
			<td colspan=\"2\"><input type=\"submit\" class=\"form-submit\" /></td>
		</tr>
		</table>
		<input type=\"hidden\" name=\"op\" value=\"edit_language_word\" />
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
		
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function delete_language_word($word)
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file, $nuke_configs;
		
		$db->table(LANGUAGES_TABLE)
			->where('main_word' , $word)
			->delete();
			
		cache_system("nuke_languages");
		add_log(sprintf(_DELETE_PHRASE_LOG, $word), 1);
		header("location: ".$admin_file.".php?op=language");
	}
	
	$word = (isset($word)) ? $word:'';
	$word_equals = (isset($word_equals)) ? $word_equals:'';
	$new_word = (isset($new_word)) ? $new_word:'';
	$new_word_equals = (isset($new_word_equals)) ? $new_word_equals:'';
	$word_search = (isset($word_search)) ? $word_search:'';
	
	switch($op)
	{

		case "language":
		language($new_word, $new_word_equals, $word_search);
		break;

		case "edit_language_word":
		edit_language_word($word, $word_equals);
		break;

		case "delete_language_word":
		delete_language_word($word);
		break;

	}

}
else
{
	header("location: ".$admin_file.".php");
}

?>