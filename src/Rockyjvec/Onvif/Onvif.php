<?php namespace Rockyjvec\Onvif;

use Rockyjvec\Onvif\Service\Device;

class Onvif
{
    // List of capabilities mapping from variables
    static public $caps = [
        'analyics' => 'Analytics', 
        //'device' => 'Device',
        'events' => 'Events', 
        'imaging' => 'Imaging', 
        'media' => 'Media', 
        'ptz' => 'PTZ'
    ];

    // List of extensions mapping from variables
    static public $exts = [
        'deviceio' => 'DeviceIO',
        'display' => 'Display',
        'recording' => 'Recording',
        'search' => 'Search',
        'replay' => 'Replay',
        'receiver' => 'Receiver',
        'analyticsdevice' => 'AnalyticsDevice'
    ];

    // caps
    public $analytics = null;
    public $device = null;
    public $events = null;
    public $imaging = null;
    public $media = null;
    public $ptz = null;

    // exts
    public $deviceio = null;
    public $display = null;
    public $recording = null;
    public $search = null;
    public $replay = null;
    public $receiver = null;
    public $analyticsdevice = null;

    public function __construct($location, $username, $password)
    {
        $this->device = new Device($location, $username, $password);

        $capabilities = $this->device->GetCapabilities(null);

        foreach(static::$caps as $var => $name)
        {
            if(isset($capabilities->$name))
            {
                $class = "Rockyjvec\\Onvif\\Service\\" . $name;
                $this->$var = new $class($capabilities->$name->XAddr, $username, $password);
            }
        }

        if(isset($capabilities->Extension))
        {
            foreach(static::$exts as $var => $name)
            {
                if(isset($capabilities->Extension->$name))
                {
                    $class = "Rockyjvec\\Onvif\\Service\\Extension\\" . $name;
                    $this->$var = new $class($capabilities->Extension->$name->XAddr, $username, $password);
                }
            }
        }
    }
    
	public static function unparse_url($parsed_url) { 
		// http://php.net/manual/pt_BR/function.parse-url.php / thomas at gielfeldt dot com
		$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
		$host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
		$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
		$user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
		$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
		$pass     = ($user || $pass) ? "$pass@" : ''; 
		$path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
		$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
		$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
		return "$scheme$user$pass$host$port$path$query$fragment"; 
	} 
    
	public static function Discovery($custom_options = []) {
		
		$options = array_replace([
			'timeout'    => 2,
			'bindip'     => '0.0.0.0',
			'mcastip'    => '239.255.255.250',
			'mcastport'  => 3702,
			'hidedup'    => true
		], $custom_options);
		
		
		$MessageID = sprintf( 'uuid:%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
				mt_rand( 0, 0xffff ),
				mt_rand( 0, 0x0fff ) | 0x4000,
				mt_rand( 0, 0x3fff ) | 0x8000,
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
			);
		
		$payload = '<?xml version="1.0" encoding="UTF-8"?>
				<e:Envelope 
					xmlns:e="http://www.w3.org/2003/05/soap-envelope" 
					xmlns:w="http://schemas.xmlsoap.org/ws/2004/08/addressing" 
					xmlns:d="http://schemas.xmlsoap.org/ws/2005/04/discovery" 
					xmlns:dn="http://www.onvif.org/ver10/network/wsdl">
					
						<e:Header>
							<w:MessageID>' . $MessageID . '</w:MessageID>
							<w:To e:mustUnderstand="true">
								urn:schemas-xmlsoap-org:ws:2005:04:discovery
							</w:To>
							<w:Action a:mustUnderstand="true">
								http://schemas.xmlsoap.org/ws/2005/04/discovery/Probe
							</w:Action>
						</e:Header>
						
						<e:Body>
							<d:Probe>
								<d:Types>dn:NetworkVideoTransmitter</d:Types>
							</d:Probe>
						</e:Body>
				</e:Envelope>';
		
		$result = array();
		
		try {
			if(FALSE == ($sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))){
				echo('Create socket error: ['.socket_last_error().'] '.socket_strerror(socket_last_error()));
			}
			if(FALSE == @socket_bind($sock, $options['bindip'], rand(20000, 40000))){
				echo('Bind socket error: ['.socket_last_error().'] '.socket_strerror(socket_last_error()));
			}
			socket_set_option($sock, IPPROTO_IP, MCAST_JOIN_GROUP, array('group' => $options['mcastip']));
			socket_sendto($sock, $payload, strlen($payload), 0, $options['mcastip'], $options['mcastport']);

			$sock_read   = array($sock);
			$sock_write  = NULL;
			$sock_except = NULL;

			while ( socket_select( $sock_read, $sock_write, $sock_except, $options['timeout'] ) > 0 ) {
				if(FALSE !== @socket_recvfrom($sock, $response, 9999, 0, $from, $options['mcastport'])){
					if($response != NULL && $response != $payload){
						$xml = simplexml_load_string($response);
						$RelatesTo = $xml->children('SOAP-ENV', true)->Header->children('wsa', true)->RelatesTo;
						if ($RelatesTo == $MessageID) {
							$ProbeMatch = $xml->children('SOAP-ENV', true)->Body->children('d', true)->ProbeMatches->ProbeMatch;
							
							$PM = [
								'IPAddr' => $from,
								'XAddrs' => []
							];
							
							// Some chinese cameras send wrong data
							foreach ( $ProbeMatch->XAddrs as $xaddr ) {
								$url = parse_url($xaddr);
								if ( isset($url['scheme']) && isset($url['host']) ) {
									$url['host'] = $from;
									$PM['XAddrs'][] = self::unparse_url($url);
								}
							}
							
							if( $options['hidedup'] ) $result[$from] = $PM;
							else $result[] = $PM;
						}
					}
				}
			}
			
			socket_close($sock);
		} catch (Exception $e) {}
		
		sort($result);
		return $result;
	}
    
}

