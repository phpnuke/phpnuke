<?php

/*
**
** Author:		Robert Mahan
** Email:		bjtexas@swbell.net
** Version:     2.0.0
**
** Usage:
**
**  	filename1 = 'whitelist filename';
**  	filename2 = 'blacklist filename';
**
**  	$ip = '192.168.0.1';
**  	$list = new IpBlockList( filename1, filename2 );
**  	boolean $result = $list->ipPass( $ip );
**  	$msg = $list->message();
**  	$status = $list->status();
**	
*/
/*
**  Exceptions - 
**
**  Ex_ipversionmismatch  -  thrown if an _And or _Cmp is
**  attempted between an IPv4 and IPv6 address.  
*/

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class Ex_ipversionmismatch extends Exception {
    public function __construct( $message = null, $code = 0, $previous = null ) {
        parent::__construct($message, $code, $previous);
        if ( !$this->message ) {
            $this->message = "Both IPs must be IPv4 or IPv6";
        }
        $this->message = __CLASS__ ." :[{$this->file}:{$this->line}] {$this->message}\n";
    }
}

/*
**  Ex_invalidipaddress  -  thrown if an IP address is not a
**  valid IPv4 or IPv6 address.
*/
class Ex_invalidipaddress extends Exception {

    public function __construct( $message = null, $code = 0, $previous = null ) {
        parent::__construct($message, $code, $previous);
        if ( !$this->message ) {
            $this->message = "Requires valid IPv4 or IPv6 address";
        }
        $this->message = __CLASS__ ." :[{$this->file}:{$this->line}] {$this->message}\n";
    }
}

/*
**  Ex_invalidipobject  -  thrown if a operation is attempted on a
**  non-IPObj class object.
*/
class Ex_invalidipobject extends Exception {

    public function __construct( $message = null, $code = 0, $previous = null ) {
        parent::__construct($message, $code, $previous);
        if ( !$this->message ) {
            $this->message = "Invalid IPObj class";
        }
        $this->message = __CLASS__ ." :[{$this->file}:{$this->line}] {$this->message}\n";
    }
}

/*
**  Ex_invalidipv4address  -  thrown if an operation intended for
**  an IPv4 IPObject is attempted on an non-IPv4 IPObj.  
*/
class Ex_invalidipv4address extends Exception {

    public function __construct( $message = null, $code = 0, $previous = null ) {
        parent::__construct($message, $code, $previous);
        if ( !$this->message ) {
            $this->message = "Invalid IPv4 Object";
        }
        $this->message = __CLASS__ ." :[{$this->file}:{$this->line}] {$this->message}\n";
    }
}

/*
**  Ex_invalidipv6address  -  thrown if an operation intended for
**  an IPv6 IPObject is attempted on an non-IPv6 IPObj.  
*/
class Ex_invalidipv6address extends Exception {

    public function __construct( $message = null, $code = 0, $previous = null ) {
        parent::__construct($message, $code, $previous);
        if ( !$this->message ) {
            $this->message = "Invalid IPv6 Object";
        }
        $this->message = __CLASS__ ." :[{$this->file}:{$this->line}] {$this->message}\n";
    }
}

/*
** class IPObj( <IP Address string> )
**
**      An IP class object that can take an IPv4 or IPv6 address
**      string and create an object such that IPv4 and IPv6 
**      addresses can be handled in the same manner.  IPv4 and
**      IPv6 objects each have separate address spaces. An attempt
**      to create an IPObj using an invalid address string will
**      throw an Ex_invalidipaddress Exception.
*
**  Methods:
**      integer _Cmp( IPObj $ipobj )
**          compares $this IPObj with another IPObj of the same
**          version. Returns 1 if $this IPObj is greater than the
**          specified IPObj, Returns 0 if the IPObjs are equal, and
**          returns -1 if the specified IPObj is greater than $this 
**          IPObj. An attempt to compare an IPv4 address with an
**          IPv6 address will throw an Ex_ipversionmismatch Exception.
**
**      string _And( IPObj $ipobj )
**          Returns a string representing anding the Internal 
**          representations. An attempt to and an IPv4 address with an
**          IPv6 address will throw an Ex_ipversionmismatch Exception.
*/

class IPObj {

    private $IPv4Value = NULL;
    private $IPv6Value = NULL;
    private $IArrValue = array();    // 8 integers

	public function __construct( $Ip ) {
        // Reformat IPv4 address to look like IPv6
		if ( filter_var( $Ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
            $this->IPv4Value = $Ip;         # save IPv4 string
			$this->ipv4tov6( $Ip );   # converted to IPv6 format
		}
		// check and save IPv6 format string
		elseif ( filter_var( $Ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 )) {
			$this->IPv6Value = $Ip;
		}
		else {
			throw new Ex_invalidipaddress( "Requires valid IPv4 or IPv6 address ({$Ip})" );
		}		
		$this->toIntArray();
	}
	
	public function __destruct() {
	}
	
	public function __toString() {
        if ( isset( $this->IPv4Value ) ) {
            return $this->IPv4Value;
        }
        return $this->IPv6Value;
    }
	
    public function _Cmp( $IPobj ) {
        if ( !is_a( $IPobj, 'IPObj' ) ) {
            throw new Ex_invalidipobject( null );
        }
        if ((isset( $this->IPv4Value ) && isset( $IPobj->IPv4Value )) ||
            (!isset( $this->IPv4Value ) && !isset( $IPobj->IPv4Value )) ) {
            
            if ( $this->IArrValue != $IPobj->IArrValue ) {

                for( $i = 0; $i < 8; $i++ ) {
                    if ( $this->IArrValue[$i] < $IPobj->IArrValue[$i] ) {
                        return -1;
                    }
                    if ( $this->IArrValue[$i] > $IPobj->IArrValue[$i] ) {
                        return 1;
                    }
                }
            }
            return 0;
        }                  
        else {
            throw new Ex_ipversionmismatch( "Both IPs must be IPv4 or IPv6 ({$this}),({$IPobj})");
        }
    }
    
    public function _And( $IPobj ) {
        if ( !is_a( $IPobj, 'IPObj' ) ) {
            throw new Ex_invalidipobject( null );
        }
        if ((isset( $this->IPv4Value ) && isset( $IPobj->IPv4Value )) ||
            (!isset( $this->IPv4Value ) && !isset( $IPobj->IPv4Value )) ) {
            for ( $i = 0; $i < 8; $i++ ) {
                $arr[] = $this->IArrValue[$i] & $IPobj->IArrValue[$i];
            }
        }
        else {
            throw new Ex_ipversionmismatch( "Both IPs must be IPv4 or IPv6 ({$this}),({$IPobj})");
        }
        return $arr;        
    }
    
    private function ipv4tov6() {
		// This tells IPv6 it has an IPv4 address
		if ( !isset( $this->IPv4Value ) ) {
			throw new Ex_invalidipv4address( "({$Ip}) Requires valid IPv4 address" );
		}
		
		static $Mask = '::ffff:';
		$Ip = $this->IPv4Value; 
		$IPv6 = (strpos($Ip, '::') === 0);
		$IPv4 = (strpos($Ip, '.') > 0);

		if (!$IPv4 && !$IPv6) {
			throw new Ex_invalidipv4address( "({$Ip}) Requires valid IPv4 address" );
		}
		if ($IPv6 && $IPv4) {
			$Ip = substr($Ip, strrpos($Ip, ':')+1); // Strip IPv4 Compatibility notation
		}
		elseif (!$IPv4) {
			return $Ip; // Seems to be IPv6 already?
		}
		$Ip = array_pad(explode('.', $Ip), 4, 0);
		if (count($Ip) > 4) {
			throw new Ex_invalidipv4address( "({$Ip}) Requires valid IPv4 address" );
		}
		for ($i = 0; $i < 4; $i++) { 
			if ($Ip[$i] > 255) {
                throw new Ex_invalidipv4address( "({$Ip}) Requires valid IPv4 address" );
			}
		}

		$Part7 = base_convert(($Ip[0] * 256) + $Ip[1], 10, 16);
		$Part8 = base_convert(($Ip[2] * 256) + $Ip[3], 10, 16);
		$this->IPv6Value = $Mask.$Part7.':'.$Part8;
    }
    
    /*
    **  Converts an IP Address, either IPv4 converted to an IPv6
    **  type format or an IPv6, to an array of 8 integers used for
    **  operating on the address.  Also bypasses issues with 32bit
    **  integer found on some platforms.
    */
    private function toIntArray() {
        $this->expand();
        $arr = explode( ':', $this->IPv6Value );
        if ( count( $arr ) != 8 ) {
            throw new Ex_invalidipv6address( "({$this->IPv6Value}) too short" );
        }
        foreach( $arr as $a ) {
            $l[] = hexdec( $a );
        }
        $this->IArrValue = $l;  
    }
    
    /*
	**  Replaces IPv6 shorthand '::' with the appropriate 
	**  number of ':0's
	*/
	private function expand() {
		$Ip = $this->IPv6Value;
		if (strpos($Ip, '::') !== false) {
			$Ip = str_replace('::', str_repeat(':0', 8 - substr_count($Ip, ':')).':', $Ip);
        }
		if (strpos($Ip, ':') === 0) {
            $Ip = '0'.$Ip;
        }
		$this->IPv6Value = $Ip;
	}   
}

/*
**  class IpList( <listfilename> );
**
**      Must provide the filename of the list of IP ranges to be used.
**      An exception will be thrown if the file can not be red.
**
**  Methods:
**      boolean is_inlist( string <$ip > );
**          Returns true if the IP is found in the list, false if the
**          IP is not found in the list.
**
**      string message();
**          Returns a string containing the range specification matching
**          the last IP found in the list.
**
**      array(string) iplist();
**          Returns an array containing the range specifications read
**          from the list file. Used for testing.
**
*/
class IpList {

	private $iplist = array();
	private $ipfile = "";

	public function __construct( $list, $is_file = true ) {
		$contents = array();
		if($is_file)
		{
			$this->ipfile = $list;
			$lines = $this->read( $list );
			foreach( $lines as $line ) {
				$line = trim( $line );
				# remove comment and blank lines
				if ( empty($line ) ) {
					continue;
				}
				if ( $line[0] == '#' ) {
					continue;
				}
				# remove on line comments
				$temp = explode( "#", $line );
				$line = trim( $temp[0] );
				# create content array
				$contents[] = $this->normal($line);
			}
		} else {
			if(!empty($list)) {
				foreach( $list as $line ) {
					# create content array
					$contents[] = $this->normal($line);
				}
			}
		}
		
		$this->iplist = $contents;
	}
	
	public function __destruct() {
	}
	
	public function __toString() {
		return implode(' ',$this->iplist);
	}
	
	public function is_inlist( $ip ) {
		$retval = false;		
		foreach( $this->iplist as $ipf ) {
            try {
                $retval = $this->ip_in_range( $ip, $ipf );
                if ($retval === true ) {
                    $this->range = $ipf;
                    break;
                }
            }
            catch ( Ex_ipversionmismatch $e ) {
                continue;
            }
		}
		return $retval;
	}
	
	/*
	** public function that returns the ip list array
	*/
	public function iplist() {
		return $this->iplist; 
	}
	
	/*
	*/
	public function message() {
		return $this->range;
	}
		
	/*
	** private function that reads the file into array
	*/
	private function read( $fname ) {
		try {
			$file = file( $fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES  );
		}
		catch( Exception $e ) {
			throw new Exception( "{$fname}: {$e->getmessage()}");
		}
		return $file;
	}
	
	private function ip_in_range( $ip, $range ) {
        $ipadd = new IPObj( $ip );
        if ( strpos($range, '/') !== false ) {
            // IP/NETMASK format
            list( $base, $netmask ) = explode('/', $range, 2 );
            $ipbase = new IPObj( $base );
            if (( strpos( $netmask, '.' ) !== false ) || 
                ( strpos( $netmask, ':' ) !== false )) {
                $netmask = str_replace( '*', '0', $netmask );
                
            }
            else {
                if ( strpos( $base, '.') !== false ) {
                    $n = $netmask / 8;
                    $m = $netmask % 8;
                    $r = array_pad( array(), 4, 0);
                    for ( $i = 0; $i < $n; $i++ ) {
                        $r[$i] = 255;
                    }
                    # echo '$m = '.$m.'<br/>';
                    if ( $m > 0 ) {
                        $r[$n] = ~( pow( 2, ( 8 - $m )) - 1 );
                    }
                    for ( $i = 0; $i < count( $r ); $i++ ) {
                        $r[ $i ] = (string)$r[$i];
                    }
                    $netmask = implode( '.', $r );
                }
                else {
                    $n = $netmask / 16;
                    $m = $netmask % 16;
                    $r = array_pad( array(), 8, 0 );
                    for ( $i = 0; $i < $n; $i++ ) {
                        $r[$i] = 0xffff;
                    }
                    if ( $m > 0 ) {
                        $r[$n] = ~( pow( 2, ( 16 - $m )) - 1 );
                    }
                    for ( $i = 0; $i < count( $r ); $i++ ) {
                        $r[$i] = dechex( $r[$i] );
                    }
                    $netmask = implode( ':', $r );
                }
            }
            $ipnetmask = new IPObj( $netmask );
            return ( $ipadd->_And( $ipnetmask ) == $ipbase->_And( $ipnetmask ) );
        }
        else {
            if ( strpos( $range, '*' ) !== false ) {
                // 255.255.*.* format
                $low = str_replace( '*', '0', $range );
                $high = str_replace( '*', '255', $range );
                $range = $low.'-'.$high;
            }
            if ( strpos( $range, '-') !== false ) {
                list( $low, $high ) = explode( '-', $range , 2);
                $iplow = new IPObj( $low );
                $iphigh = new IPObj( $high );
                return (( $ipadd->_Cmp( $iplow ) != -1) && ( $ipadd->_Cmp( $iphigh ) != 1));
            }
        }
        $iprange = new IPObj( $range );
        return ( $ipadd->_Cmp( $iprange ) == 0 );
    }
             
	/*
	** private function that excess spaces from within the string.
	*/
	private function normal( $range ) {
		return str_replace( ' ', '', $range );
	}	
}

/*
**  class IpBlockList( <whitelistfile>, <blacklistfile> );
**
**      If whitelist and blacklist filenames are not provided, they will
**      default to '_whitelist.dat' and '_blacklist.dat'.  If either
**      file is not present, an Exception will be thrown.  See the 
**      example '_whitelist.dat' and '_blacklist.dat' files for the
**      proper format. 
** 
**  methods:
**      boolean ipPass( <ipaddress> ) 
**          Returns true if address is found in the whitelist or not
**          found in either the whitelist or blacklist. Returns false
**          if address is found in the blacklist.
**
**      string message()
**          Returns a message string specifying the reason for the result
**          of the last call to ipPass() which can be used for logging.
**
**      integer status()
**          Returns an integer specifying the result of the last call to
**          ipPass().  Returns -1 if the last IP was found in the blacklist,
**          0 if the last IP was not found in either the whitelist or
**          blacklist, 1 if the last IP was found in the whitelist.
** 
*/
class IpBlockList {

    private $statusid = array( 'negative' => -1, 'neutral' => 0, 'positive' => 1 );

	private $whitelist = array();
	private $blacklist = array();
	private $message   = NULL;
	private $status    = NULL;

	public function __construct( $iplist_array = array(), $whitelistfile = '_whitelist.dat', $blacklistfile = '_blacklist.dat' ) {
		
		if(is_array($iplist_array) && !empty($iplist_array))
		{
			$this->whitelistfile =(isset($iplist_array['whitelistfile']) && !empty($iplist_array['whitelistfile'])) ? $iplist_array['whitelistfile']:"";
			$this->blacklistfile =(isset($iplist_array['blacklistfile']) && !empty($iplist_array['blacklistfile'])) ? $iplist_array['blacklistfile']:"";
			
			$this->whitelist = new IpList( $this->whitelistfile, false );
			$this->blacklist = new IpList( $this->blacklistfile, false );

		}
		else
		{
			$this->whitelistfile = $whitelistfile;
			$this->blacklistfile = $blacklistfile;
			
			$this->whitelist = new IpList( $whitelistfile );
			$this->blacklist = new IpList( $blacklistfile );
		}
	}
	
	public function __destruct() { 
	}
	
	public function ipPass( $ip ) {
		$retval = False;
		
		if ( !filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) &&
             !filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
            throw new Ex_invalidipaddress( 'Requires valid IP address ({$ip})' );
		}
		
		if ( $this->whitelist->is_inlist( $ip ) ) {
			// Ip is white listed, so it passes
			$retval = True;
			$this->message = "{$ip} is whitelisted by {$this->whitelist->message()}";
			$this->status = $this->statusid['positive'];
		}
		else if ( $this->blacklist->is_inlist( $ip ) ) {
			$retval = False;
			$this->message = "{$ip} is blacklisted by {$this->blacklist->message()}";
			$this->status = $this->statusid['negative'];
		}
		else {
			$retval = True;
			$this->message = "{$ip} is unlisted";
			$this->status = $this->statusid['neutral'];
		}
		return $retval;
	}
	
	public function message() {
		return $this->message;
	}

	public function status() {
        return $this->status;
    }
    
	public function append( $type, $ip, $comment = "" ) {
        if ($type == 'WHITELIST' ) {
            $retval = $this->whitelistfile->append( $ip, $comment );
        }
        elseif( $type == 'BLACKLIST' ) {
            $retval = $this->blacklistfile->append( $ip, $comment );
        }
        else {
            $retval = false;
        }
	}

	public function filename( $type, $ip, $comment = "" ) {
        if ($type == 'WHITELIST' ) {
            $retval = $this->whitelistfile->filename( $ip, $comment );
        }
        elseif( $type == 'BLACKLIST' ) {
            $retval = $this->blacklistfile->filename( $ip, $comment );
        }
        else {
            $retval = false;
        }
	}
}

?>
