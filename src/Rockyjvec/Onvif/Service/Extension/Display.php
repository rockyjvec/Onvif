<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class Display extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/display/wsdl';
}

