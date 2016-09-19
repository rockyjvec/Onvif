<?php namespace App\Onvif;

class Media extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/media/wsdl';
}

