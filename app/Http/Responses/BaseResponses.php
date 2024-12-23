<?php

namespace App\Http\Responses;


class BaseResponses
{
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
