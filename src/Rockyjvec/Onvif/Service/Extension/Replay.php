<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class Replay extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/replay/wsdl';
}

