<?php namespace App\Onvif;

class SoapClient extends \SoapClient
{
    protected $api_uri = "";
    protected $api_location = "";
    protected $api_username = "";
    protected $api_password = "";

    public function __construct($location, $username, $password)
    {
        $options = [
            'location' => $location,
            'uri' => $this->api_uri,
            'trace'       => true,
            'exceptions'  => true,
          //  'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
       /*     'keep_alive'  => true,
            'connection_timeout'  => 100,
            'soap_version' => SOAP_1_2,
            'user_agent' => 'security',*/
            'features' => SOAP_USE_XSI_ARRAY_TYPE|SOAP_WAIT_ONE_WAY_CALLS,
        ];

        parent::__construct(null, $options);

        $this->api_location = $location;
        $this->api_username = $username;
        $this->api_password = $password;

    }

    private function soapize($ar)
    {
        $vars = [];
        foreach($ar as $key => $val)
        {
            if(is_array($val))
            {
                $vars[] = new \SoapParam($this->soapize($val), 'ns1:' . $key);
            }
            else
            {
                $vars[] = new \SoapParam($val, 'ns1:' . $key);
            }
        }
        return $vars;
    }

    public function __call($method, $args)
    {
        $vars = [];

        if(isset($args[0]))
        {
            $vars = $this->soapize($args[0]);
        }

        $this->__setSoapHeaders([new WsseAuthHeader($this->api_username, $this->api_password), new AddressingHeaderTo($this->api_location), new AddressingHeaderAction($this->api_location . "#" . $method)]);
        return parent::__call($method, $vars);
    }

    public function __soapCall($method, $args, $options = [], $input_headers = [], &$output_headers = [])
    {
        $this->__setSoapHeaders([new WsseAuthHeader($this->api_username, $this->api_password), new AddressingHeaderTo($this->api_location), new AddressingHeaderAction($this->api_location . "#" . $method)]);
        return parent::__soapCall($method, $args, $options, $input_headers, $output_headers);
    }
}

