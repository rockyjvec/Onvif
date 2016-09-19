<?php namespace App\Onvif;

class Device extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/device/wsdl';
}

