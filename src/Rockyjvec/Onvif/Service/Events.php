<?php namespace Rockyjvec\Onvif\Service;

use Rockyjvec\Onvif\Soap\SoapClient;

class Events extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/events/wsdl';
}

