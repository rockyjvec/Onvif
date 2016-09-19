<?php namespace Rockyjvec\Onvif\Service\Extension;

use Rockyjvec\Onvif\Soap\SoapClient;

class Replay extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/replay/wsdl';
}

