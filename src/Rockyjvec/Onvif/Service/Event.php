<?php namespace App\Onvif;

class Event extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/events/wsdl';
}

