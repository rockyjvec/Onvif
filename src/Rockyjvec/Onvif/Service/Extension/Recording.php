<?php namespace Rockyjvec\Onvif\Service\Extension;

use Rockyjvec\Onvif\Soap\SoapClient;

class Recording extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/recording/wsdl';
}

