<?php

namespace App\Library\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ApiWrapper
{

    protected $client;
    protected $clientAccess;

    protected $bearerToken;

    protected $data;
    protected $headers;

    protected $requestId;

    public function __construct(){
        $this->clientAccess['geth']    = new Client(['base_uri' => config('api.geth.baseUrl')]);        
        $this->clientAccess['gethlight']    = new Client(['base_uri' => config('api.gethlight.baseUrl')]);        

        $this->requestId = 0;
        
        $this->client = 'default';
    }

    public function getAccess($apiName = 'default', $useClientCredentials = false){
        if($apiName === FALSE || $apiName === TRUE) {
            $useClientCredentials = $apiName;
            $apiName = 'default';
        }

        $this->client = $apiName;
        
        //when accessing the API without a registered user
        if($useClientCredentials === '-1'){
            $this->data['id'] = $this->requestId++;
        }elseif($useClientCredentials){
            $this->withData([
                'client_id' => config('api.'.$apiName.'.clientToken'),
                'client_secret' => config('api.'.$apiName.'.clientSecret'),
                'grant_type' => 'client_credentials'
            ]);

            $response = $this->sendTo('oauth/token', 'POST');

            $this->withHeaders([
                'Authorization' => 'Bearer '.$response['access_token']
            ]);

        }else{
            if(empty($this->bearerToken)){
                $token = $this->refresh($apiName);

                $this->bearerToken = $token;
            }

            $this->withHeaders([
                'Authorization' => 'Bearer '.$this->bearerToken
            ]);
        }

        return $this;
    }

    public function withHeaders($data = array()){
        $this->headers = $data;
        return $this;
    }

    public function withData($data = array()){
        foreach($data as $key => $val) $this->data[$key] = $val;
        return $this;
    }

    public function clearHeaders(){
        $this->headers = [];
        return $this;
    }

    public function clearData(){
        $this->data = [];
        return $this;
    }

    public function sendTo($url, $type = 'GET'){
        try{
            $response = $this->clientAccess[$this->client]->request($type, $url, [
                'headers' => $this->headers,
                'json' => $this->data,
                'form_params' => $this->data,
                //'connect_timeout' => 30
            ]);

            $body = $response->getBody();
            $content = $body->getContents();

            return json_decode($content,TRUE);

        }catch(\Exception $e){
            Log::error('Api send : '.$e->getMessage().'( '.$url.' )', $this->data);
            abort(400, $e->getMessage());
        }finally{
            $this->clearHeaders()
                 ->clearData();
        }

    }

    private function refresh($apiName = 'default'){
        try{
            if(!Session::has('crt')) throw new \Exception('CRT not found.');;

            $this->withData(array(
                'client_id' => config('api.'.$apiName.'.clientToken'),
                'client_secret' => config('api.'.$apiName.'.clientSecret'),
                'grant_type' => 'refresh_token',
                'refresh_token' => session('crt')
            ));
            $response = $this->sendTo('oauth/token', 'POST');

            if(empty($response['refresh_token']) || !empty($response['error'])) throw new \Exception('Refresh failed.');

            Session::put('crt', $response['refresh_token']);

            return $response['access_token'];
        }catch(\Exception $e){
            Log::error('Api token refresh : '.$e->getMessage());
            Session::forget('crt');
            //abort(403);
            throw $e;
        }
    }

    public function auth($username, $password){
        try{
            $this->withData(array(
                'client_id' => config('api.default.clientToken'),
                'client_secret' => config('api.default.clientSecret'),
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password
            ));

            $response = $this->sendTo('oauth/token', 'POST');

            if(empty($response) || !empty($response['error'])) throw new \Exception('Api Authentication failed.');

            Session::put('crt', $response['refresh_token']);

            return $response;
        }catch(\Exception $e){
            Log::error('Api auth : '.$e->getMessage());
            abort(403);
        }
    }


}
