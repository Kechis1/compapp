<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check())
        {
            switch ($guard)
            {
                case 'admin':
                    return redirect()->action('AdminController@index');
                break;
                case 'shop':
                    return redirect()->action('ShopController@index');
                break;
                default:
                    return redirect()->action('PagesController@home');
                break;
            }
        }

        return $next($request);
    }
}
