<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\Debug\ShouldntReport;
use Symfony\Component\HttpFoundation\Response;

/**
 * 請求參數驗證異常處理
 */
class FormRequestValidationException extends Exception implements ShouldntReport
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
        return  response()->json(data: ['message' => $this->getMessage()], status: Response::HTTP_BAD_REQUEST);
    }
}
