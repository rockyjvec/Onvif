<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class Recording extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/recording/wsdl';
}

