<?php

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class PingOptimizer 
{
	var $active_pings = 0;			// Ping Optimizer status
	var $ping_sites = '';			// Ping Optimizer pinging URLs
	var $future_pings = array();	// List of future posts to be pinged
	var $future_ping_time = '';		// Last updated time for future ping
	var $current_date = '';			// Holds the current date and time
	var $post_title = "";			// Title of the post
	var $request;					// Request Type Fixed  error
	var $excessive_pinging = 0;	
	var $already_pinged = array();	
	var $options = array();	

	function __construct()
	{
		global $nuke_configs, $db;
		
		$this->current_date = _NOWTIME;
		$this->ping_sites   = $nuke_configs['ping_sites'];
		$this->active_pings	= $nuke_configs['active_pings'];
		$this->future_pings = ($nuke_configs['future_pings'] != '') ? phpnuke_unserialize($nuke_configs['future_pings']):array();
		$this->options      = ($nuke_configs['ping_options'] != '') ? phpnuke_unserialize($nuke_configs['ping_options']):array('limit_ping' => 0, 'limit_number' => 1, 'limit_time' => 15);
		
		if( !is_array($this->future_pings))
		{
			$this->future_pings = array();
		}
		if( !$this->future_ping_time = $nuke_configs['future_ping_time'])
		{
			$this->future_ping_time = _NOWTIME;
		}
		
		if($this->ping_sites != '')
			$this->ping_sites = explode("\n", str_replace("\r", "", $this->ping_sites));
		// Check if ping limit reached and Duplicate results in log
		// Fixed the bug WP pinging all the time
		$this->excessive_pinging = 0;
		
		if($this->options['limit_ping'] == 1)
		{
			$last_ping_time	= $nuke_configs['last_ping_time'];
			$curr_time		= _NOWTIME;
			$limit_time		= $this->options['limit_time'] * 60;
			$limit_number	= $this->options['limit_number'];
			
			$ping_num		= $nuke_configs['ping_num'];
			if($last_ping_time <= 0) $last_ping_time = $curr_time;
			
			if(($limit_time >= ($curr_time - $last_ping_time)) && ($ping_num >= $limit_number))
				$this->excessive_pinging = 1;
			else
				if($ping_num >= $limit_number)
					update_configs('ping_num',0);
		}
	}
	
	function set_defaults($id, $data)
	{
		$this->_ID		= $id;
		$this->_data	= $data;
	}
	
	function phpnuke_PingServices($post_id_title)
	{
		global $nuke_configs;
		
		$this->already_pinged = array();
		$this->_post_title = $post_id_title;
		$this->_post_url = '';
			
		if(strpos($post_id_title,'~#') !== false)
		{
			$post_id_title = explode('~#',$post_id_title);
			$this->_post_title = $post_id_title[1];
			$this->_post_url = (!empty($this->_data)) ? $this->_data['post_url']:'';
			$this->_poster = (!empty($this->_data)) ? $this->_data['poster']:'';
			$this->_poster_ip = (!empty($this->_data)) ? $this->_data['poster_ip']:'';
		}
		
		$services = $nuke_configs['ping_sites'];
		$services = preg_replace("|(\s)+|", '$1', $services);
		$services = trim($services);
		if('' != $services)
		{
			set_time_limit(300);
			$services = explode("\n", $services);
			foreach ($services as $service)
				$this->phpnuke_SendXmlrpc($service);
		}
		unset($this->already_pinged);
		set_time_limit(60);
	}
	
	function phpnuke_SendXmlrpc($server = '', $path = '')
	{
		global $nuke_configs;
		include_once ('includes/class-IXR.php');
		
		// using a timeout of 3 seconds should be enough to cover slow servers
		$client = new IXR_Client($server, ((!strlen(trim($path)) || ('/' == $path)) ? false : $path));
		$client->timeout = 3;
		$client->useragent .= ' -- PhpNuke-MTEdition/'.$nuke_configs['Version_Num'];
	
		// when set to true, this outputs debug messages by itself
		$client->debug = false;
		$nukeurl = rtrim($nuke_configs['nukeurl'], '/') . '/';
		$check_title = $this->_post_title = $this->_post_title ;
		$feedlink = ($nuke_configs['gtset'] == 1) ? "rss/":"index.php?modname=Feed&mode=rss";
		$check_url = ($this->_post_url != '') ? $this->_post_url : $feedlink;

		if(!in_array($server,$this->already_pinged))
		{
			$this->already_pinged[] = $server;
			///$this->_post_title = $this->_post_title.'###'.$check_url;///
			// the extendedPing format should be "blog name", "blog url", "check url" (post url), and "feed url",
			// but it would seem as if the standard has been mixed up. It's therefore good to repeat the feed url.
			//Replaced below line to solve extended ping problem
			//if($client->query('weblogUpdates.extendedPing', get_settings('blogname'), $nukeurl, $check_url, get_bloginfo('rss2_url'))) { 
			if($client->query('weblogUpdates.extendedPing', $check_title, $check_url, $feedlink))
				add_log(sprintf(_SUCCESS_PING_LOG, "<a href=\"".$this->_post_url."\">".$this->_post_title."</a>", $server), 1, '', $this->_poster_ip, $this->_poster);
			else
				if($client->query('weblogUpdates.ping', $nuke_configs['sitename'], $nukeurl))
					add_log(sprintf(_SUCCESS_PING_LOG, "<a href=\"".$this->_post_url."\">".$this->_post_title."</a>", $server), 1, '', $this->_poster_ip, $this->_poster);
				else
					add_log(sprintf(_ERROR_PING_LOG, "<a href=\"".$this->_post_url."\">".$this->_post_title."</a>", $server, $client->error->message), 1, '', $this->_poster_ip, $this->_poster);
		}
	}
	
	function phpnuke_Ping()
	{
		global $nuke_configs;
		if($this->ping_sites != "")
		{
			if($this->active_pings == 1)
			{
				if($this->_data['status'] == 'publish' || $this->_data['status'] == 'future')
				{
					$post_id_title = intval($this->_ID).'~#'.filter($this->_data['title'], "nohtml");
					// if post_date is greater than current time/date then its a future post (don't ping it)			
					if($this->_data['time'] > _NOWTIME)
					{
						$this->future_pings[$this->_ID] = $this->_data; 
						update_configs('future_pings', $this->future_pings);
					}
					else if($this->_data["status"] == 'publish' && $this->_data['title'] != '')
					{	
						if($this->excessive_pinging != 1)
						{
							$this->phpnuke_PingServices($post_id_title);
							update_configs('last_ping_time',_NOWTIME);
							update_configs('ping_num', $nuke_configs['ping_num']+1);
						}
						else
						{
							$this->future_pings[$this->_ID] = $this->_data; 
							update_configs('future_pings', $this->future_pings);
						}
					}
				}
			}
		}
	}
	
	function phpnuke_FuturePing()
	{
		global $nuke_configs;
		
		// future ping list is empty
		if(count($this->future_pings) <= 0)
			return true;
		
		// Check last updated date and update it if more than 15 min, and ping if any future post's time elasped
		$_now_time = _NOWTIME;
		$_prev_time  = $this->future_ping_time;
		$elapsed_min = ($_now_time-$_prev_time)/(60);
		$do_ping = 0;
		
		/// if last update/ping time more than 5 minutes
		if($elapsed_min > 5)
		{
			if(is_array($this->future_pings))
			{
				foreach($this->future_pings as $id => $_data)
				{
					// if future published post later has been changed to draft or other status
					// then delete it from the ping list (It will be automatically be pinged when its status changes to publish)
					if($_data['status'] != 'publish' && $_data['status'] != 'future')
						unset($this->future_pings[$id]); 
					if($_data["time"] <= $_now_time && ($_data['status'] == 'publish' || $_data['status'] == 'future'))
					{		
						unset($this->future_pings[$id]);
						$do_ping = 1;
						$post_title = $_data['title'];
						$this->_ID = $id;
						$this->_data = $_data;
					}
				}
			}
			update_configs("future_pings", $this->future_pings);
			update_configs('future_ping_time', $_now_time);
			if($do_ping == 1)
			{
				$post_id_title = $post_id.'~#'.$post_title;
				$this->phpnuke_PingServices($post_id_title);
			}
		}
	}
	
	function phpnuke_FuturePingDelete($id)
	{ 
		global $nuke_configs;
		
		if(count($this->future_pings) <= 0)
			return $id;
		if(isset($this->future_pings[$id]))
		{		
			unset($this->future_pings[$id]);
			update_configs('future_pings', $this->future_pings);
		}
		return $id;
	}
}

$PingOptimizer = new PingOptimizer();

$PingOptimizer->phpnuke_FuturePing();

?>