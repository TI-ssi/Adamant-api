<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Log;

class Debug
{
    private $execution_time;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        
        
        if(env('APP_DEBUG', true)){
            $this->execution_time = microtime(true);
            Log::debug( "BEGIN API REQUEST {$_SERVER['REQUEST_METHOD']} ( {$request->path()} )");
        }

        if(env('DB_DEBUG', true)) {
            DB::connection()->enableQueryLog();

            DB::listen(function ($query) {
                Log::debug( "{$query->sql} ( {$query->time} ms )", $query->bindings);
            });
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::debug( "END API REQUEST ( {$request->path()} ) executed in ".((microtime(true)-$this->execution_time)*1000).' ms');
    }
}
