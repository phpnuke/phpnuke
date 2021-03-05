<?php

if(!defined('NUKE_FILE'))
{
	exit;
}

define('MSB_VERSION', '1.0.0');

define('MSB_NL', "\r\n");

define ('IPORTERVERSION','0.35b');
define ('DATA_CHUNK_LENGTH',16384);  // How many chars are read per time
@ini_set('auto_detect_line_endings', true);

@set_time_limit(0);

class BackupMySQL
{
	var $database				= '';
	var $tables					= array();
	var $drop_tables			= true;
	var $struct_only			= false;
	var $comments				= true;
	var $backup_dir				= '';
	var $fname_format			= 'd_m_y__H_i_s';
	var $error					= '';
	var $db						= '';

	var $mode					= 1;
	var $comment				= array(
		'#',					// Standard comment lines are dropped by default
		'--',
		
		//'---',					// Uncomment this line if using proprietary dump created by outdated mysqldump
		//'CREATE DATABASE',		// Uncomment this line if your dump contains create database queries in order to ignore them
		'DELIMITER',			// Ignore DELIMITER switch as it's not a valid SQL statement
		'/*!',					// Or add your own string to leave out other proprietary things
	);
	var $pre_query				= array(
		'SET @saved_cs_client = @@character_set_client',
		'SET character_set_client = utf8',
	); //SQL queries to be executed at the beginning of each import session
	var $delimiter				= ';';
	var $string_quotes			= '\''; // String quotes character
	var $max_query_lines		= 300; // How many lines may be considered to be one query (except text lines)
	var $db_connection_charset	= 'utf8';
	var $filename				= ''; // Specify the dump filename to suppress the file selection dialog
	var $linespersession		= 3000; // Lines to be executed per one import session
	var $delaypersession		= 0; // You can specify a sleep time in milliseconds after each session
	
	function __construct()
	{
		return true;
	}
	
	function first_Execute($file_name)
	{
		global $db;
		$mysql_version = $db->query('select version()');
		
		if ($this->comments)
		{
			$sql = '#' . MSB_NL;
			$sql .= '# MySQL database dump' . MSB_NL;
			$sql .= '# Created by Database Backup class, ver. ' . MSB_VERSION . MSB_NL;
			$sql .= '#' . MSB_NL;
			//$sql .= '# Host: ' . $this->server . MSB_NL;
			$sql .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSB_NL;
			$sql .= '# MySQL version: ' . $mysql_version[0]['version()'] . MSB_NL;
			$sql .= '# PHP version: ' . phpversion() . MSB_NL;
			if (!empty($this->database))
			{
				$sql .= '#' . MSB_NL;
				$sql .= '# Database: `' . $this->database . '`' . MSB_NL;
			}
			$sql .= '#' . MSB_NL . MSB_NL . MSB_NL;
			return $this->_SaveToFile($file_name, $sql);
		}
	}

	function Execute($file_name,$table_name,$start,$end,$max_rows)
	{
		if (!($sql = $this->_Retrieve($table_name,$start,$end,$max_rows)))return false;    
		return $this->_SaveToFile($file_name, $sql);
	}

	function _GetTables()
	{
		global $db, $pn_dbname;
		$value = array();
		if (!($result = $db->query("SELECT table_name, table_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$pn_dbname';")))
		{
			return false;
		}
		
		if($db->count() > 0)
		{
			foreach ($result as $row)
			{
				$table_name = $row['table_name'];
				$table_rows = $row['table_rows'];
				if(!empty($this->tables) && !in_array($table_name, $this->tables)) continue;				
				$value[] = $table_name.':'.$table_rows;
			}
		}
		
		if (!sizeof($value))
		{
			$this->error = 'No tables found in database.';
			return false;
		}
		return $value;
	}

	function GetTables()
	{
		return $this->_GetTables();
	}

	function _DumpTable($table,$start,$end,$row_count)
	{
		global $db;
		$value = '';
		
		if($start==0)
		{
			if ($this->comments)
			{
				$value .= '#' . MSB_NL;
				$value .= '# Table structure for table `'.$table. '`'. MSB_NL;
				$value .= '#' . MSB_NL . MSB_NL;
			}
			if ($this->drop_tables)
			{
				$value .= 'DROP TABLE IF EXISTS `'.$table. '`;'. MSB_NL;
			}
			if (!($result = $db->query('SHOW CREATE TABLE `'.$table.'`')))
			{
				return false;
			}
			$row = $result->results()[0];
			$value .= str_replace("\n", MSB_NL, $row['Create Table']) . ';';
			$value .= MSB_NL . MSB_NL;				
		}
		
		if (!$this->struct_only)
		{
			if ($start==0 && $this->comments)
			{
				$value .= '#' . MSB_NL;
				$value .= '# Dumping data for table `' . $table . '`' . MSB_NL;
				$value .= '#' . MSB_NL . MSB_NL;
			}
			$value .= $this->_GetInserts($table,$start,$end);
		}
		
		if($end>=$row_count)$value .= MSB_NL . MSB_NL;
		
		return $value;
	}	

	function _GetInserts($table,$start,$end)
	{
		global $db;
		$value = '';
		$db->query("SET NAMES 'utf8'");
		if (!($result = $db->query('SELECT * FROM `'.$table.'` LIMIT '.$start.','.$end)))
		{
			return false;
		}
		
		if($db->count() > 0)
		{
			foreach ($result as $row)
			{
				$values = '';
				foreach ($row as $data)
				{
					$values .= '\'' . addslashes($data) . '\', ';
				}
				$values = substr($values, 0, -2);
				$value .= 'INSERT INTO `'.$table . '` VALUES (' . $values . ');' . MSB_NL;
			}
		}
		return $value;
	}

	function _Retrieve($table_name,$start,$end,$row_count)
	{
		global $db;
		
		$value = '';
		
		if (!($table_dump = $this->_DumpTable($table_name,$start,$end,$row_count)))
		{
			$this->error = $db->getErrors('last')['message'];
			return false;
		}
		$value .= $table_dump;
		
		return $value;
	}

	function _SaveToFile($fname, $sql)
	{
		if (!($f = fopen($fname, 'a')))
		{
			$this->error = 'Can\'t create the output file.';
			return false;
		}
		fwrite($f, $sql);
		fclose($f);
		return true;
	}

	function Operations($op)
	{
		return $this->_GetTables();
	}

	function read_sql_url($filename, $start, $foffset, $delimiter, $totalqueries, $new_prefix = '')
	{
		$filename_arr = explode("/", $filename);
		
		$is_sql_code = false;
		
		if($filename_arr[0] == 'cache')
			$is_sql_code = true;
			
		if ($this->db_connection_charset !== '')
		{
			$this->db->query("set character_set_server='".$this->db_connection_charset."'");
			$this->db->query("SET NAMES '".$this->db_connection_charset."'");
		}
		
		//run pre-query
		if (isset ($this->pre_query) && sizeof ($this->pre_query)>0)
		{
			reset($this->pre_query);
			foreach ($this->pre_query as $pre_query_value)
			{
				if (!$this->db->query($pre_query_value))
				//if (!mysql_query($pre_query_value, $dbconnection))
				{
					$error = array(
						"status" => "error",
						"message" => array(
							"line" => trim(nl2br(htmlentities($pre_query_value))),
							"error_exp" => $this->db->getErrors('last')['message']
						)
					);
					return (json_encode($error));
				}
			}
		}
	
		$curfilename=$filename;	//Set current filename 

		//Recognize GZip filename
		if (preg_match("/\.gz$/i",$curfilename)) 
			$gzipmode=true;
		else
			$gzipmode=false;

		if ((!$gzipmode && !$file=@fopen($curfilename,"r")) || ($gzipmode && !$file=@gzopen($curfilename,"r")))
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "فايل ".$curfilename." باز نميشود"
				)
			);
			return(json_encode($error));
		}
		// Get the file size (can't do it fast on gzipped files, no idea how)
		else if ((!$gzipmode && @fseek($file, 0, SEEK_END)==0) || ($gzipmode && @gzseek($file, 0)==0))
		{
			if (!$gzipmode)
				$filesize = ftell($file);
			else
				$filesize = gztell($file);
		}
		else
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "نميتوان فايل $curfilename را خواند"
				)
			);
			return(json_encode($error));
		}
		
		if($gzipmode && $filesize == '')
		{
			$gz_data_info = read_media_metadata($filename, true);
			$gz_info = $gz_data_info['gzip']['files'];
			foreach($gz_info as $gz_name => $gz_size)
			{
				$filesize = $filesize+$gz_size;
			}
		}

		// Check start and foffset are numeric values
		if (!is_numeric($start) || !is_numeric($foffset))
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "مقدار شروع بايد از نوع عددي باشد"
				)
			);
			return(json_encode($error));
		}
		else
		{
			$start   = floor($start);
			$foffset = floor($foffset);
		}

		// Set the current delimiter if defined
		if (isset($delimiter))
			$delimiter = $delimiter;
		else
			$delimiter = $this->delimiter;
		
		// Check $foffset upon $filesize (can't do it on gzipped files)
		if ((!$gzipmode || $filesize > 0) && $foffset > $filesize)
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "مقدار شروع بيشتر از مقدار پاياني فايل است"
				)
			);
			return(json_encode($error));
		}

		// Set file pointer to $foffset
		if ((!$gzipmode && fseek($file, $foffset)!=0) || ($gzipmode && gzseek($file, $foffset)!=0))
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "UNEXPECTED: Can't set file pointer to offset: ".$foffset.""
				)
			);
			return(json_encode($error));
		}

		// Start processing queries from $file
		$query="";
		$queries=0;
		$linenumber=$start;
		$querylines=0;
		$inparents=false;

		// Stay processing as long as the $this->linespersession is not reached or the query is still incomplete
		while($linenumber < $start+$this->linespersession || $query != "")
		{
			// Read the whole next line
			$dumpline = "";
			while (!feof($file) && substr ($dumpline, -1) != "\n" && substr ($dumpline, -1) != "\r")
			{
				if (!$gzipmode)
					$dumpline .= fgets($file, DATA_CHUNK_LENGTH);
				else
					$dumpline .= gzgets($file, DATA_CHUNK_LENGTH);
			}
			
			if ($dumpline==="")
				break;

			// Remove UTF8 Byte Order Mark at the file beginning if any
			if ($foffset==0)
				$dumpline=preg_replace('|^\xEF\xBB\xBF|','',$dumpline);

			// Handle DOS and Mac encoded linebreaks (I don't know if it really works on Win32 or Mac Servers)
			$dumpline=str_replace("\r\n", "\n", $dumpline);
			$dumpline=str_replace("\r", "\n", $dumpline);
			
			// Recognize delimiter statement
			if (!$inparents && strpos ($dumpline, "DELIMITER ") === 0)
				$delimiter = str_replace ("DELIMITER ","",trim($dumpline));

			// Skip comments and blank lines only if NOT in parents
			if (!$inparents)
			{
				$skipline=false;
				reset($this->comment);
				foreach ($this->comment as $comment_value)
				{
					if (trim($dumpline)=="" || strpos (trim($dumpline), $comment_value) === 0)
					{
						$skipline=true;
						break;
					}
				}
				if ($skipline)
				{
					$linenumber++;
					continue;
				}
			}

			// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
			$dumpline_deslashed = str_replace ("\\\\","",$dumpline);

			// Count ' and \' (or " and \") in the dumpline to avoid query break within a text field ending by $delimiter
			$parents=substr_count ($dumpline_deslashed, $this->string_quotes)-substr_count ($dumpline_deslashed, "\\".$this->string_quotes."");
			if ($parents % 2 != 0)
				$inparents = !$inparents;

			// Add the line to query
			$query .= $dumpline;

			// Don't count the line if in parents (text fields may include unlimited linebreaks)
			if (!$inparents)
				$querylines++;

			// Stop if query contains more lines as defined by $this->max_query_lines
			if ($querylines > $this->max_query_lines)
			{
				$error = array(
					"status" => "error",
					"message" => array(
						"line" => '',
						"error_exp" => "توقف در رديف $linenumber.در اين محل کوئري مورد نظر شامل بيش از ".$this->max_query_lines." رديف است."
					)
				);
				return(json_encode($error));
			}

			// Execute query if end of query detected ($delimiter as last character) AND NOT in parents
			if ((preg_match('/'.preg_quote($delimiter,'/').'$/',trim($dumpline)) || $delimiter=='') && !$inparents)
			{
				// Cut off delimiter of the end of the query
				$query = substr(trim($query),0,-1*strlen($delimiter));
				
				if($new_prefix != '')
					$query = str_replace("{NUKEPREFIX}", $new_prefix, $query);
				
				if ($this->db_connection_charset !== '')
				{
					$this->db->query("set character_set_server='".$this->db_connection_charset."'");
					$this->db->query("SET NAMES '".$this->db_connection_charset."'");
				}
				
				if (!$this->db->query($query))
				//if (!mysql_query($query, $dbconnection))
				{
					$error = array(
						"status" => "error",
						"message" => array(
							"line" => trim(nl2br(htmlentities($query))),
							"error_exp" => $this->db->getErrors('last')['message']
						)
					);
					return(json_encode($error));
				}
				$totalqueries++;
				$queries++;
				$query="";
				$querylines=0;
			}
			$linenumber++;
		}

		// Get the current file position
		if (!$gzipmode) 
			$nfoffset = ftell($file);
		else
			$nfoffset = gztell($file);
			
		if (!$nfoffset)
		{
			$error = array(
				"status" => "error",
				"message" => array(
					"line" => '',
					"error_exp" => "UNEXPECTED: Can't read the file pointer offset"
				)
			);
			return(json_encode($error));
		}


		$lines_this   = $linenumber-$start;
		$lines_done   = $linenumber-1;
		$lines_togo   = ' ? ';
		$lines_tota   = ' ? ';

		$queries_this = $queries;
		$queries_done = $totalqueries;
		$queries_togo = ' ? ';
		$queries_tota = ' ? ';

		$bytes_this   = $nfoffset-$foffset;
		$bytes_done   = $nfoffset;
		$kbytes_this  = round($bytes_this/1024,2);
		$kbytes_done  = round($bytes_done/1024,2);
		$mbytes_this  = round($kbytes_this/1024,2);
		$mbytes_done  = round($kbytes_done/1024,2);

		/*if (!$gzipmode)
		{*/
			$bytes_togo  = $filesize-$nfoffset;
			$bytes_tota  = $filesize;
			$kbytes_togo = round($bytes_togo/1024,2);
			$kbytes_tota = round($bytes_tota/1024,2);
			$mbytes_togo = round($kbytes_togo/1024,2);
			$mbytes_tota = round($kbytes_tota/1024,2);

			$pct_this   = ceil($bytes_this/$filesize*100);
			$pct_done   = ceil($nfoffset/$filesize*100);
			$pct_togo   = 100 - $pct_done;
			$pct_tota   = 100;

			if ($bytes_togo==0) 
			{
				$lines_togo   = '0'; 
				$lines_tota   = $linenumber-1; 
				$queries_togo = '0'; 
				$queries_tota = $totalqueries; 
			}

			$pct_bar = $pct_done;
		/*}
		else
		{
			$bytes_togo  = ' ? ';
			$bytes_tota  = ' ? ';
			$kbytes_togo = ' ? ';
			$kbytes_tota = ' ? ';
			$mbytes_togo = ' ? ';
			$mbytes_tota = ' ? ';

			$pct_this    = ' ? ';
			$pct_done    = ' ? ';
			$pct_togo    = ' ? ';
			$pct_tota    = 100;
			$pct_bar     = -1;
		}*/
		
		if ($linenumber < $start+$this->linespersession)
		{				
			$error = array(
				"status" => "success",
				"message" => array(
					"line" => '',
					"error_exp" => "Congratulations: End of file reached, assuming OK"
				),
				"lines_this" => $lines_this,
				"lines_done" => $lines_done,
				"lines_togo" => $lines_togo,
				"lines_tota" => $lines_tota,
				
				"queries_this" => $queries_this,
				"queries_done" => $queries_done,
				"queries_togo" => $queries_togo,
				"queries_tota" => $queries_tota,
				
				"bytes_this" => $bytes_this,
				"bytes_done" => $bytes_done,
				"bytes_togo" => $bytes_togo,
				"bytes_tota" => $bytes_tota,
				
				"kbytes_this" => $kbytes_this,
				"kbytes_done" => $kbytes_done,
				"kbytes_togo" => $kbytes_togo,
				"kbytes_tota" => $kbytes_tota,
				
				"mbytes_this" => $mbytes_this,
				"mbytes_done" => $mbytes_done,
				"mbytes_togo" => $mbytes_togo,
				"mbytes_tota" => $mbytes_tota,
				
				"pct_this" => $pct_this,
				"pct_done" => $pct_done,
				"pct_togo" => $pct_togo,
				"pct_tota" => $pct_tota,
				
				"pct_bar" => $pct_bar,
				
			);
			return(json_encode($error));
		}
		else
		{
			$error = array(
				"status" => "progress",
				"message" => array(
					"line" => '',
					"error_exp" => "Press <b><a href=\"".$_SERVER["PHP_SELF"]."\">STOP</a></b> to abort the import <b>OR WAIT!<br /><br />waiting ..."
				),
				"start" => $linenumber,
				"foffset" => $nfoffset,
				"totalqueries" => $totalqueries,
				
				"lines_this" => $lines_this,
				"lines_done" => $lines_done,
				"lines_togo" => $lines_togo,
				"lines_tota" => $lines_tota,
				
				"queries_this" => $queries_this,
				"queries_done" => $queries_done,
				"queries_togo" => $queries_togo,
				"queries_tota" => $queries_tota,
				
				"bytes_this" => $bytes_this,
				"bytes_done" => $bytes_done,
				"bytes_togo" => $bytes_togo,
				"bytes_tota" => $bytes_tota,
				
				"kbytes_this" => $kbytes_this,
				"kbytes_done" => $kbytes_done,
				"kbytes_togo" => $kbytes_togo,
				"kbytes_tota" => $kbytes_tota,
				
				"mbytes_this" => $mbytes_this,
				"mbytes_done" => $mbytes_done,
				"mbytes_togo" => $mbytes_togo,
				"mbytes_tota" => $mbytes_tota,
				
				"pct_this" => $pct_this,
				"pct_done" => $pct_done,
				"pct_togo" => $pct_togo,
				"pct_tota" => $pct_tota,
				
				"pct_bar" => $pct_bar,
				
			);
			return(json_encode($error));
		}
	}	
}

?>