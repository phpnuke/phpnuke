<?PHP
/****************************************************************
*****************************************************************
Protect and Secure Your PHP-NUKE With MTSN v.4.3.0 (2014)
Mashhad Team Secure Nuke
Powered By MashhadTeam.com [PHPNUKE.IR]
Email : mashhadteam@gmail.com
CopyRight By www.PHPNuke.ir
AmirHossein Moazzami (Zero-F)
Mahmoud Namvar [iman64]

****************************************************************
****************************************************************/

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class sql_inject
{
    var $urlRedirect;
    var $bdestroy_session;
    var $rq;
    var $bLog;
    var $mtsn_text_file1;
    var $ipbanx;
    var $mtsn_alarm1;
    var $send_mail;
    var $admin_mail;
    var $banning_expire;
	
	function __construct($mLog=FALSE,$bdestroy_session=FALSE,$urlRedirect=FALSE)
	{
		global $nuke_config;
        $this->bLog = ($mLog!=FALSE) ? $mLog:'';
        $this->urlRedirect = (((trim($urlRedirect)!='') && file_exists($urlRedirect))?$urlRedirect:'');
        $this->bdestroy_session = $bdestroy_session;
        $this->rq = '';
		$this->banning_expire = ($nuke_config['mtsn_block_ip_expire'] != 0) ? (_NOWTIME+$nuke_configs['mtsn_block_ip_expire']):0;
    }
   
	function test($sRQ)
	{
        global $db, $admin, $admin_file, $nuke_configs;
        $sRQ = strtolower($sRQ);
        $this->rq = $sRQ;
        $aValues = array();
        $aTemp = array();
        $aWords = array();
        $aSep = array(' and ',' or ');
        $sConditions = '(';
        $matches = array();
        $sSep = '';

		$this->mtsn_text_file1 = stripslashes($nuke_configs['mtsn_text_file']);
		$this->ipbanx = stripslashes($nuke_configs['mtsn_block_ip']);
		$this->mtsn_alarm1=stripslashes($nuke_configs['mtsn_show_alarm']);
		$this->send_mail=stripslashes($nuke_configs['mtsn_send_mail']);
		$this->admin_mail=stripslashes($nuke_configs['mtsn_admin_mail']);

		//////////////////////// Start String Filter ///////////////////
		if (isset($_REQUEST) && !@is_admin()) {
		//if (isset($_REQUEST)) { for anonymouse test
			$parameters = $_REQUEST;
			@$this->protect_sql_injection($parameters);
		}
		
        if (!is_admin()) {
         if($nuke_configs['mtsn_string_filter'] == "1"){
           if ($this->_in_post('exec')) return $this->detect2('exec');
           if ($this->_in_post('/*')) return $this->detect2('/*');
           if (@stristr($sRQ,'/*')) return $this->detect();
           if (@stristr($sRQ,'/*') || stristr($sRQ,'/*')) return $this->detect();
           if (@stristr($sRQ,'*') || stristr($sRQ,'*')) return $this->detect();
           if (@stristr($sRQ,'.txt?') || stristr($sRQ,'.txt?')) return $this->detect();
           if (@stristr($sRQ,'.gif?') || stristr($sRQ,'.gif?')) return $this->detect();
           if (@stristr($sRQ,'..') || stristr($sRQ,'..')) return $this->detect();
           if (@stristr($sRQ,'"') || stristr($sRQ,'"')) return $this->detect();
           if (@stristr($sRQ,"'") || stristr($sRQ,"'")) return $this->detect();
         }
        /////////////////////////// End String Filter ////////////////////
        /////////////////////////////////////////////////////////////////
        /////////////////////////// Start Html Filter///////////////////
        if($nuke_configs['mtsn_html_filter'] == "1"){
          if (@stristr($sRQ,'<') || stristr($sRQ,'<')) return $this->detect();
          if (@stristr($sRQ,'>') || stristr($sRQ,'>')) return $this->detect();
          if (@stristr($sRQ,'<script>') || stristr($sRQ,'<script>')) return $this->detect();
        }
        /////////////////////////// End Html Filter///////////////////////
        /////////////////////////// Start Sql Injection Filter////////////
        if($nuke_configs['mtsn_injection_filter'] == "1"){
          if ($this->_in_post('union')) return $this->detect2('union');
          if (@stristr($sRQ, "exec") || @stristr($sRQ, "cmd"))   return $this->detect();
          if (@stristr($sRQ,'concat') || stristr($sRQ,'concat')) return $this->detect();
          if (@stristr($sRQ,'INSERT INTO') || stristr($sRQ,'INSERT INTO')) return $this->detect();
          if (@stristr($sRQ,'UNION') || stristr($sRQ,'UNION')) return $this->detect();
        }
        }
        /////////////////////////// End Sql Injection Filter////////////////
        ////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////

        //if (@ereg("--",$sRQ) || @ereg("xp_",$sRQ))   return $this->detect();
        if (is_int(strpos($sRQ,';'))){
            $aTemp = explode(';',$sRQ);
            if ($this->_in_post($aTemp[1])) return $this->detect();
        }


        $aTemp = explode(" where ",$sRQ);
        if (count($aTemp)==1) return FALSE;
        $sConditions = $aTemp[1];
        $aWords = explode(" ",$sConditions);
        if(strcasecmp($aWords[0],'select')!=0) $aSep[] = ',';
        $sSep = '('.implode('|',$aSep).')';
        $aValues = @preg_split($sSep,$sConditions,-1, PREG_SPLIT_NO_EMPTY);


        foreach($aValues as $i => $v)
        {

            if (is_int(strpos($v,'=')))
            {
                 $aTemp = explode('=',$v);
            }


            if (is_int(strpos($v,'<>')))
            {
                $aTemp = explode('<>',$v);
                if ((trim($aTemp[0])!=trim($aTemp[1]))&& ($this->_in_post('<>'))) return $this->detect();
            }
        }

        if (strpos($sConditions,' null'))
        {
            {
                foreach($matches as $i => $v)
                {
                    if ($this->_in_post($v))return $this->detect();
                }
            }
        }

        if (@preg_match("/[a-z0-9]+ +between +[a-z0-9]+ +and +[a-z0-9]+/",$sConditions,$matches))
        {
            $Temp = explode(' between ',$matches[0]);
            $Evaluate = $Temp[0];
            $Temp = explode(' and ',$Temp[1]);
            if ((strcasecmp($Evaluate,$Temp[0])>0) && (strcasecmp($Evaluate,$Temp[1])<0) && $this->_in_post($matches[0])) return $this->detect();
        }
        return FALSE;
    }

	function test_badw($match)
	{
		if(isset($match[2]) && (str_replace(" ", "", $match[2]) == 'true--' OR str_replace(" ", "", $match[2]) == 'true'))
		{
			return "sql injection";
		}
		elseif((isset($match[6]) && isset($match[9])) && (str_replace(" ", "", $match[6]) == str_replace(" ", "", $match[9])))
		{
			if($match[6] != "" && $match[9] != ""){
				return "sql injection";
			}
		}
		return $match[6]."=".$match[9];
	}
	
	function protect_sql_injection($param)
	{
		foreach ($param as $param_name => $param_val) {
			if(@is_array($param_val)){
				@$this->protect_sql_injection($param_val);
			}else{
				$badw = preg_replace_callback("#((\strue\s?([\-]*))|((or\s|or\t|OR\s|OR\t|\sOR\s|\tOR\t|\sor\s|\tor\t|\s|\t)?([a-zA-Z0-9]*)(\s|\t)?=(\s|\t)?([a-zA-Z0-9]*)))#i", array($this, 'test_badw'), str_replace("'","",$param_val));
				if($badw != str_replace("'","",$param_val)){
					return $this->detect();
				}
			}
		}
	}
    
	function _in_post($value)
    {
        foreach($_POST as $i => $v)
        {
             if (is_int(strpos(strtolower($v),$value))) return TRUE;
        }
        return FALSE;
    }
	
    function detect()
    {
		global $db, $met, $visitor_ip;
		
		$time=_NOWTIME;
		$this->servAd = getenv("SERVER_NAME");
		if ($this->mtsn_text_file1=="1"){
			if ($this->bLog)
			{
				$fp = @fopen($this->bLog,'a+');
				if ($fp)
				{
				//	fputs($fp,"\r\n".'echo"'.date("d-m-Y H:i:s").' ['.$this->rq.'] from: '.$this->sIp = $_SERVER["REMOTE_ADDR"].' ServerName: '.$this->servAd.'";');       Nuke 8.4 fix
					fclose($fp);
				}
			}
		}
		$time = mres($time);
		$ipp= mres($visitor_ip);
		$rq = mres($this->rq);
		$this->servAd = getenv("SERVER_NAME");
		$result = $db->table(MTSN_TABLE)
			->insert([
				'server' => $this->servAd,
				'ip' => $ipp,
				'time' => $time,
				'method' => $rq,
			]);

		if ($this->ipbanx =="1"){
			$ippx= mres($visitor_ip);
			if ($ippx != "127.0.0.1") {
				$this->servAd = getenv("SERVER_NAME");
				
				$result = $db->table(MTSN_IPBAN_TABLE)
					->insert([
						'blocker' => 'mtsn system',
						'ipaddress' => $ippx,
						'reason' => $rq,
						'time' => $time,
						'expire' => $this->banning_expire,
					]);
				cache_system('nuke_mtsn_ipban');
			}
		}
		
		if($this->send_mail == "1"){
			$nowdate = @now_in_hejri();
			$body = "";
			$body .= "Ip Address: " . $ipp . "\n";
			$body .= "Time: " . $nowdate . "\n";
			$body .= "Method of Attack: " . $rq . "\n";
			$body .= "Domain : " . $nuke_configs['nukeurl'] . "\n";
			$body .= "Powered By MashhadTeam [PHPNuke.Ir]\n";
			$email_address = $this->admin_mail;
			$email_name = "MTSN Alert";
			phpnuke_mail($email_address,$email_name,"<pre>" . $body . "</pre>",$email_name,$email_address);
		}
		if($this->mtsn_alarm1=="1"){
			$this->_alert_show();
		}
		return TRUE;
    }

	function detect2($met)
	//baraye sabte hamalate post.
	{
		global $db, $visitor_ip;
		$time=_NOWTIME;
		$this->servAd = getenv("SERVER_NAME");
		if ($this->mtsn_text_file1=="1"){
			if ($this->bLog)
			{
				$fp = @fopen($this->bLog,'a+');
				if ($fp)
				{
				//	fputs($fp,"\r\n".'echo"'.date("d-m-Y H:i:s").' ['.$this->rq.' '.$met.'] from: '.$this->sIp = $_SERVER["REMOTE_ADDR"].' ServerName: '.$this->servAd.'";');    //Nuke 8.4 fix
					fclose($fp);
				}
			}
		}

		$ipp= mres($visitor_ip);
		$met = mres($met);
		$result = $db->table(MTSN_TABLE)
			->insert([
				'server' => $this->servAd,
				'ip' => $ipp,
				'time' => $time,
				'method' => $met,
			]);
		
		if($this->send_mail=="1"){
			$body = "";
			$body .= "Ip Address: " . $ipp . "\n";
			$body .= "Time: " . $nowdate . "\n";
			$body .= "Method of Attack: " . $rq . "\n";
			$body .= "Domain : " . $nuke_configs['nukeurl'] . "\n";
			$body .= "Powered By MashhadTeam [PHPNuke.Ir]\n";
			$email_address = $this->admin_mail;
			$email_name = "MTSN Alert";
			phpnuke_mail($email_address,$email_name,"<pre>" . $body . "</pre>",$email_name,$email_address);
		}
		if($this->mtsn_alarm1=="1"){
		$this->_alert_show();

		}
		return TRUE;
	}

	function _alert_show(){
		die("
		<html>
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		<title>Control By MTSN v4.4.0</title>
		</head>
		<body bgcolor=\"#000066\" style=\"font-family:arial; color:#ffffff; cursor:default;\">
		<table width=\"100%\" height=\"100%\"><tr><td align=\"center\" valign=\"center\">
		<img src=\"../images/mtsn/mtsn.gif\" width=\"146\" height=\"125\" alt=\"mtsn\" title=\"mtsn\"><br><br>
		<span style=\"font-size:24px; font-weight:bold;\">Control By MashhadTeam</span><br>
		<span style=\"font-size:12px;\">Protect and Secure <b>PHPNUKE</b> With <b>MTSN</b> v.4.4.0 (2017)</span><br>
		<span style=\"font-size:14px;\"><b>M</b>ashhad <b>T</b>eam <b>S</b>ecure <b>N</b>uke</span><br>
		<div class=\"text-center\"><a style=\"font-size:11px; font-weight:bold; text-decoration:none; color:f9f9f9;\" href=\"http://www.phpnuke.ir\" onmouseover=this.style.color='#cccccc' onmouseout=this.style.color='#f6f6f6'>phpnuke.ir</a></div>
		</td></tr></table>
		</body>
		</html>");
	}
}

?>