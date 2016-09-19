<?php namespace Rockyjvec\Onvif\Service;

use Rockyjvec\Onvif\Soap\SoapClient;

class Analytics extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/analytics/wsdl';
}

