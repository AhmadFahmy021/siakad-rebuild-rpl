<?php

use App\Http\Middleware\CheckAccessAdmin;
use App\Http\Middleware\CheckAccessGuru;
use App\Http\Middleware\CheckAccessSiswa;
use App\Http\Middleware\CheckAccessTataUsaha;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.siswa' => CheckAccessSiswa::class,
            'check.admin' => CheckAccessAdmin::class,
            'check.tu' => CheckAccessTataUsaha::class,
            'check.guru' => CheckAccessGuru::class,
            // 'check.orangtua' => CheckAccessOrangTua::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
