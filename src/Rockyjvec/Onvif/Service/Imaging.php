<?php namespace App\Onvif;

class Imaging extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/imaging/wsdl';
}

