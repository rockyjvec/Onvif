<?php namespace Rockyjvec\Onvif\Service;

use Rockyjvec\Onvif\Soap\SoapClient;

class Imaging extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/imaging/wsdl';
}

