<?php namespace App\Onvif\Extension;

use App\Onvif\SoapClient;

class Search extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/search/wsdl';
}

