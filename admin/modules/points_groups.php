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
	/* Users points_groups Functions                         */
	/*********************************************************/

	function points_groups()
	{
		global $db, $admin_file, $hooks;
		
		$hooks->add_filter("set_page_title", function(){return array("points_groups" => _POINTS_GROUP_ADMIN);});
		
		$contents = '';
		$contents .= GraphicAdmin();
		
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>"._ADD_NEW_POINT_GROUP."</b></font></div><br><br>
		<form action=\"".$admin_file.".php\" method=\"post\">
			<table border=\"0\" width=\"100%\" class=\"id-form\">
				<tr>
					<th>"._GTITLE.":</th>
					<td><input class=\"inp-form\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"255\"></td>
				</tr>
				<tr>
					<th>"._DESCRIPTIONS.":</th>
					<td><textarea class=\"form-textarea\" name=\"description\" cols=\"50\" rows=\"5\"></textarea></td>
				</tr>
				<tr>
					<th>"._POINT_VALUE.":</th>
					<td><input class=\"inp-form\" type=\"text\" name=\"points\" size=\"10\" maxlength=\"20\" value=\"0\">&nbsp;<i>("._ONLYNUMVAL.")</i></td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"group_add\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			<input type=\"submit\" value=\""._CREATEGROUP."\" class=\"form-submit\">
		</form>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _POINTSSYSTEM . "</b></font></div><br><br>
		<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"product-table\">
			<tr>
				<th class=\"table-header-repeat line-left\">"._CODE."</th>
				<th class=\"table-header-repeat line-left\">" . _NAME . "</th>
				<th class=\"table-header-repeat line-left\">" . _DESCRIPTIONS . "</th>
				<th class=\"table-header-repeat line-left\">" . _POINTS . "</th>
				<th class=\"table-header-repeat\" style=\"width:180px\">" . _OPERATION . "</th>
			</tr>";
			
			$result = $db->table(POINTS_GROUPS_TABLE)
						->order_by(["id" => "ASC"])
						->select();
						
			if($db->count() > 0)
			{
				foreach($result as $row){
					$id = $row['id'];
					$contents .= "<form action=\"".$admin_file.".php\" method=\"post\">
					<tr>
						<td align=\"center\">&nbsp;".$id."&nbsp;</td>
						<td>&nbsp;".$row['title']."&nbsp;</td>
						<td>".$row['description']."</td>
						<td align=\"center\">&nbsp;<input type=\"text\" value=\"".$row['points']."\" size=\"5\" class=\"inp-form\" style=\"text-align:center;\" name=\"points\">&nbsp;</td>
						<td align=\"center\" class=\"id-form\"><font class=\"content\">&nbsp;
						<input type=\"hidden\" name=\"id\" value=\"$id\">
						<input type=\"hidden\" name=\"op\" value=\"points_update\">
						<input type=\"submit\" value=\""._UPDATE."\" class=\"form-submit\">&nbsp;</font>
						".(($row['type'] == 0) ? "<a href=\"".$admin_file.".php?op=group_edit&id=$id\" class=\"table-icon icon-1 info-tooltip\" title=\""._EDIT."\"></a><a href=\"".$admin_file.".php?op=group_del&id=$id&csrf_token="._PN_CSRF_TOKEN."\" class=\"table-icon icon-2 info-tooltip\" title=\""._DELETE."\" onclick=\"return confirm('"._POINT_GROUP_DELETE_SURE."');\"></a>":"")."
						</td>
					</tr>
					<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
					</form>";
				}
			}
		$contents .= "</table>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function group_add($title, $description, $points)
	{
		global $db, $admin_file;
		if (!is_numeric($points) || @stristr($points, "-"))
		{
			$contents = '';
			$contents .= GraphicAdmin();
			$contents .= title(_POINTS_GROUP_ADMIN);
			$contents .= OpenAdminTable();
			$contents .= "<div class=\"text-center\"><b>" . _GROUPADDERROR . "</b><br><br>"
			."" . _NONUMVALUE . "<br><br>"
			."" . _GOBACK . "</div>";
			$contents .= CloseAdminTable();
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
		else
		{
			$query = $db->query("SHOW TABLE STATUS WHERE name='".POINTS_GROUPS_TABLE."'"); 
			$row = $query->results()[0];
			$next_inc_value = (!isset($row["AUTO_INCREMENT"])) ? $row["Auto_increment"]:$row["AUTO_INCREMENT"];
	
			$title = filter($title, 'nohtml');
			$points = intval($points);
			$db->table(POINTS_GROUPS_TABLE)
				->insert([
					"id" => $next_inc_value,
					"title" => $title,
					"description" => $description,
					"points" => $points
				]);
			
			cache_system("nuke_points_groups");
			add_log(sprintf(_ADD_POINT_GROUP_LOG, $title), 1);
			Header("Location: ".$admin_file.".php?op=points_groups");
		}
	}

	function group_edit($id)
	{
		global $db, $admin_file, $hooks;
		
		$id = intval($id);
		$contents = '';
		
		$row = $db->table(POINTS_GROUPS_TABLE)->find("$id");
		
		if($id < 23 || (isset($row['type']) && $row['type'] == 1))
		{
			header("location: ".$admin_file.".php?op=points_groups");
			die();
		}
		$title = filter($row['title'], "nohtml");
		$description = filter($row['description']);
		$points = intval($row['points']);

		$hooks->add_filter("set_page_title", function() use($title){return array("group_edit" => _POINTS_GROUP_ADMIN." - ".$title);});
		
		$contents .= GraphicAdmin();

		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>".sprintf(_EDIT_POINT_GROUP, $title)."</b></font></div><br><br>
		<form action=\"".$admin_file.".php\" method=\"post\">
			<table border=\"0\" width=\"100%\" class=\"id-form\">
				<tr>
					<th>"._GTITLE.":</th>
					<td><input class=\"inp-form\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"255\" value=\"$title\"></td>
				</tr>
				<tr>
					<th>"._DESCRIPTION.":</th>
					<td><textarea class=\"form-textarea\" name=\"description\" cols=\"70\" rows=\"15\">$description</textarea></td>
				</tr>
				<tr>
					<th>"._POINT_VALUE.":</th>
					<td><input class=\"inp-form\" type=\"text\" name=\"points\" size=\"10\" maxlength=\"20\" value=\"$points\">&nbsp;<i>("._ONLYNUMVAL.")</i></td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"group_edit_save\">
			<input type=\"hidden\" name=\"id\" value=\"$id\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			<input type=\"submit\" value=\""._SAVE."\" class=\"form-submit\">
		</form>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function group_edit_save($id, $title, $description, $points)
	{
		global $db, $admin_file;
		$id = intval($id);
		
		$row = $db->table(POINTS_GROUPS_TABLE).find("$id");
		
		if($id < 23 || (isset($row['type']) && $row['type'] == 1))
		{
			header("location: ".$admin_file.".php?op=points_groups");
			die();
		}	
		if (!is_numeric($points)) {
			$contents = '';
			$contents .= GraphicAdmin();
			$contents .= title(_POINTS_GROUP_ADMIN);
			$contents .= OpenAdminTable();
			$contents .= "<div class=\"text-center\"><b>" . _GROUPADDERROR . "</b><br><br>"
			."" . _NONUMVALUE . "<br><br>"
			."" . _GOBACK . "</div>";
			$contents .= CloseAdminTable();
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		} else {
			$id = intval($id);
			$title = filter($title, 'nohtml');
			$points = intval($points);
			
			$db->table(POINTS_GROUPS_TABLE)
				->where("id", "$id")
				->update([
					"title" => $title,
					"description" => $description,
					"points" => $points,
				]);
			
			cache_system("nuke_points_groups");
			add_log(sprintf(_EDIT_POINT_GROUP, $title), 1);
			Header("Location: ".$admin_file.".php?op=points_groups");
		}
	}

	function group_del($id)
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file;
		$id = intval($id);
		
		$row = $db->table(POINTS_GROUPS_TABLE)->find("$id");
		if(!empty($row))
		{
			if($id < 23 || $row['type'] == 1)
			{
				header("location: ".$admin_file.".php?op=points_groups");
				die();
			}
		
			$points_group = $db->table(POINTS_GROUPS_TABLE)
				->where("id", $id)
				->first(['title']);
			
			$result = $db->table(POINTS_GROUPS_TABLE)
						->where("id", $id)
						->delete();
			
			phpnuke_auto_increment(POINTS_GROUPS_TABLE);

			cache_system("nuke_points_groups");
			add_log(sprintf(_POINT_GROUP_DEL_LOG, $points_group['title']), 1);
		}
		Header("Location: ".$admin_file.".php?op=points_groups");
	}

	function points_update($points, $id)
	{
		global $db, $admin_file;
		$id = intval($id);
		$points = intval($points);
		
		$points_group = $db->table(POINTS_GROUPS_TABLE)
			->where("id", $id)
			->first(['title']);
			
		$db->table(POINTS_GROUPS_TABLE)
			->where("id", $id)
			->update([
				"points" => $points
			]);
		
		cache_system("nuke_points_groups");
		add_log(sprintf(_EDIT_POINT_GROUP_LOG, $points_group['title']), 1);
		Header("Location: ".$admin_file.".php?op=points_groups");
	}
	
	$id = (isset($id)) ? intval($id):0;
	$points = (isset($points)) ? intval($points):0;
	$box_id = (isset($box_id)) ? filter($box_id, "nohtml"):'';
	$title = (isset($title)) ? filter($title, "nohtml"):'';
	$description = (isset($description)) ? addslashes($description):'';
	$op = (isset($op)) ? filter($op, "nohtml"):'points_groups';
	
	switch($op)
	{

		case "points_groups":
		points_groups();
		break;

		case "group_add":
		group_add($title, $description, $points);
		break;

		case "group_edit":
		group_edit($id);
		break;

		case "group_edit_save":
		group_edit_save($id, $title, $description, $points);
		break;

		case "group_del":
		group_del($id);
		break;

		case "points_update":
		points_update($points, $id);
		break;

	}

}
else
{
	header("location: ".$admin_file.".php");
}

?>