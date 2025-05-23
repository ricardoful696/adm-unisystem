<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UpdateCalendario;
use App\Http\Middleware\AtualizaDominioMiddleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(UpdateCalendario::class);
        $middleware->append(AtualizaDominioMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();