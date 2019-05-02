<?php

namespace App\Library\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Library\Services\ApiWrapper;

class Geth 
{

    protected $api;

    protected $method;
    protected $params;

    public function __construct(ApiWrapper $api){
        $this->params = array();
        $this->api = $api;
    }

    public function method($method){
        $this->method = $method;
        return $this;
    }

    public function params($params){
        $this->params = $params;
        return $this;
    }

    public function send(){
        return $this->api->getAccess('geth', '-1')
                       ->withHeaders(['Content-type'=> 'application/json',
                                     'Accept'=> 'application/json'])
                       ->withData(['jsonrpc' => '2.0', 'method' => $this->method, 'params' => $this->params])
                       ->sendTo('/', 'POST');
    }
}
