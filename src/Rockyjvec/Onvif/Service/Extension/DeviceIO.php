<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class DeviceIO extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/deviceIO/wsdl';
}

