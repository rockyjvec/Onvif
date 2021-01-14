# Onvif IP Camera library for PHP

This library will be the basis of a Network Video Recorder (NVR) application I am writing in Laravel.  It provides a php interface to Onvif compatible cameras.  I have only tested it with the RLC-410.

It supports subscribing to motion events.

## Installation

Pull in the package through Composer.

Run:
```composer require rockyjvec/onvif```

##Usage

Device discovery:  
```
$r = Onvif::Discovery();
print_r($r);
```

You need to specify the endpoint, username, and password of the camera you want to connect to.
Here is the basic code to get the capabilities of the camera:
```
$o = new Rockyjvec\Onvif\Onvif("http://camera.hostname:8000/onvif/device_service", "username", "password");
var_dump($o->device->GetCapabilities());
```
The various services are available as properties of the Onvif class:
$o->device,
$o->media,
$o->events, 
...

The capabilities are parsed when constructing the Onvif class and services are null if the camera doesn't have that capability

For information about the available methods, etc.  See "ONVIF WSDL and XML Schemas Specifications" section on this page: http://www.onvif.org/Documents/Specifications.aspx
