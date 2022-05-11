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

/********************************************************/
/* Site Admin Backup & Optimize Module for PHP-Nuke     */
/* Version 1.0.0         10-24-04                       */
/* By: Telli (telli@codezwiz.com)                       */
/* http://codezwiz.com/                                 */
/* Copyright © 2000-2004 by Codezwiz                    */
/********************************************************/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

global $aid, $database_superadmins;

//if (check_admin_permission($filename) && in_array($aid, $database_superadmins)) {
if (check_admin_permission($filename)) {

	require_once 'admin/modules/modules/mysql_backup.php';

	global $db, $pn_dbname;
	
	$DB_obj = new BackupMySQL();
	$DB_obj->database = $pn_dbname;

	function Showdbcc()
	{
		$contents = '';
		$contents .= OpenAdminTable();
		$contents .="<br /><div align=\"center\"><b>"._DATABASEMANAGE."</b></div><br />";
		$contents .=CloseAdminTable();
		$contents .= "<br />";
		return $contents;
	}

	function backup_tables($tables = '*', $comments='')
	{
		global $db, $aid, $DB_obj;

		if($tables != "*" && is_array($tables))
		{
			$DB_obj->tables = $tables;
		}
		$file_name="cache/backup_details.csv";
		
		$max_rows=50000 ;
		$large_tables=array("");
		$folder_name=trim("cache/");
		
		$path=$folder_name;	
				
		//Add DROP TABLE IF EXISTS queries before CREATE TABLE in backup file.
		$DB_obj->drop_tables = true;
		
		//Only structure of the tables will be backed up if true.
		$DB_obj->struct_only = false;

		//Include comments in backup file if true.
		$DB_obj->comments = (isset($comments)) ? true:false;
		
		$filename = $folder_name.date('d-m-Y').'_'.$DB_obj->database.'.sql';
		
		$table_details=$DB_obj->GetTables();

		$fp = fopen($file_name, 'w');
		
		for($count=0;$count<count($table_details);$count++)
		{					
			fwrite($fp, $table_details[$count].":0\r\n");				
		}
		fclose($fp);
		
		$fp = fopen($filename, 'w');
		fclose($fp);
		
		@unlink($filename."gz");
		
		$file_contents=file($file_name);
		$total_rows=0;
		$table_count=count($file_contents);
		
		if($DB_obj->comments)
			$DB_obj->first_Execute($filename);
		
		for($count=0;$count<$table_count;$count++)
		{
			list($table_name,$row_count,$start)=explode(':',str_replace("\r","",str_replace("\n","",$file_contents[$count])));
			
			if(in_array($table_name,$large_tables))
				$max_rows=10000;
			else
				$max_rows=50000;
			
			if($start < $row_count || $row_count==0)
			{
				if(($start+$max_rows) > $row_count)
					$end=($row_count-$start);
				else
					$end=$max_rows;
				
				$str = $table_name.":".$row_count.":".($start+$end)."\r\n";
				$file_contents[$count]=$str;
				
				if (!$DB_obj->Execute($filename,$table_name,$start,$end,$row_count))
				{
					$output = $DB_obj->error;
					echo "Error backing up table ".$table_name.". Details : ".$output;
				}
				else
				{
					$total_rows+=$end;
					//if($end!=0)echo "Rows ".$start."-".($start+$end-1)." of table ".$table_name." were successfully backed up.<br/>";
					//else echo "Rows 0-0 of table ".$table_name." were successfully backed up.<br/>";
					if($total_rows>=$max_rows)
						echo '<br/>';
					
					$fp = fopen($file_name, 'w');
					for($counter=0;$counter<count($file_contents);$counter++)
					{		
						fwrite($fp, $file_contents[$counter]);
						if($total_rows>=$max_rows)
							echo $file_contents[$counter].'<br/>';
					}
					fclose($fp);																
				}
				
				if($total_rows>=$max_rows)
					break;
			}		
		}
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		if (gzcompressfile($filename,1)==false)
		{
			$contents .= "<div class=\"text-center\"><font class=\"title\"><b>"._BACKUPCREATIONERROR."</b></font></div>";
		}
		else{
			$contents .= "<meta http-equiv=\"refresh\" content=\"1; url=$filename.gz\"><div class=\"text-center\"><font class=\"title\"><b>".sprintf(_BACKUPCREATIONOK, "$filename.gz")."</b></font><br /><br />"._GOBACK."</div>";
			@unlink($filename);
			$db->query("SET NAMES 'latin1'");
			add_log(_BACKUPCREATION, 1);
		}
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
		
	}
	
	function gzcompressfile($source,$level=false)
	{
		$dest = $source.'.gz';
		$mode='wb'.$level;
		$error=false;
		if($fp_out=gzopen($dest,$mode))
		{
			if($fp_in=fopen($source,'rb'))
			{
				while(!feof($fp_in))gzwrite($fp_out,fread($fp_in,4096));
				fclose($fp_in);
			}
			else $error=true;
			gzclose($fp_out);
		}
		else
			$error=true;
		
		if($error)
			return false;
		else
			return $dest;
	}
	
	function database()
	{
		global $db, $hooks, $admin_file, $DB_obj, $nuke_configs, $visitor_ip;
		
		$hooks->add_filter("set_page_title", function(){return array("database" => _DATABASEMANAGE);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"title\"><b>"._DATABASEMANAGE."</b></font></div>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		$contents .= OpenAdminTable();
		
		$contents .= "
		<style>
		.database .ui-tabs-anchor{
			color:#1c94c4;
		}
		.database .ui-tabs-anchor:hover{
			color:#c77405;
		}
		.other_db_info{
			display:none;
		}
		.ui-dialog, .ui-dialog>a, .ui-dialog>span, .ui-dialog>div{
			font-size:12px;
		}
		</style>
		<form method=\"post\" name=\"backup\" action=\"".$admin_file.".php\">";
		$contents .= "<table width=\"100%\" class=\"id-form product-table no-border database\"><tr>";
		$contents .= "<td colspan=\"3\">
		<link href=\"includes/Ajax/jquery/multi-select.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\">
		<select name=\"tables[]\" size=\"20\" multiple class=\"multiple-select\" style=\"display:none;\">";
		
		$table_details=$DB_obj->GetTables();
		foreach($table_details as $key => $val)
		{
			$val = explode(":", $val);
			$contents .= "<option value=\"$val[0]\" style=\"direction:ltr;text-align:left;\">$val[0]</option>";
		}
		$contents .= "</select>
		<script src=\"includes/Ajax/jquery/jquery.multi-select.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/jquery.quicksearch.js\" type=\"text/javascript\"></script>
		<script>
			$(document).ready(function(){
				$('.multiple-select').multiSelect({
					selectableHeader: \"<div class='custom-header'><a href='#' id='select-all'><b>"._CHECKALL."</b></a> &nbsp; <input type='text' class='search-input' placeholder='search ...' autocomplete='off' dir='ltr'></div>\",
					selectionHeader: \"<div class='custom-header'><a href='#' id='deselect-all'><b>"._UNCHECKALL."</b></a> &nbsp; <input type='text' class='search-input' autocomplete='off' placeholder='search ...' dir='ltr'></div>\",
					afterInit: function(ms){
						var that = this,
						$"."selectableSearch = that.$"."selectableUl.prev().find('input'),
						$"."selectionSearch = that.$"."selectionUl.prev().find('input'),
						selectableSearchString = '#'+that.$"."container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
						selectionSearchString = '#'+that.$"."container.attr('id')+' .ms-elem-selection.ms-selected';

						that.qs1 = $"."selectableSearch.quicksearch(selectableSearchString)
						.on('keydown', function(e){
							if (e.which === 40){
								that.$"."selectableUl.focus();
								return false;
							}
						});

						that.qs2 = $"."selectionSearch.quicksearch(selectionSearchString)
						.on('keydown', function(e){
							if (e.which == 40){
								that.$"."selectionUl.focus();
								return false;
							}
						});
					},
					afterSelect: function(){
						this.qs1.cache();
						this.qs2.cache();
					},
					afterDeselect: function(){
						this.qs1.cache();
						this.qs2.cache();
					}
				});
				$('#select-all').click(function(){
					$('.multiple-select').multiSelect('select_all');
					return false;
				});
				$('#deselect-all').click(function(){
					$('.multiple-select').multiSelect('deselect_all');
					return false;
				});
			});
		</script>	
		</td></tr>
		<tr>
			<td width=\"250\">
				<select name=\"op\" class=\"styledselect-select\">
					<option value=\"BackupDB\">"._DB_BACKUP_CREATE."</option>
					<option value=\"OptimizeDB\">"._DB_OPTIMAIZE."</option>
					<option value=\"CheckDB\">"._DB_CHECK."</option>
					<option value=\"AnalyzeDB\">"._DB_ANALYZE."</option>
					<option value=\"RepairDB\">"._DB_REPAIR."</option>
				</select>
			</td>
			<td width=\"300\">
				<label for=\"backup_comments\">"._INCLUDEDROPSTATEMENT." "._FORBACKUPONLY."</label>
				<input type=\"checkbox\" value=\"1\" id=\"backup_comments\" name=\"comments\" checked class=\"styled\">
			</td>
			<td>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				<input type=\"submit\" class=\"form-submit\" value=\""._OK."\">
				</form>
			</td>
		</tr>
		<tr>
			<td colspan=\"3\">
				<div id=\"container\">
					<ul>
						<li><a href=\"#webhost_info\">"._SERVER_INFO."</a></li>
					</ul>
					<div id=\"webhost_info\">
						"._SERVER_INFO_DESC."<br /><br />
						<table width=\"100%\" class=\"id-form\">
							<tr>
								<th style=\"width:200px\">"._SERVER_IP."</th>
								<td><span dir=\"ltr\">".$_SERVER['SERVER_ADDR']."</span></td>
								<th style=\"width:200px\">"._YOUR_IP."</th>
								<td><span dir=\"ltr\">".$visitor_ip."</span></td>
							</tr>
							<tr>
								<th>"._PHP_VERSION."</th>
								<td><span dir=\"ltr\">".$nuke_configs['phpver']."</span></td>
								<th>"._MYSQL_VERSION."</th>
								<td>
									<span dir=\"ltr\">";
									$result = $db->query("SELECT @@version as mysql_version");
									$row = $result->results()[0];
									$mysql_version = $row['mysql_version'];
									$contents .="$mysql_version</span>
								</td>
							</tr>
							<tr>
								<th>"._NUKE_VERSION."</th>
								<td><span dir=\"ltr\">".$nuke_configs['Version_Num']."</span></td>
								<th>"._INSTALLED_NUKE_PATH."</th>
								<td><span dir=\"ltr\">".PHPNUKE_ROOT_PATH."</span></td>
							</tr>
						</table>
					</div>
				</div>
				<script>
				$(document).ready(function(){
					$('#container').tabs({hide: { effect: 'fade', duration: 300 }});
				});
				</script>
			</td>
		</tr>
		</table>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function operation($mode, $tables)
	{
		global $db, $hooks, $aid, $DB_obj, $admin_file;
		$type = strtoupper(substr($mode,0,-2));
		
		$hooks->add_filter("set_page_title", function() use($type){return array("operation" => _DATABASEMANAGE." - "._OPERATION." ".$type);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= Showdbcc();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"title\"><b>$type "._TABLES."</b></font></div>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		$contents .= OpenAdminTable();
		$DB_obj->tables = $tables;
		
		if (!empty($DB_obj->tables))
		{
			if ($type == "STATUS")
			{
				$query = 'SHOW TABLE STATUS FROM '.$DB_obj->database;
			}
			else
			{
				$query = "$type TABLE ".implode(", ", $DB_obj->tables);
			}

			$result = $db->query($query);
			
			$columnNames = $result->columnNames();
			$contents .= "
			<table width=\"100%\" class=\"product-table\">
				<tr>
					<th class=\"table-header-repeat line-left\">"._ROW."</th>";
					foreach($columnNames as $columnKey => $columnvalue)
					{
						$contents .= "<th class=\"table-header-repeat line-left\">".$columnvalue."</th>";
					}
					$contents .= "
				</tr>";
			$o = 0;
			$c = 1;
			
			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					if($o==0){
						$trclass = " class=\"alternate-row\"";
						$o++;
					}else{
						$trclass = "";
						$o = 0;			
					}
					$contents .= "<tr$trclass>";
					$contents .= "<td align=\"center\">$c</td>";
					foreach($row as $value)
					{
						$contents .= "<td align=\"center\" dir=ltr>".$value."</td>";
					}
					$contents .= "</tr>";
					$c++;
				}
			}
			$contents .= "</table>";
			
			add_log(sprintf(_DB_OPERATION_LOG, $type), 1);
		}
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$comments = (isset($comments)) ? filter($comments, "nohtml"):'';
	$tables = (isset($tables)) ? $tables:'*';
	
	switch($op)
	{
		default:   
		case "database":
			database();
		break;
		
		case "BackupDB":
			backup_tables($tables, $comments);
		break;
		
		case "OptimizeDB":
		case "CheckDB":
		case "AnalyzeDB":
		case "RepairDB":
		case "StatusDB":
			operation($op, $tables);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}

?>