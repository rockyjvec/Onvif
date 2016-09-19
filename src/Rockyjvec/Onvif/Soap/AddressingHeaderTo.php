<?php namespace Rockyjvec\Onvif\Soap;

class AddressingHeaderTo extends \SoapHeader 
{
    private $ans = 'http://www.w3.org/2005/08/addressing';

    function __construct($to) 
    {
        parent::__construct($this->ans, 'To', $to, true);
    }
}

