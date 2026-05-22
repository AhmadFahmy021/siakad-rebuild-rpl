<?php

namespace App\Http\Middleware;

use App\Models\Guru;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessGuru
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isGuru = Guru::where('user_id', Auth::user()->id)->exists(); // Ganti dengan logika pengecekan akses yang sesuai
        if ($isGuru) {
            return $next($request);
        } else {
            return response(view('error.pages-403'), 403);
        }
        return $next($request);
    }
}
