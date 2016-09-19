<?php namespace Rockyjvec\Onvif\Soap;

class AddressingHeaderAction extends \SoapHeader 
{
    private $ans = 'http://www.w3.org/2005/08/addressing';

    function __construct($action) 
    {
        parent::__construct($this->ans, 'Action', $action, true);
    }
}

