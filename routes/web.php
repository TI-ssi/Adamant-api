<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->group(['prefix' => 'v1'], function () use ($router) {
    //authenticated user by oauth2
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        //general route
        $router->get('/', ['uses' => 'ServiceAvailabilityController@getAuthResponse']);
        $router->post('/', ['uses' => 'ServiceAvailabilityController@postAuthResponse']);

        $router->get('/AuthUser', ['uses' => 'UserController@getAuthUser']);
        $router->put('/user', ['uses' => 'UserController@updateUser']);
        
        $router->group(['prefix' => 'user'], function () use ($router) {
           $router->get('/', ['uses' => 'UserController@getAuthUser']);
           
        });
               
        
        $resources = [];
        foreach ($resources as $resource)
        {
          $router->group(['prefix' => $resource], function () use ($router, $resource) {
            $router->get('/', ['uses' => ucfirst($resource).'Controller@index']);
            $router->post('/', ['uses' => ucfirst($resource).'Controller@store']);
            $router->get('/{id}', ['uses' => ucfirst($resource).'Controller@show']);
            $router->put('/{id}', ['uses' => ucfirst($resource).'Controller@update']);
            $router->delete('/{id}', ['uses' => ucfirst($resource).'Controller@destroy']);
          });
        }

        $router->post('/blog/{title}/comment', ['uses' => 'PostsController@sendComment']);
        
    });

    //authenticated system by clientCredential
    $router->group(['middleware' => ['clientAuth', 'oauthClientId']], function () use ($router) {
        $router->post('/register', ['uses' => 'UserController@createUser']);
    });
});

//public call
$router->get('/', ['uses' => 'ServiceAvailabilityController@getResponse']);
$router->post('/', ['uses' => 'ServiceAvailabilityController@postResponse']);
