<?php

namespace App\Http\Middleware;

use App\Models\Siswa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessSiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isSiswa = Siswa::where('user_id', Auth::user()->id)->exists();
        if ($isSiswa) {
            return $next($request);
        } else {
            return response(view('error.pages-403'), 403);
        }
        // return $next($request);
    }
}
