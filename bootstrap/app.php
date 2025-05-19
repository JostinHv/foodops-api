<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ThrottleRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->api(
//            append: [Authenticate::class,]
//        )->alias(['auth' => Authenticate::class]
//        );
//        $middleware->api(
//            append: [ThrottleRequests::class]
//        )->alias(['throttle' => ThrottleRequests::class]
//        );
        $middleware->alias(['throttle' => ThrottleRequests::class]);
        $middleware->alias(['auth' => Authenticate::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
