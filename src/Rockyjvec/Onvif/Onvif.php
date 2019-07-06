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
}

