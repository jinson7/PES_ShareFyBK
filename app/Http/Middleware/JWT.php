<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;
use App\Http\Controllers\FirebaseController;

use App\User;

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
        $user = \App\User::where('token_password', $request->token)->first();
        if($user !== null){
            if($user->password===null || $user->password===""){
                $client = new \Google_Client();
                if ($client->verifyIdToken($request->token)) {
                    return $next($request);
                } else {
                    return response()->json([
                        'error' => 'token is invalid'
                    ], 200);
                }
            }else{
                JWTAuth::parseToken()->authenticate();
                return $next($request);
            }
        }else{
            return response()->json([
                'error' => 'token is invalid'
            ], 200);
        }
    }
}
