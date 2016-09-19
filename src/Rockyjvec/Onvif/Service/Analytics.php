<?php namespace App\Onvif;

class Analytics extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/analytics/wsdl';
}

