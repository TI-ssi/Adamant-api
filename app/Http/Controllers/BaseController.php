<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as LumenBaseController;
use Illuminate\Support\Facades\Log;

class BaseController extends LumenBaseController
{
    public function success($data, $code = 200){
        Log::debug('Returned result : ', [json_encode(['data' => $data])]);

        return response()->json(['data' => $data], $code);
    }

    public function error($message, $code = 400, $ex = null){
        $error = ['message' => $message];

        if(!empty($ex)) $error['exception'] = $ex;

        Log::debug('Returned error : ', [json_encode($error)]);
        
        return response()->json($error, $code);
    }

}
