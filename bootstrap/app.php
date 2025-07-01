<?php

use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\ApiCheckRole;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\ThrottleRequests;
use App\Http\Middleware\WebAuthenticate;
use App\Http\Middleware\WebCheckRole;
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
        $middleware->encryptCookies(['access_token', 'refresh_token']);
//        $middleware->api(
//            append: [ApiAuthenticate::class,]
//        )->alias(['auth' => ApiAuthenticate::class]
//        );
//            append: [ThrottleRequests::class]
//        )->alias(['throttle' => ThrottleRequests::class]
        $middleware->alias(['throttle' => ThrottleRequests::class]);
        $middleware->alias(['auth.api' => ApiAuthenticate::class]);
        $middleware->alias(['auth.web' => WebAuthenticate::class]);
        $middleware->alias(['auth.check' => CheckAuth::class]);
        $middleware->alias(['role.api' => ApiCheckRole::class]);
        $middleware->alias(['role.web' => WebCheckRole::class]);
    })
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['middleware' => [ApiAuthenticate::class]],
    )
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
