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
		global $nuke_configs, $db, $cache;
		
		$this->ping_sites   = $nuke_configs['ping_sites'];
		$this->active_pings	= $nuke_configs['active_pings'];
		$this->options      = ($nuke_configs['ping_options'] != '') ? phpnuke_unserialize($nuke_configs['ping_options']):array('limit_ping' => 0, 'limit_number' => 1, 'limit_time' => 15);
		if($cache->isCached("future_pings"))
		{
			$future_pings = $cache->retrieve("future_pings");
			$this->future_pings = ($future_pings != '') ? phpnuke_unserialize($cache->retrieve("future_pings")):array();
		}
		else
		{
			$this->future_pings = array('update_time' => _NOWTIME, 'posts' => array());
			$cache->store("future_pings", $this->future_pings);
		}
		
		if($this->ping_sites != '')
			$this->ping_sites = explode("\n", str_replace("\r", "", $this->ping_sites));
			
		$last_pings_update = (isset($this->future_pings['update_time'])) ? $this->future_pings['update_time']:_NOWTIME;

		if((_NOWTIME-$last_pings_update) > ($this->options['limit_time'] * 60))
		{
			$this->phpnuke_FuturePing();
		}
	}
	
	function set_defaults($module_name, $id, $data)
	{
		$this->_Module	= $module_name;
		$this->_ID		= $id;
		$this->_data	= $data;
		$this->_data['module_name']	= $module_name;
	}
	
	function phpnuke_PingServices($post_id_title)
	{
		global $nuke_configs;
		
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
		$feedlink = LinkToGT("index.php?modname=Feed&mode=rss".((isset($this->_data['module_name']) && $this->_data['module_name'] != '') ? strtolower($this->_data['module_name']):""));
		$check_url = ($this->_post_url != '') ? $this->_post_url : $feedlink;

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
	
	function phpnuke_Ping()
	{
		global $nuke_configs, $cache;
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
						$this->future_pings['posts'][$this->_Module][$this->_ID] = $this->_data; 
						$cache->store('future_pings', $this->future_pings);
					}
					else if($this->_data["status"] == 'publish' && $this->_data['title'] != '')
					{
						if(isset($this->options['limit_ping']) && $this->options['limit_ping'] == 1)
						{
							$this->future_pings['posts'][$this->_Module][$this->_ID] = $this->_data; 
							$cache->store('future_pings', $this->future_pings);
						}
						else
						{
							die("Aaaaa");
							$this->phpnuke_PingServices($post_id_title);
						}
					}
				}
			}
		}
	}
	
	function phpnuke_FuturePing()
	{
		global $nuke_configs, $cache;
		
		// future ping list is empty
		if(!empty($this->future_pings['posts']))
		{		
			$i = 0;
			foreach($this->future_pings['posts'] as $module_name => $pingdata)
			{
				foreach($pingdata as $id => $_data)
				{
					// if future published post later has been changed to draft or other status
					// then delete it from the ping list (It will be automatically be pinged when its status changes to publish)
					if(($_data['status'] != 'publish' && $_data['status'] != 'future') || $_data["time"] > _NOWTIME)
						continue;
					if($_data["time"] <= _NOWTIME && ($_data['status'] == 'publish' || $_data['status'] == 'future'))
					{
						if($i <= $this->options['limit_number'])
						{
							unset($this->future_pings['posts'][$module_name][$id]);
							$this->future_pings['update_time'] = _NOWTIME;
							$cache->store("future_pings", $this->future_pings);
							
							$post_title = $_data['title'];
							$this->_ID = $id;
							$this->_data = $_data;
							
							$post_id_title = $id.'~#'.$post_title;
							$this->phpnuke_PingServices($post_id_title);
						}
						else
							break 2;
					}
					$i++;
				}
			}
		}
	}
	
	function phpnuke_FuturePingDelete($module_folder, $id)
	{ 
		global $nuke_configs, $cache;
		
		if(!empty($this->future_pings) && isset($this->future_pings['posts'][$module_folder][$id]))
		{
			unset($this->future_pings['posts'][$module_folder][$id]);
			$cache->store("future_pings", $this->future_pings);
		}
	}
}

?>