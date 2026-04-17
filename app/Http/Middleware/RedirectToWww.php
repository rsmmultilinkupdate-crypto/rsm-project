<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectToWww
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
     
        if (!$request->is('www.*')) {
            return redirect()->to('https://'. $request->getHttpHost() . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
