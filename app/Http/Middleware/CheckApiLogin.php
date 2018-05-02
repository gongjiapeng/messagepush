<?php
namespace App\Http\Middleware;
use Cache;
use Closure;
class CheckApiLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $redirectUrl = URL('wap/userlogin');
        $token = $request->session()->get('token');
        //$token = $request->input('token','');//令牌
        $userInfo = Cache::store('redis')->get($token);
        if (empty($userInfo)) {
            return redirect($redirectUrl);
        }

        return $next($request);
    }
}
