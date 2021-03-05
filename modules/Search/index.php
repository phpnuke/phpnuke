<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

function search_form($search_query='', $module = 'Articles', $author = '', $category = 0, $time = 0)
{
	global $db, $userinfo, $page, $module_name, $visitor_ip, $nuke_configs, $hooks, $search_type;
	
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	$modules_search_data = array();
	$modules_search_data = $hooks->apply_filters("modules_search_data", $modules_search_data);
	
	$hooks->add_filter("site_theme_headers", function ($theme_setup) use($nuke_configs, $modules_search_data)
	{
		$theme_setup = array_merge_recursive($theme_setup, array(
			"defer_js" => array(
				"<script>
					var first_module = '$module';
					var selected_category = '$category';
					var search_data = ".((!empty($modules_search_data)) ? json_encode($modules_search_data):"[]").";
					var search_language = {
						all_categories : '"._ALL_CATEGORIES."'
					}
				</script>",
				"<script src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/search.js\"></script>"
			)
		));
		return $theme_setup;
	}, 10);

	$st_sel1 = ($search_type == 1) ? "selected":"";
	$st_sel2 = ($search_type == 2) ? "selected":"";
	$st_sel3 = ($search_type == 3) ? "selected":"";
	$st_sel4 = ($search_type == 4) ? "selected":"";
	
	$contents = '';
	$contents .= "
	<form action=\"".LinkToGT("index.php?modname=$module_name")."\" method=\"post\" role=\"search\" class=\"form-horizontal\">
	<div class=\"well\">
		<div class=\"form-group addon\">
			<label class=\"sr-only\" for=\"search_query\">"._PHRASE."</label>
			<div class=\"input-group\">
				<div class=\"input-group-addon\">"._PHRASE."</div>
				<input type=\"text\" name=\"search_query\" class=\"form-control input-lg\" id=\"search_query\" placeholder=\""._SEARCH." ...\" value=\"$search_query\">
				<div class=\"input-group-btn\">
					<button class=\"btn btn-default input-lg\" type=\"submit\" name=\"submit\" value=\"ok\"><i class=\"glyphicon glyphicon-search\"></i></button>
				</div>
			</div>
		</div>
		<div class=\"form-group\">
		<label for=\"search_type\" class=\"col-sm-1 col-form-label sr-only\">"._SEARCH_TYPE."</label>
		<div class=\"col-sm-2\">
			<select name=\"search_type\" class=\"form-control\" id=\"search_type\">
				<option value=\"1\" $st_sel1>"._SEARCH_WITH_ANYOFONE."</option>
				<option value=\"2\" $st_sel2>"._SEARCH_EXACT_OF."</option>
				<option value=\"3\" $st_sel3>"._SEARCH_STARTS_WITH."</option>
				<option value=\"4\" $st_sel4>"._SEARCH_WITHOUT."</option>
			</select>
		</div>";
		if(!empty($modules_search_data))
		{			
			$contents .= "
				<label for=\"search_module\" class=\"col-sm-1 col-form-label sr-only\">"._SEARCH_IN."</label>
				<div class=\"col-sm-2\">
					<select name=\"search_module\" class=\"form-control\" id=\"search_module\">";
						foreach($modules_search_data as $search_data_key => $search_data_value)
						{
							$sel = ($module == $search_data_key) ? " selected":"";
							$search_data_value['title'] = (defined($search_data_value['title'])) ? constant($search_data_value['title']):$search_data_value['title'];
							$contents .= "<option value=\"$search_data_key\"$sel>".$search_data_value['title']."</option>";
						}
						$contents .= "
					</select>
				</div>
				<span id=\"search_category_html\">
					<label for=\"search_category\" class=\"col-sm-1 col-form-label sr-only\">"._CATEGORY."</label>
					<div class=\"col-sm-3\">
						<select name=\"search_category\" class=\"form-control\" id=\"search_category\"><option value=\"0\" selected>"._ALL_CATEGORIES."</option></select>
					</div>
				</span>
				<span id=\"search_author_html\">
					<label for=\"search_author\" class=\"col-sm-1 col-form-label sr-only\">"._AUTHOR."</label>
					<div class=\"col-sm-3\">
						<select name=\"search_author\" class=\"form-control\" id=\"search_author\">
						<option value=\"0\" selected>"._ALL_AUTHORS."</option>";
						foreach($nuke_authors_cacheData as $aid => $authors_data)
						{
							$sel = ($author == $aid) ? " selected":"";
							$authors_data['realname'] = (isset($authors_data['realname']) && $authors_data['realname'] != '') ? $authors_data['realname']:$aid;
							$contents .="<option value=\"$aid\"$sel>".$authors_data['realname']."</option>";
						}
						$contents .="</select>
					</div>
				<span>
				<span id=\"search_time_html\">
					<label for=\"search_time\" class=\"col-sm-1 col-form-label sr-only\">"._DATE."</label>
					<div class=\"col-sm-2\">
						<select name=\"search_time\" class=\"form-control\" id=\"search_time\">";
							
							$sel1 = ($time == 1) ? " selected":"";
							$sel7 = ($time == 7) ? " selected":"";
							$sel14 = ($time == 14) ? " selected":"";
							$sel30 = ($time == 30) ? " selected":"";
							$sel60 = ($time == 60) ? " selected":"";
							$sel90 = ($time == 90) ? " selected":"";
							$sel183 = ($time == 183) ? " selected":"";
							$sel365 = ($time == 365) ? " selected":"";
							
							$contents .="<option value=\"0\">"._ALL."</option>
							<option value=\"1\"$sel1>1 "._DAY."</option>
							<option value=\"7\"$sel7>1 "._WEEK."</option>
							<option value=\"14\"$sel14>2 "._WEEKS."</option>
							<option value=\"30\"$sel30>1 "._MONTH."</option>
							<option value=\"60\"$sel60>2 "._MONTHS."</option>
							<option value=\"90\"$sel90>3 "._MONTHS."</option>
							<option value=\"183\"$sel183>6 "._MONTHS."</option>
							<option value=\"365\"$sel365>1 "._YEAR."</option>
						</select>
					</div>
				<span>";
		}
		$contents .= "
			</div>
		</div>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
	</form>";
	return $contents;
}

function search_main($submit = '', $search_query='', $search_module = 'Articles', $search_author = '', $search_category = 0, $search_time = 0, $search_type = 2)
{
	global $db, $userinfo, $page, $module_name, $visitor_ip, $PnValidator, $nuke_configs, $hooks;
	$contents = '';
	
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	$finish = false;
	$link_to = "index.php?modname=$module_name&search_module=$search_module";
	
	$search_data = array(
		"search_query"	=> $search_query,
		"category"		=> intval($search_category),
		"time"			=> $search_time,
		"author"		=> $search_author,
		"module"		=> $search_module,
	);
	
	if((isset($search_query) && $search_query != ''))
	{
		$skipWords  = (isset($nuke_configs['mtsn_skipwords']) && !empty($nuke_configs['mtsn_skipwords'])) ? array_map('trim',explode(',',$nuke_configs['mtsn_skipwords'])):array();
		$sWords     = array();
		
		$modules_search_data = array();
		$modules_search_data = $hooks->apply_filters("modules_search_data", $modules_search_data);
	
		$this_search_data = isset($modules_search_data[$search_module]) ? $modules_search_data[$search_module]:$modules_search_data['Articles'];

		$PnValidator->validation_rules(array(
			'search_query'	=> 'required|regex,/([^\>])+$/i'
		)); 
		
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'search_query'	=> 'sanitize_string|rawurldecode|htmlspecialchars',
			'category'		=> 'sanitize_numbers',
			'time'			=> 'sanitize_numbers',
			'author'		=> 'sanitize_string'
		)); 

		$search_data = $PnValidator->sanitize($search_data, array(), true, true);
		$validated_data = $PnValidator->run($search_data);

		if($validated_data !== FALSE)
		{
			$search_data = $validated_data;
		}
		else
		{

			include ("header.php");
			$contents .= OpenTable();
			$contents .= "<p align=\"center\">".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>";
			$contents .= CloseTable();
			$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
			include("footer.php");
		}
				
		if(isset($search_data['search_query']) && $search_data['search_query'] != '')
		{
			if(trim($search_data['search_query']))
			{			
				$thisSearchTerm = stripslashes($search_data['search_query']);
				
				$strtolower = function_exists( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower';
				$checked = array();
				
				$splitKeys   = explode(' ',$thisSearchTerm);
				$skipLower   = array_map($strtolower,$skipWords);
				
				foreach ( $splitKeys as $splitKey ) {
					// keep before/after spaces when splitKey is for exact match
					if ( preg_match( '/^".+"$/', $splitKey ) )
						$splitKey = trim( $splitKey, "\"'" );
					else
						$splitKey = trim( $splitKey, "\"' " );

					// Avoid single A-Z and single dashes.
					if ( ! $splitKey || ( 1 === strlen( $splitKey ) && preg_match( '/^[a-z\-]$/i', $splitKey ) ) )
						continue;

					if ( in_array( call_user_func( $strtolower, $splitKey ), $skipLower, true ) )
						continue;

					$newSearch[] = $splitKey;
				}
			
				if (!empty($newSearch))
					$thisSearchTerm = implode(' ',$newSearch);
				
				$where = array();
				$params = array();

				$where[] = $this_search_data['where'];
				
				$link_to .= "&search_query=".rawurlencode($thisSearchTerm)."";
					
				$search_in_field	= array_keys($this_search_data['search_in_field']);
				
				$search_query		= array();
				$fileds_query		= array();
				$search_type = intval($search_type);
				
				foreach($search_in_field as $value)
				{
					$fileds_query[] = "$value {CONDITION}";
				}
				$fileds_query = implode(" ".(($search_type != 4) ? "OR":"AND")." ", $fileds_query);
				
				switch($search_type)
				{
					case "1":
						$wordsAry = explode(" ", $thisSearchTerm);
						foreach($wordsAry as $key => $word)
						{
							$params[":thisSearchTerm_val_$key"] = "%$word%";
							$query_set[] = str_replace("{CONDITION}", "LIKE '%" . $word . "%'", $fileds_query);
						}
						$query_set = implode(" OR ", $query_set);
					break;
					case "2":
							$query_set = str_replace("{CONDITION}", "LIKE '%" . $thisSearchTerm . "%'", $fileds_query);
					break;
					case "3":
						$query_set = str_replace("{CONDITION}", "LIKE '" . $thisSearchTerm . "%'", $fileds_query);
					break;
					case "4":
						$query_set = str_replace("{CONDITION}", "NOT LIKE '%" . $thisSearchTerm . "%'", $fileds_query);
					break;
				}
				
				$query_set = ($query_set != '') ? "(".$query_set.")":"";
				
				if($search_data['author'] != '' && array_key_exists($search_data['author'], $nuke_authors_cacheData))
				{
					$params[":search_data_author"] = $search_data['author'];
					$where[] = "".$this_search_data['author_field']." = :search_data_author";
					$link_to .= "&search_author=".$search_data['author']."";
				}
				
				if(intval($search_data['category']) != 0)
				{
					$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
					$all_sub_cats = array_unique(get_sub_categories_id($search_data['module'], $search_data['category'], $nuke_categories_cacheData, array($search_data['category'])));
					$catwhere = array();
					if(!empty($all_sub_cats))
						foreach($all_sub_cats as $sub_cat)
						{
							$params[":cat_$sub_cat"] = $sub_cat;
							$catwhere[] = "FIND_IN_SET(:cat_$sub_cat, ".$this_search_data['categories_field'].")";
						}
					$params[":catlink"] = intval($search_data['category']);
					$catwhere[] = "".$this_search_data['category_field']." = :catlink";
					$where[] = "(".implode(" OR ", $catwhere).")";
					$link_to .= "&search_category=".$search_data['category']."";
				}
				
				if(intval($search_data['time']) != 0)
				{
					$search_time = _NOWTIME - intval($search_data['time'])*86400;
					$params[":search_time"] = $search_time;
					$where[] = "".$this_search_data['time_field']." >= :search_time";
					$link_to .= "&search_time=".$search_data['time']."";
				}

				$where				= (!empty($where)) ? " AND ".implode(" AND ", $where):"";

				$entries_per_page	= $nuke_configs['home_pagination'];
				$current_page		= (empty($page)) ? 1 : $page;
				$start_at			= ($current_page * $entries_per_page) - $entries_per_page;
				
				$result = $db->query("SELECT ".implode(", ", $this_search_data['fetch_fields']).", (SELECT COUNT(".$this_search_data['fetch_fields'][0].") FROM ".$this_search_data['table']." WHERE ".$query_set."".$where.") as total_rows FROM ".$this_search_data['table']." WHERE ".$query_set."".$where." ORDER BY ".$this_search_data['orderby']." DESC LIMIT $start_at, $entries_per_page", $params);

				if($result->count() > 0)
				{
					$rows = $result->results();
					if(isset($this_search_data['parse_result']) && $this_search_data['parse_result'] != '')
						$this_search_data['parse_result']($rows);
					
					$total_rows = intval($rows['total_rows']);
					unset($rows['total_rows']);

					foreach ($rows as $row)
					{
						
						$link = $this_search_data['more_link']($row);
						$row['link'] = LinkToGT($link);
						
						if(file_exists("themes/".$nuke_configs['ThemeSel']."/".$this_search_data['search_template'].".php"))
							include("themes/".$nuke_configs['ThemeSel']."/".$this_search_data['search_template'].".php");
						elseif(function_exists($this_search_data['search_template']))
							$contents .= $this_search_data['search_template']($row);
						else
						{
							$contents .= "
							<div class=\"panel panel-info\">
								<div class=\"panel-heading\"> <span class=\"glyphicon glyphicon-list-alt\"></span><b> <a href=\"".$row['link']."\">".$row['title']."</a></b></div>
								<div class=\"panel-body\">
									".$row['title']."
								</div>
							</div>";
						}
					}					

					if($entries_per_page < $total_rows)
					{
						$contents .= "<div id=\"pagination\">";
						$contents .= clean_pagination($total_rows, $entries_per_page, $current_page, $link_to);
						$contents .= "</div>";
					}
					
				}
				else
				{
					$contents .= OpenTable();
					$contents .= _NO_RESULT_FOUND;
					$contents .= CloseTable();
				}
			}
		}
		else
			$contents .= '';
	}
	else
		$contents .= '';

	$meta_tags = array(
		"url" => LinkToGT($link_to),
		"title" => ""._SEARCH."".((isset($search_data['search_query']) && $search_data['search_query'] != '' && trim($search_data['search_query'])) ? " : ". $search_data['search_query']:""),
		"description" => ((isset($search_data['search_query']) && $search_data['search_query'] != '' && trim($search_data['search_query'])) ? " : ". $search_data['search_query']:""),
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("search_header_meta", $meta_tags, $search_data, $link_to);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($search_module, $meta_tags, $search_data){
		$breadcrumbs['search'] = array(
			"name" => _SEARCH,
			"link" => LinkToGT("index.php?modname=Search&search_module=$search_module"),
			"itemtype" => "WebPage"
		);
		if($search_data['search_query'] != '')
		{
			$breadcrumbs['search-query'] = array(
				"name" => $search_data['search_query'],
				"link" => $meta_tags['url'],
				"itemtype" => "WebPage"
			);
		}
		return $breadcrumbs;
	}, 10);
	unset($meta_tags);
	
	include("header.php");
	$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), search_form($search_data['search_query'], $search_data['module'], $search_data['author'], $search_data['category'], $search_data['time']).$contents);
	include("footer.php");
}

function search_categories($module, $selected_category)
{
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$categories = new categories_list($nuke_categories_cacheData[$module]);
	$categories->categories_list();
	asort($categories->result);
	
	$contents = '';
	foreach($categories->result as $cid => $catname)
	{
		if($catname == 'uncategorized') continue;
		$sel = ($cid == $selected_category) ? " selected":"";
		$contents .= "<option value=\"$cid\"$sel>$catname</option>";
	}
	die($contents);
}

$submit					= (isset($submit)) ? filter($submit, "nohtml"):"";
$search_query			= (isset($search_query)) ? filter($search_query, "nohtml"):"";
$search_module			= (isset($search_module)) ? filter($search_module, "nohtml"):"Articles";
$search_author			= (isset($search_author)) ? filter($search_author, "nohtml"):'';
$search_category		= (isset($search_category)) ? intval($search_category):0;
$search_time			= (isset($search_time)) ? $search_time:0;
$search_type			= (isset($search_type)) ? intval($search_type):2;
$op						= (isset($op)) ? filter($op, "nohtml"):"search_home";
$module					= (isset($module)) ? filter($module, "nohtml"):"Articles";

switch ($op)
{
	default:
		search_main($submit, $search_query, $search_module, $search_author, $search_category, $search_time, $search_type);
	break;
	case"search_categories":
		search_categories($module, $selected_category);
	break;
}

?>