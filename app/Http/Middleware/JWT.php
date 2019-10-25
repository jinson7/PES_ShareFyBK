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
        /*
        $user = User::where('token_password', $request->token)->first();
        dd($user);
        if( $user !== null ){
            if($user->password === "" || $user->password === null ){
                $firebase = new FirebaseController();
                $firebase->verifyIdToken('');
            }else{
                JWTAuth::parseToken()->authenticate();
            }
        }
        */
        
        JWTAuth::parseToken()->authenticate();
        return $next($request);
    }
}
