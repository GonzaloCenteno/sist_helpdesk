<?php
namespace App\Http\Controllers\soap;
use SoapClient;
class InstanceSoapClient extends BaseSoapController implements InterfaceInstanceSoap
{
    public static function init()
    {
        $wsdlUrl = self::getWsdl();
        $soapClientOptions = [
            'stream_context' => self::generateContext(),
            'cache_wsdl'     => WSDL_CACHE_NONE,
            'trace' => TRUE
        ];
        return new SoapClient($wsdlUrl, $soapClientOptions);
    }
}