<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;
use App\Http\Controllers\FirebaseController;
use Carbon\Carbon;

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
        JWTAuth::parseToken()->authenticate();
        return $next($request);
        /*
        $user = \App\User::where('token_password', $request->token)->first();
        if($user !== null){
            if($user->password===null || $user->password===""){
                $actual_date = now();
                $token_expire = Carbon::createFromTimestamp($user->token_expire);
                if($token_expire->greaterThanOrEqualTo($actual_date)){
                    return $next($request);
                }
                return response()->json([
                    'error' => 'token is invalid'
                ], 200);
                //dd($timestamp, $date, $test);
                /*
                $client = new \Google_Client();
                if ($client->verifyIdToken($request->token)) {
                    return $next($request);
                } else {
                    return response()->json([
                        'error' => 'token is invalid'
                    ], 200);
                }
                */
                /*
            }else{
                JWTAuth::parseToken()->authenticate();
                return $next($request);
            }
        }else{
            return response()->json([
                'error' => 'token is invalid'
            ], 200);
        }
        */
    }
}
