<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class Admin
{

    /**
     * Handle an incoming request. Check is User admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	try {
        $token = JWTAuth::parseToken();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
		return \Redirect::route('admin.login.get')->with('error', 'Token is Invalid');
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
		$token = JWTAuth::refresh($token);
		JWTAuth::setToken($token);
		$user = JWTAuth::authenticate($token);
//		return \Redirect::route('admin.login.get')->with('error', 'Token is Expired');
            }else{
		return \Redirect::route('admin.login.get')->with('error', 'Something is wrong');
            }
        }
        return $next($request);
    }
}
