<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use App\Services\ActivationService;

class checkAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && Auth::user()->block_user){
            if( Auth::user()->active ){
                return $next($request);
            }else{
                return redirect('email/verify');
            }           
        }else{
            Auth::logout();
            return redirect('login')->with('error_login','Tài khoản không khả dụng');
        }
        
    }
}
