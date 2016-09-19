<?php namespace Rockyjvec\Onvif\Service;

use Rockyjvec\Onvif\Soap\SoapClient;

class Device extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/device/wsdl';
}

