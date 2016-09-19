<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class AnalyticsDevice extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/analyticsdevice/wsdl';
}

