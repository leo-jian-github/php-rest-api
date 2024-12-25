<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\Debug\ShouldntReport;
use Symfony\Component\HttpFoundation\Response;

/**
 * API 交互令牌失效異常處理
 */
class TokenAuthException extends Exception implements ShouldntReport
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        return  response()->json(data: ['message' => "Token expired"], status: Response::HTTP_FORBIDDEN);
    }
}
