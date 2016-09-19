<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class Receiver extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/receiver/wsdl';
}

