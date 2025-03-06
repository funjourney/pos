<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\VerifyCsrfToken;

class Kernel extends HttpKernel
{
    /**
     * Middleware global yang selalu diterapkan ke semua request.
     */
    protected $middleware = [
        HandleCors::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * Middleware groups yang bisa dipanggil dalam routes.
     */
    protected $middlewareGroups = [
        'web' => [
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
        ],

        'api' => [
            ThrottleRequests::class . ':api',
        ],
    ];
}
