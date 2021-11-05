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
 
if ( !defined('ADMIN_FILE') )
{
	die("Illegal File Access");
}

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	$main_caches = array(
		"nuke_configs" => _PREFERENCES,
		"nuke_admins_menu" => _ADMINS_PERMISSIONS,
		"nuke_authors" => _ADMINS_DATA,
		"nuke_blocks" => ""._BLOCKS."",
		"nuke_categories" => _CATEGORIES,
		"nuke_nav_menus" => _NAV_MENUS,
		"nuke_bookmarksite" => _BOOKMARKS,
		"nuke_points_groups" => _POINTS_GROUPS,
		"nuke_headlines" => _HEADLINES,
		"nuke_modules" => _MODULES,
		"nuke_surveys" => _SURVEYS,
		"nuke_modules_friendly_urls" => _GTLINKS,
		"nuke_languages" => _LANGUAGE,
		"nuke_mtsn_ipban" => _BANED_IPS,
		"nuke_users_system" => _USERS_SYSTEM_DATA,
	);
	
	function cache()
	{
		global $admin, $db, $hooks, $admin_file, $cache_systems, $main_caches, $users_system;
		
		$hooks->add_filter("set_page_title", function(){return array("cache" => _CACHEADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= "<br>";
		$contents .= OpenAdminTable();

		if(is_array($cache_systems) && !empty($cache_systems))
		{
			foreach($cache_systems as $cache_name => $cache_system)
			{
				if($cache_system['table'] != "")
				{
					if(defined($cache_system['name']))
						$cache_system['name'] = constant($cache_system['name']);
					$main_caches[$cache_name] = $cache_system['name'];
				}
			}
		}
		
		$contents .= "
		<div class=\"text-center\"><b>"._UPDATE_CACHES."</b></div><br /><br />
		<form action=\"".$admin_file.".php\" method=\"post\">
		<table width=\"100%\" class=\"id-form\">";
			$i = 1;
			
			foreach($main_caches as $main_cache_key => $main_cache_val)
			{
				if($i == 1) $contents .= "<tr>";
				$contents .= "<td><input type=\"checkbox\" class=\"styled\" name=\"main_caches_inputs[]\" value=\"$main_cache_key\" data-label=\"$main_cache_val\" /> &nbsp;&nbsp; </td>";
				if($i == 5)
				{
					$contents .= "</tr>";
					$i = 0;
				}
				$i++;
			}
		$contents .= "</table>
		<br /><br /><div align=\"center\"><a href=\"".$admin_file.".php?op=FlushCache&csrf_token="._PN_CSRF_TOKEN."\"><b>"._FLUSHALLCACHES."</b></a></div>
		<input type=\"hidden\" name=\"op\" value=\"updatecache\">
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<br /><br /><input type=\"submit\" class=\"form-submit\" value=\""._SAVECHANGES."\">	
		</form>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function FlushCache()
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file, $cache_systems, $users_system, $cache, $main_caches;

		$cache->flush_caches();
		define("IN_FLUSH", true);
		$main_caches = array_keys($main_caches);
		
		if(is_array($cache_systems) && !empty($cache_systems))
		{
			foreach($cache_systems as $cache_name => $cache_system)
			{
				if($cache_system['table'] != "")
				{
					$main_caches[] = $cache_name;
				}
			}
		}
		
		foreach($main_caches as $main_caches_input)
		{
			cache_system($main_caches_input);
		}
		
		$extra_cache_codes = $users_system->cache_system();

		if(!empty($extra_cache_codes))
			cache_system('all',$extra_cache_codes);
		
		add_log(_FLUSHALLCACHESLOG, 1);
		
		Header("Location: ".$admin_file.".php?op=cache");
	}

	function updatecache($main_caches_inputs)
	{
		global $db, $admin_file, $users_system;
		
		if(isset($main_caches_inputs) && !empty($main_caches_inputs))
		{
			foreach($main_caches_inputs as $main_caches_input)
			{
				if($main_caches_input != 'nuke_users_system')
					cache_system($main_caches_input);
			}
		}

		if(in_array("nuke_users_system", $main_caches_inputs))
		{
			$extra_cache_codes = $users_system->cache_system();
			
			if(!empty($extra_cache_codes))
				cache_system('all',$extra_cache_codes);
		}
		
		add_log(sprintf(_FLUSHCOSTUMCACHESLOG, implode(", ", $main_caches_inputs)), 1);
		Header("Location: ".$admin_file.".php?op=cache");
	}

	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$main_caches_inputs = (isset($main_caches_inputs)) ? $main_caches_inputs:array();
	
	switch ($op)
	{
		case "cache":
		cache();
		break;

		case "FlushCache":
		FlushCache();
		break;

		case "updatecache":
		updatecache($main_caches_inputs);
		break;

		case "cachemenufooter":
		cachemenufooter();
		break;
	}
}
else
{
    header("location: ".$admin_file.".php");
}
?>