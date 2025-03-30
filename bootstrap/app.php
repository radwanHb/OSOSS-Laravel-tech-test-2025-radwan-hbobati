<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            $message = $e->getMessage() ?: 'An unexpected error occurred';
            $errorNumber = method_exists($e, 'getErrorNumber') ? $e->getErrorNumber() : 500;

            return response()->json([
                'status' => false,
                'msg' => $message,
                'errNum' => $errorNumber
            ]);
        });
    })->create();
