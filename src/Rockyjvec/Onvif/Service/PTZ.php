<?php namespace Rockyjvec\Onvif\Service;

use Rockyjvec\Onvif\Soap\SoapClient;

class PTZ extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/ptz/wsdl';
}

