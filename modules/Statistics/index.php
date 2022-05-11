<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

if(!defined("INDEX_FILE"))
	define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

function statistics()
{
	global $db, $module_name, $nuke_configs, $users_system, $hooks;
	$contents = '';	
	$now_time = _NOWTIME;
	
	$today_day = date("d");
	$today_month = date("m");
	$today_year = date("Y");
	$start_of_day = mktime(0,0,0,$today_month,$today_day,$today_year);
	
	$last_month = $start_of_day-2592000;
	$last_month_day = correct_date_number(date("d", $last_month));
	$last_month_month = correct_date_number(date("m", $last_month));
	$last_month_year = date("Y", $last_month);
	
	$users_table_exists = users_table_exists();

	if($users_table_exists)
	{
		$result = $db->query("SELECT ".$users_system->user_fields['user_regdate']." as user_regdate FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['user_regdate']." >= :last_month AND ".$users_system->user_fields['user_regdate']." <= :now_time AND ".$users_system->user_fields['common_where']."", array(":last_month" => $last_month, ":now_time" => $now_time));
		
		if(intval($db->count()) > 0)
		{
			$registered_users = array();
			$rows = $result->results();
			
			foreach($rows as $row1)
			{
				$user_regdate = $row1['user_regdate'];
				$user_regdate_year = date("Y", $user_regdate);
				$user_regdate_month = date("m", $user_regdate);
				$user_regdate_day = date("d", $user_regdate);
				$user_regdate_hour = date("H", $user_regdate);
				if(isset($registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day][$user_regdate_hour]))
					$registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day][$user_regdate_hour]++;
				else
					$registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day][$user_regdate_hour] = 1;
			}
		}
	}
	$result = $db->query("SELECT * FROM ".STATISTICS_TABLE." WHERE id >= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = :last_month_year AND month = :last_month_month AND day >= :last_month_day ORDER BY id ASC LIMIT 1), 1) AND id <= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = :today_year AND month = :today_month AND day <= :today_day ORDER BY id DESC LIMIT 1), (SELECT id FROM ".STATISTICS_TABLE." ORDER BY id DESC LIMIT 1))", 
	array(
		":last_month_year" => $last_month_year, 
		":last_month_month" => $last_month_month, 
		":last_month_day" => $last_month_day, 
		":today_year" => $today_year,
		":today_month" => $today_month,
		":today_day" => $today_day,
	));
	
	$main_chart_data = array();
	if(!empty($result))
	{
		foreach($result as $row)
		{
			$hourly_info = objectToArray(json_decode($row['hourly_info']));
			$visitors = intval($row['visitors']);
			$this_year = intval($row['year']);
			$this_month = correct_date_number(intval($row['month']));
			$this_day = correct_date_number(intval($row['day']));
			foreach($hourly_info as $hour => $hits)
			{
				if($hits == 0) continue;
				$this_time = mktime($hour,0,0, $this_month, $this_day, $this_year);
				$datetime = nuketimes($this_time, false, false, false, 1);
				$main_chart_data[] = '
				{
					"c-1": '.$hits.',
					"c-2": '.$visitors.',
					"c-3": '.((isset($registered_users[$this_year][$this_month][$this_day][$hour])) ? $registered_users[$this_year][$this_month][$this_day][$hour]:0).',
					"date": "'.$datetime.'\n'._HOUR.' '.$hour.'"
				}';
			}
			$hourly_info = null;
		}
	}
	
	$main_chart_data = "[".implode(",\n", $main_chart_data)."]";

	$statistics = array();
	/*$statistics['total_visitors'] = array();
	$result = $db->table(STATISTICS_TABLE)
					->where('visitor_ips', '!=', "''")
					->select(['visitor_ips']);
	$rows = $result->results();

	if($result->count() > 0)
	{
		foreach ($rows as $row)
		{
			$visitor_ips = ($row['visitor_ips'] != "") ? objectToArray(json_decode($row['visitor_ips'])):array();
			if(!empty($visitor_ips))
				$statistics['total_visitors'] = array_merge($statistics['total_visitors'], $visitor_ips);
		}
	}
	$statistics['total_visitors'] = array_unique($statistics['total_visitors']);	
	$total_visitors = sizeof($statistics['total_visitors']);*/
	
	$result = $db->table(STATISTICS_COUNTER_TABLE)
				->order_by(['type' => 'DESC', 'var' => 'ASC'])
				->select(['type', 'var', 'count']);
	if($result->count() > 0)
	{
		$rows = $result->results();
		foreach ($rows as $row)
		{
			$type = stripslashes(check_html($row['type'], "nohtml"));
			$var = stripslashes(check_html($row['var'], "nohtml"));
			$count = intval($row['count']);
			
			if(($type == "total") && ($var == "hits"))
				$total_hits = $count;
			
			$statistics[$type][$var] = array($count, number_format((100 * $count / $total_hits), 0));
		}
	}
	
	$browsers_chart_data = array();
	foreach($statistics['browser'] as $browser_key => $browser_hits)
	{
		//if($browser_hits[0] == 0) continue;
		$browsers_chart_data[] = "{
			\"browser\": \"$browser_key\",
			\"value\": $browser_hits[0]
		}";
	}
	$browsers_chart_data = "[".implode(",\n", $browsers_chart_data)."]";
	
	$os_chart_data = array();
	$statistics['os'] = array_reverse($statistics['os'], true);
	foreach($statistics['os'] as $os_key => $os_hits)
	{
		//if($os_hits[0] == 0) continue;
		$os_chart_data[] = "{
			\"os\": \"$os_key\",
			\"value\": $os_hits[0]
		}";
	}
	$os_chart_data = "[".implode(",\n", $os_chart_data)."]";
	
	$nuke_statistics_data = array();
	$nuke_statistics_data = $hooks->apply_filters("modules_statistics_data", $nuke_statistics_data);
			
	$rows_title = array(
		"total_users" => _USERS,
		"total_authors" => _AUTHORS,
		"total_comments" => _COMMENTS,
	);
	
	$statistics_arr = array();
	foreach($nuke_statistics_data as $module => $statistics_part)
	{
		foreach($statistics_part as $part_key => $statistics_data)
		{
			$statistics_arr[] = "(SELECT COUNT(".$statistics_data['count'].") FROM ".$statistics_data['table']."".(($statistics_data['where'] != '') ? " WHERE ".$statistics_data['where']."":"").") as ".$part_key."";
			$rows_title[$part_key] = (defined($statistics_data['title'])) ? constant($statistics_data['title']):$statistics_data['title'];
		}
	}
	
	$statistics_sql = (!empty($statistics_arr)) ? implode(",\n", $statistics_arr):"";
	
	$statistics_result = $db->query("SELECT 
		COUNT(".$users_system->user_fields['user_id'].") AS total_users,
		(SELECT COUNT(aid) FROM ".AUTHORS_TABLE.") as total_authors,
		(SELECT COUNT(cid) FROM ".COMMENTS_TABLE." WHERE status = '1') as total_comments
		".(($statistics_sql != '') ? ", ".$statistics_sql:"")."
		FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['common_where']."
	");
	$all_rows = array();
	if($statistics_result->count() > 0)
		$all_rows = $statistics_result->results()[0];
	
	$hooks->add_functions_vars(
		'statistics_assets',
		array(
			"module_name" => $module_name,
			"main_chart_data" => $main_chart_data,
			"browsers_chart_data" => $browsers_chart_data,
			"os_chart_data" => $os_chart_data,
		)
	);

	$hooks->add_filter("site_theme_headers", "statistics_assets", 10);

	$meta_tags = array(
		"title" 				=> _VIEWERS_STATISTICS,
		"description" 			=> _STATISTICS_DESCRIPTION,
		"keywords" 				=> ''._VIEWERS_STATISTICS.', statistics'
	);
	$meta_tags = $hooks->apply_filters("select_statistics_header_meta", $meta_tags);
		
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);
	
	$hooks->add_filter("site_breadcrumb", "statistics_breadcrumb", 10);	
	unset($meta_tags);
	
	include("header.php");

	if(file_exists("themes/".$nuke_configs['ThemeSel']."/statistics_main.php"))
		include("themes/".$nuke_configs['ThemeSel']."/statistics_main.php");
	elseif(function_exists("statistics_main"))
		$contents .= statistics_main($statistics);
	else 
	{
		$contents .= "
			<div class=\"col-md-12\">
				".OpenTable(_VIEWERS_STATISTICS, 'success')."
				<div class=\"text-center\" dir=\""._DIRECTION."\">
					<div class=\"stats_icon\" dir=\""._DIRECTION."\"></div>
					<div class=\"stats_info\">
						".sprintf(_PAGES_VIEWED_BY, $total_hits)."
						<br />
						<a href=\"".LinkToGT("index.php?modname=$module_name&op=advanced_statistics")."\">"._ADVANCED_STATISTICS."</a>
					</div>
				</div>
				".CloseTable()."
			</div>
			<div class=\"col-md-12\">
			".OpenTable(_LAST_MONTH_STATISTICS)."
				<div id=\"serial_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			".CloseTable()."
			</div>
		
			<div class=\"col-md-6\">
			".OpenTable(_BROWSERS)."
			<div id=\"browsers_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			<div id=\"browsers_chart_legend\" class=\"pie_chart_legend\"></div>
			".CloseTable()."
			</div>

			<div class=\"col-md-6\">
			".OpenTable(_OPERATION_SYSTEMS)."
			<div id=\"os_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			<div id=\"os_chart_legend\" class=\"pie_chart_legend\"></div>
			".CloseTable()."
			</div>
			<div class=\"col-md-12\">
			".OpenTable(_OTHER_STATISTICS, 'info')."";
			$i=1;
			if(!empty($all_rows))
			{
				foreach($all_rows as $rows_key => $rows_value)
				{
					$contents .= "<div class=\"col-sm-6\">";
					$contents .= progressbar_theme($rows_title[$rows_key], $rows_value, 'info', false);
					$contents .= "</div>";
				}
			}
			$contents .= "
			".CloseTable()."
		</div>";
	}
	
	$contents = $hooks->apply_filters("main_statistics", $contents);
	
	$html_output .= show_modules_boxes($module_name, "advanced", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);

	include("footer.php");
}

function advanced_statistics($year=0, $month=0, $day=0)
{
	global $db, $module_name, $nuke_configs, $users_system, $hooks;

	$contents = '';
	$today_unixtime = mktime(0,0,0, date("m"), date("d"), date("Y"));
	$today_datetime = nuketimes($today_unixtime, false, false, false, 2);
	
	$today_year = intval($today_datetime[0]);
	$today_month = intval($today_datetime[1]);
	$today_day = intval($today_datetime[2]);
	
	$date_formt1 = array($today_year, 1, 1);
	$date_formt2 = array(($today_year+1), 1, 1);
	
	$year = intval($year);
	$month = intval($month);
	$day = intval($day);
	
	$mode = ($year == 0 && $month == 0 && $day == 0) ? "all":(($year != 0 && $month == 0 && $day == 0) ? "yearly":(($month != 0 && $day == 0) ? "monthly":"daily"));
	
	$date_formt = array();
	
	
	if($year != 0)
	{
		$date_formt1 = array($year, 1, 1);
		$date_formt2 = array(($year+1), 1, 1);
		
		if($month != 0)
		{
			$date_formt1 = array($year, $month, 1);
			$next_month = ($month == 12) ? 1:($month+1);
			$next_year = ($month == 12) ? ($year+1):$year;
			$date_formt2 = array($next_year, $next_month, 1);
			
			if($day != 0)
			{
				$date_formt1 = $date_formt2 = array($year, $month, $day);
			}
		}
	}
	
	$datetime1 = all_to_gregorian($date_formt1[0], $date_formt1[1], $date_formt1[2]);
	$datetime2 = all_to_gregorian($date_formt2[0], $date_formt2[1], $date_formt2[2]);	

	$where = ($mode != "all") ? " WHERE id >= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = :year1 AND month = :month1 AND day >= :day1 ORDER BY id ASC LIMIT 1), 1) AND id <= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = :year2 AND month = :month2 AND day <".(($day != 0) ? "=":"")." :day2 ORDER BY id DESC LIMIT 1), (SELECT id FROM ".STATISTICS_TABLE." ORDER BY id DESC LIMIT 1))":"";
	
	$params = ($mode != "all") ? array(
		":year1" => $datetime1[0],
		":month1" => $datetime1[1],
		":day1" => $datetime1[2],
		":year2" => $datetime2[0],
		":month2" => $datetime2[1],
		":day2" => $datetime2[2],
	):array();
	
	$first_hourly_data = false;
	$statistics['total_hits'] = 0;
	$statistics['total_visitors']['all_visitors'] = array();
	
	$result = $db->query("SELECT year, month, day, hits, hourly_info, visitor_ips, visitors FROM ".STATISTICS_TABLE."".$where."", $params);
	if(!empty($result))
	{
		foreach($result as $row)
		{
			$hits = intval($row['hits']);
			$visitors = intval($row['visitors']);
			$this_year = intval($row['year']);
			$this_month = intval($row['month']);
			$this_day = intval($row['day']);

			$this_time = mktime(0,0,0, $this_month, $this_day, $this_year);
			$datetime = nuketimes($this_time, false, false, false, 2);
			
			$this_year = intval($datetime[0]);
			$this_month = intval($datetime[1]);
			$this_day = intval($datetime[2]);
				
			$hourly_info = objectToArray(json_decode($row['hourly_info']));
			foreach($hourly_info as $hour => $hour_hits)
			{
				if($this_year == $today_year && $this_month == $today_month && $this_day == $today_day) continue;
				if($hour_hits == 0 && ($mode != "daily" && $first_hourly_data))
				{
					unset($hourly_info[$hour]);
				}
			}
			$first_hourly_data = true;
			
			$visitor_ips = ($row['visitor_ips'] != '') ? objectToArray(json_decode($row['visitor_ips'])):"";
			$this_visitors = array();
			if(isset($visitor_ips) && !empty($visitor_ips))
				foreach($visitor_ips as $visitor_ip_val)
					$statistics['total_visitors'][$this_year][$this_month][$this_day][] = $visitor_ip_val;
			
			$statistics['total_hits'] += $hits;
			$statistics['years'][$this_year] = (isset($statistics['years'][$this_year])) ? ($statistics['years'][$this_year]+$hits):$hits;
			$statistics['months'][$this_year][$this_month] = (isset($statistics['months'][$this_year][$this_month])) ? ($statistics['months'][$this_year][$this_month]+$hits):$hits;
			$statistics['days'][$this_year][$this_month][$this_day] = array($hits, $visitors, $hourly_info);		
		}
	}
	
	$statistics['total_visitors']['all_visitors'] = sizeof(array_unique(array_filter(simple_array_flatten($statistics['total_visitors']))));

	$script_array = array();
	$years_chart_data = array();
	$months_chart_data = array();
	$days_chart_data = array();
	
	if($mode == "all")
	{
		foreach($statistics['years'] as $this_year => $hits)
		{
			if($hits == 0) continue;
			
			$this_year_total_visitors = isset($statistics['total_visitors'][$this_year]) ? sizeof(array_unique(array_filter(simple_array_flatten(($statistics['total_visitors'][$this_year]))))):0;
			
			$years_chart_data[] = '
			{
				"c-1": '.$hits.',
				"c-2": '.$this_year_total_visitors.',
				"c-3": 0,
				"date": '.$this_year.',
				"url": "'.LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year").'"
			}';
		}
		
		$years_chart_data = "[".implode(",\n", $years_chart_data)."]";
		$script_array[] = "var years_chart_data = $years_chart_data;";
		$script_array[] = "yearly_chart('years_chart', years_chart_data);";
	}
	
	if($mode == "yearly" || $mode == "all")
	{
		$this_year = ($year != 0) ? $year:$today_year;
		foreach($statistics['months'][$this_year] as $this_month => $hits)
		{
			if($hits == 0) continue;
			$this_month_total_visitors = isset($statistics['total_visitors'][$this_year][$this_month]) ? sizeof(array_unique(array_filter(simple_array_flatten($statistics['total_visitors'][$this_year][$this_month])))):0;
			$months_chart_data[] = '
			{
				"c-1": '.$hits.',
				"c-2": '.$this_month_total_visitors.',
				"date": "'.get_month_name($this_month).'",
				"url": "'.LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year&month=".correct_date_number($this_month)."").'"
			}';
		}
		$months_chart_data = "[".implode(",\n", $months_chart_data)."]";
		$script_array[] = "var months_chart_data = $months_chart_data;";
		$script_array[] = "monthly_chart('months_chart', months_chart_data);";
	}
	
	if($mode == "monthly" || $mode == "yearly" || $mode == "all")
	{
		$this_month = ($month != 0) ? $month:$today_month;
		foreach($statistics['days'][$this_year][$this_month] as $this_day => $day_statistics_data)
		{
			if($hits == 0) continue;
			
			if(isset($statistics['total_visitors'][$this_year][$this_month][$this_day]))
			{
				$this_day_total_visitors = isset($statistics['total_visitors'][$this_year][$this_month][$this_day]) ? sizeof(array_unique(array_filter(simple_array_flatten($statistics['total_visitors'][$this_year][$this_month][$this_day])))):0;
			}
				
			$days_chart_data[] = '
			{
				"c-1": '.$day_statistics_data[0].',
				"c-2": '.((isset($statistics['total_visitors'][$this_year][$this_month][$this_day])) ? $this_day_total_visitors:0).',
				"date": '.$this_day.',
				'.(($this_day == $today_day && $this_month == $today_month && $this_year == $today_year) ? '"color": "#00a6ff",':'').'
				"url": "'.LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year&month=".correct_date_number($this_month)."&day=".correct_date_number($this_day)."").'"
			}';
		}
		$days_chart_data = "[".implode(",\n", $days_chart_data)."]";
		$script_array[] = "var days_chart_data = $days_chart_data;";
		$script_array[] = "daily_chart('days_chart', days_chart_data);";
	}

	if($mode == "monthly" || $mode == "yearly" || $mode == "daily" || $mode == "all")
	{
		$array_keys = 1;
		if(isset($statistics['days'][$this_year][$this_month])){
			$array_keys = array_keys($statistics['days'][$this_year][$this_month]);
			$array_keys = array_shift($array_keys);
		}
	
		$this_day = ($day != 0) ? $day:(($this_year == $today_year && $this_month == $today_month && $this_day == $today_day) ? $today_day:$array_keys);
				
		foreach($statistics['days'][$this_year][$this_month][$this_day][2] as $this_hour => $hits)
		{
			/*if($mode == "daily" && isset($statistics['total_visitors'][$this_year][$this_month][$this_day]))
			{
				$this_day_total_visitors = isset($statistics['total_visitors'][$this_year][$this_month][$this_day]) ? sizeof(array_unique(array_filter(simple_array_flatten($statistics['total_visitors'][$this_year][$this_month][$this_day])))):0;
			}*/
			$hour_chart_data[] = '
			{
				"c-1": '.$hits.',
				'.(($this_year == $today_year && $this_month == $today_month && $this_day == $today_day && $this_hour == date("H")) ? '"customBullet": "'.$nuke_configs['nukecdnurl'].'modules/'.$module_name.'/includes/redstar.png",':'').'
				"date": '.$this_hour.',
			}';
		}
		
		$hour_chart_data = "[".implode(",\n", $hour_chart_data)."]";
		$script_array[] = "var hour_chart_data = $hour_chart_data;";
		$script_array[] = "hourly_chart('hours_chart', hour_chart_data, '".$nuke_configs['nukeurl']."');";
	}
	
	$script_array = (!empty($script_array)) ? "<script>$(document).ready(function(){".implode("\n", $script_array)."\n});</script>":"";
		
	$breadcrumb_data = array();
	if($mode == "all")
		$breadcrumb_data[] = array(_ADVANCED_STATISTICS, LinkToGT("index.php?modname=$module_name&op=advanced_statistics"));
	if($mode == "yearly")
	{
		$breadcrumb_data[] = array($this_year, LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year"));
	}
	if($mode == "monthly")
	{
		$breadcrumb_data[] = array($this_year, LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year"));
		$breadcrumb_data[] = array(get_month_name($this_month), LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year&month=$month"));
	}
	if($mode == "daily")
	{
		$breadcrumb_data[] = array($this_year, LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year"));
		$breadcrumb_data[] = array(get_month_name($this_month), LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year&month=$month"));
		$breadcrumb_data[] = array(_DAY." ".$this_day, LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$this_year&month=$month&day=$this_day"));
	}
	
	if($mode == "all")
	{
		$visits_time_text = ""._TOTALY."";
		$back_link = "<a href=\"".LinkToGT("index.php?modname=$module_name")."\">"._SHOW_TOTAL_STATISTICS."</a>";
	}
	elseif($mode == "yearly")
	{
		$visits_time_text = ""._IN_YEAR." $year";
		$back_link = "<a href=\"".LinkToGT("index.php?modname=$module_name&op=advanced_statistics")."\">"._ADVANCED_STATISTICS."</a>";
	}
	elseif($mode == "monthly")
	{
		$visits_time_text = ""._IN." ".get_month_name($month)." $year";
		$back_link = "<a href=\"".LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$year")."\">"._SHOW_YEAR_STATISTICS." $year</a>";
	}
	elseif($mode == "daily")
	{
		$day_datetime = all_to_gregorian($year, $month, $day);
		$day_unixtime = mktime(0,0,0, $day_datetime[1], $day_datetime[2], $day_datetime[0]);
		$day_datetime = nuketimes($day_unixtime, false, false, false, 3);
		$visits_time_text = ""._IN_DAY." $day_datetime";
		$back_link = "<a href=\"".LinkToGT("index.php?modname=$module_name&op=advanced_statistics&year=$year&month=$month")."\">"._SHOW_STATISTICS." ".get_month_name($month)." $year</a>";
	}
	
	$hooks->add_functions_vars(
		'adv_statistics_assets',
		array(
			"module_name" => $module_name,
			"script_array" => $script_array,
		)
	);

	$hooks->add_filter("site_theme_headers", "adv_statistics_assets", 10);
	
	$meta_tags = array(
		"title" 				=> ""._VIEWERS_STATISTICS." $visits_time_text",
		"description" 			=> _SٍEPRATE_STATISTICS,
		"keywords" 				=> ''._VIEWERS_STATISTICS.', statistics, '._YEARLY_VISITS.', '._MONTHLY_VISITS.', '._DAILY_VISITS.''
	);
	$meta_tags = $hooks->apply_filters("select_statistics_adv_header_meta", $meta_tags, $visits_time_text);
		
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	
	$hooks->add_functions_vars(
		'adv_statistics_breadcrumb',
		array(
			"breadcrumb_data" => $breadcrumb_data,
		)
	);
	
	$hooks->add_filter("site_breadcrumb", "adv_statistics_breadcrumb", 10);
	
	include("header.php");

	if(file_exists("themes/".$nuke_configs['ThemeSel']."/advanced_statistics.php"))
		include("themes/".$nuke_configs['ThemeSel']."/advanced_statistics.php");
	elseif(function_exists("advanced_statistics_html"))
		$contents .= advanced_statistics_html($statistics);
	else 
	{
		$contents .= "
		<div class=\"col-md-12\">
			".OpenTable(_VIEWERS_STATISTICS, 'success')."
			<div class=\"text-center\" dir=\""._DIRECTION."\">
				<div class=\"stats_icon\" dir=\""._DIRECTION."\"></div>
				<div class=\"stats_info\">
					".sprintf(_PAGES_VISITS_STATISTICS, $visits_time_text, $statistics['total_hits'], $statistics['total_visitors']['all_visitors'])."
					<br />
					$back_link
				</div>
			</div>
			".CloseTable()."
		</div>";
		
		if($mode == "all")
		{
			
			$contents .="<div class=\"col-md-12\">
			".OpenTable(""._VIEWERS_STATISTICS." / "._ENTRIES."")."
				<div id=\"years_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			".CloseTable()."
			</div>";
		}
		if($mode == "yearly" || $mode == "all")
		{
			$contents .="<div class=\"col-md-12\">
			".OpenTable(""._VIEWERS_STATISTICS." / "._YEAR_ENTRIES." <b>$this_year</b>")."
				<div id=\"months_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			".CloseTable()."
			</div>";
		}
		if($mode == "yearly" || $mode == "monthly" || $mode == "all")
		{
			$contents .="<div class=\"col-md-12\">
			".OpenTable(""._VIEWERS_STATISTICS." / "._ENTRIES." <b>".get_month_name($this_month)." $this_year</b>")."
				<div id=\"days_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			".CloseTable()."
			</div>";
		}
		if($mode == "yearly" || $mode == "monthly" || $mode == "daily" || $mode == "all")
		{
			$contents .="<div class=\"col-md-12\">
			".OpenTable(""._VIEWERS_STATISTICS." <b>$this_day ".get_month_name($this_month)." $this_year</b>")."
				<div id=\"hours_chart\" style=\"direction:ltr;width: 100%; height: 400px;\"></div>
			".CloseTable()."
			</div>";
		}
	}
	
	$contents = $hooks->apply_filters("advanced_statistics", $contents);
	
	$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	
	include("footer.php");	
}

$op = (isset($op)) ? filter($op, "nohtml"):'';
$year = (isset($year)) ? intval($year):0;
$month = (isset($month)) ? intval($month):0;
$day = (isset($day)) ? intval($day):0;

switch($op)
{

	default:
		statistics();
	break;

	case "advanced_statistics":
		advanced_statistics($year, $month, $day);
	break;
}

?>