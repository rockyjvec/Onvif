<?php namespace App\Onvif;

class PTZ extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/ptz/wsdl';
}

