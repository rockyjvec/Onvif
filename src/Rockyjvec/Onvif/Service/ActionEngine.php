<?php namespace App\Onvif;

class ActionEngine extends \SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/actionengine/wsdl';
}

