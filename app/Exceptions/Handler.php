<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'amr_password',
        'password_again',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        echo $request->fullUrl();

        if ($request->expectsJson())
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if (is_numeric(strpos($request->fullUrl(), 'admin')))
        {
            return redirect()->guest(action('Auth\LoginController@showAdminLoginForm'));
        }
        if (is_numeric(strpos($request->fullUrl(), 'shop')))
        {
            return redirect()->guest(action('Auth\LoginController@showShopLoginForm'));
        }
        return redirect()->guest(action('PagesController@home'));
    }
}
