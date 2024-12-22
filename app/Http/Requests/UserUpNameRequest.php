<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserUpNameRequest extends BaseRequest
{
    protected function childRules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
        ];
    }
}
