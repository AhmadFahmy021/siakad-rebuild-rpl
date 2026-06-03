<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessOrtu
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd("Middleware Ortu");
        if (!session('ortu_login') ) {
            return redirect('ortu/login');
        }

        return $next($request);
    }
}
