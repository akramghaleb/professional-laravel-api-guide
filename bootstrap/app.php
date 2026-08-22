<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ValidationException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $errors = collect($exception->errors())
                ->flatMap(fn (array $messages, string $field) =>
                    collect($messages)->map(fn (string $message) => [
                        'status' => 422,
                        'message' => $message,
                        'source' => $field,
                    ])
                )->values()->all();

            return response()->json([
                'message' => 'Validation failed.',
                'status' => 422,
                'data' => ['errors' => $errors],
            ], 422);
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => 'The resource cannot be found.',
                'status' => 404,
                'data' => ['source' => $exception->getModel()],
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $previous = $exception->getPrevious();

            return response()->json([
                'message' => 'The resource cannot be found.',
                'status' => 404,
                'data' => ['source' => $previous instanceof ModelNotFoundException
                    ? $previous->getModel()
                    : ''],
            ], 404);
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 401,
                'data' => null,
            ], 401);
        });

        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $status = $exception->getStatusCode();

            return response()->json([
                'message' => $status >= 500 ? 'Server error.' : ($exception->getMessage() ?: 'Request failed.'),
                'status' => $status,
                'data' => null,
            ], $status, $exception->getHeaders());
        });

        $exceptions->render(function (\Throwable $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => 'Server error.',
                'status' => 500,
                'data' => null,
            ], 500);
        });
    })->create();
