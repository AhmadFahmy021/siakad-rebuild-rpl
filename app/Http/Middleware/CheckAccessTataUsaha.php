<?php

namespace App\Http\Middleware;

use App\Models\TataUsaha;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessTataUsaha
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isTataUsaha = TataUsaha::where('user_id', Auth::user()->id)->exists(); // Ganti dengan logika pengecekan akses yang sesuai
        if ($isTataUsaha) {
            return $next($request);
        } else {
            return response(view('error.pages-403'), 403);
        }
        // return $next($request);
    }
}
