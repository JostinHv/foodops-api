<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Las cookies que no deben ser encriptadas.
     *
     * @var array<int, string>
     */
    protected $except = [
        'access_token',
        'refresh_token'
    ];

    protected static $neverEncrypt = [
        'access_token',
        'refresh_token'
    ];
}
