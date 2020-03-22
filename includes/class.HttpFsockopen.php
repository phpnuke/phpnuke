<?php 

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class HttpFsockopen {

	protected $url;
	protected $path;
	protected $host;
	protected $query;
	protected $post;
	protected $port;
	protected $headers;
	protected $ssl;
	protected $method;
	protected $timeout;

	protected static $autoload;
	
	public function __construct($url, $use_autoload = true){
		if(is_null(HttpFsockopen::$autoload) && $use_autoload){
			HttpFsockopen::$autoload = true;
			spl_autoload_register(array("HttpFsockopen", "load"));
		}
		$url_array = parse_url($url);
		
		if(!empty($url_array["scheme"]) && preg_match("#^https|ssl$#i", $url_array["scheme"])){
			$this->ssl = true;
		} else {
			$this->ssl = false;
		}

		if(empty($url_array["port"])){
			if($this->ssl){
				$this->port = 443;
			} else {
				$this->port = 80;
			}
		}

		if(array_key_exists("path", $url_array)){
			$this->path = $url_array["path"];
		} else {
			$this->path = false;
		}
		
		if(array_key_exists("query", $url_array)){
			$this->query = $url_array["query"];
		} else {
			$this->query = false;
		}
		
		$this->host = $url_array["host"];
		$this->method = "GET";
		$this->timeout = 15;
	}

	public static function load($class){
		$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 
			preg_replace("#[_]+#", DIRECTORY_SEPARATOR , $class) . ".php";
		if(file_exists($file))
		include_once $file ;
	}

	public function setQueryData($data){
		if(is_array($data)){
			$data = http_build_query($data);
		}
		$this->query = $data;
		return $this;
	}
	
	public function setPostData($data){
		if(is_array($data)){
			$data = http_build_query($data);
		}
		$this->post = $data;
		$this->method = "POST";
		$this->setHeaders("Content-Type", "application/x-www-form-urlencoded");
		return $this;
	}

	public function setMethod($method){
		$previous_method = $this->method;
		if(preg_match("#^[a-z]+$#i", $method)){
			$this->method = strtoupper($method);
		}
		if($this->method == "POST" && $previous_method != "POST"){
			$this->setHeaders("Content-Type", "application/x-www-form-urlencoded");
		}
		if($this->method != "POST" && $previous_method == "POST"){
			$this->setHeaders("Content-Type", null);
		}
		return $this;
	}

	public function setTimeout($timeout){
		$this->timeout = $timeout;
		return $this;
	}

	public function setPort($port){
		$this->port = $port;
		return $this;
	}

	public function setHeaders($key, $value = null){
		if(is_array($key)){
			foreach($key as $key => $value){
				if(is_null($value)){
					unset($this->headers[$key]);
				} else {
					$this->headers[$key] = $value;
				}
			}
		} else {
			if(is_null($value)){
				unset($this->headers[$key]);
			} else {
				$this->headers[$key] = $value;
			}
 		}
		return $this;
	}
	
	public function setUserAgent($user_agent){
		return $this->setHeaders("User-Agent", $user_agent);
	}
	
	public function exec(){
		$socket = fsockopen(($this->ssl ? "ssl://" : "") . $this->host, $this->port, $errno, $errstr,
			$this->timeout);
		$contents = "";
		
		if($socket){
			$http  = $this->method . " ". (strlen($this->path) ? $this->path : "/") .
				(strlen($this->query)>0 ? "?" . $this->query : "")
				." HTTP/1.1\r\n";
			$http .= "Host: ".$this->host."\r\n";
			if(is_array($this->headers) && !empty($this->headers))
			{
				foreach($this->headers as $key => $value){
					$http .= $key. ": ".$value."\r\n";
				}
			}
			$http .= "Content-length: " . strlen($this->post) . "\r\n";
			$http .= "Connection: close\r\n\r\n";
			if(!is_null($this->post))
			$http .= $this->post . "\r\n\r\n";
			fwrite($socket, $http);	
			while (!feof($socket)) {
				$contents .= fgetc($socket);
			}
			fclose($socket);
		}
		
		return new HttpFsockopen_Response($socket, $contents, $errno, $errstr);
	}

}

class HttpFsockopen_Response {
	
	protected $socket;
	protected $errno;
	protected $errstr;
	
	public function __construct($socket, $contents, $errno, $errstr){
		$this->socket = $socket;
		$this->errno = $errno;
		$this->errstr = $errstr;
		if( $errno == 0){
			$limiter = strpos($contents,"\r\n\r\n");
			$headerStr = substr($contents, 0, $limiter);
			$this->body = substr($contents, $limiter+3);
			$this->header = new HttpFsockopen_Header($headerStr);
		} else {
			$this->body = $contents;
			$this->header = new HttpFsockopen_Header("");
		}
	}
	
	public function getContent(){
		return $this->body;
	}
	
	public function getHeader(){
		return $this->header;
	}
	
	public function getSocket(){
		return $this->socket;
	}
	
	public function getErrno(){
		return $this->errno;
	}
	
	public function getErrstr(){
		return $this->errstr;
	}
}

class HttpFsockopen_Header{
	protected $data = array();
	protected $cookie;

	public function __construct($data){
		if(is_string($data)){
			$output = explode("\r\n",$data);
		} else {
			$output = $data;
		}
		$header = array();
		if(empty($output)){
			$output=array();
		}
		foreach($output as $line){
			if(empty($line)){
				break;
			}elseif(preg_match("#^((?P<key>[^\:]+)\s*:)?\s*(?P<content>.+)$#i", $line, $result)){
				if(!empty($result['key'])){
					if(empty($header[$result['key']])){
						$header[$result['key']] = $result['content'];
					}else{
						if(!is_array($header[$result['key']])){
							$buffer = $header[$result['key']];
							unset($header[$result['key']]);
							$header[$result['key']] = array($buffer, $result['content']);
							
						}else{
							$header[$result['key']][] = $result['content'];
						}
					}
				}else $header[] = $result['content'];
			}
		}
		$this -> data = $header;
	}
	
	public function test(){
		$args = func_get_args();
		if(array_key_exists(0, $args)){
			if(array_key_exists($args[0], $this -> data)){
				if(array_key_exists(1, $args)){
					return $this -> data[$args[0]] == $args[1];
				} else return true;
			}else return false;
		}
	}

	public function get(){
		$args = func_get_args();
		if(array_key_exists(0, $args)){
			if(array_key_exists($args[0], $this -> data)){
				return $this -> data[$args[0]];
			}else return null;
		}else return $this -> data;
	}
	
	public function getHttpCode(){
		$httpcode = $this -> get(0);
		if(preg_match("#([0-9]{3,3})\s+#",$httpcode,$match)){
			return $match[1];
		} else {
			return 0;
		}
	}

	public function getCookie(){
		if(!$this -> cookie){
			$this -> cookie = new HttpFsockopen_Cookie(
				$this -> get('Set-Cookie')
			);
		}
		return $this -> cookie;
	}
}

class HttpFsockopen_Cookie{

	protected $raw;
	protected $header;
	protected $arr;

	public function __construct($raw){
		$this -> raw = $raw;
		$this -> header = null;
		$this -> arr = null;
	}
	public function asHeader(){
		if(is_null($this -> header)){
			if(!empty($this -> raw)){
				if(is_array($this -> raw)){
					$header_cookie = '';
					foreach($this -> raw as $cookie){
						$header_cookie .= $this -> processCookie($cookie);
					}
				}else{
					$header_cookie = $this -> processCookie($this -> raw);
				}
				$this -> header = $header_cookie;
			}else {
				$this -> header = '';
			}
		}
		return $this -> header;
	}
	
	public function combine($object){
		$current = $this -> asArray();
		$other = $object -> asArray();
		foreach($other as $key => $value ){
			$current[$key] = $value;
		}
		$new = array();
		foreach($current as $key => $value ){
			$current[] = $key."=".$value.";";
		}
		return new HttpFsockopen_Cookie($current);
	}
	
	public function asArray(){
		if(is_null($this -> arr)){
			$header = $this -> asHeader();
			if($header){
				$cookies = preg_split('#\s*;\s*#i',$header);
				$buffer = array();
				foreach($cookies  as $line){
					if(empty($line)) continue;
					preg_match('/^\s*([^=]+)\s*=\s*(.*)\s*$/',$line, $output);
					$buffer[$output[1]] = $output[2];
				}
				$this -> arr = $buffer;
			}else 
			$this -> arr = array();
		}
		return $this -> arr;
	}
	
	private function processCookie($cookie){
		
		if(preg_match('#^([^;]+);#i', $cookie, $result)){
			return $result[1].'; ';
		}
	}
}
?>