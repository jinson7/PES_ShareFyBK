<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;
use App\Http\Controllers\FirebaseController;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $firebase = new FirebaseController();
        $firebase->index();
        //JWTAuth::parseToken()->authenticate();
        return $next($request);
    }
}
