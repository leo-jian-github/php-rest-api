<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueCreateRequest extends BaseRequest
{
    protected function childRules(): array
    {
        return [
            'title' => 'required|string|min:1|max:100',
            'content' => 'required|string|min:1',
            'assignee' => 'required|array',
            'assignee.*' => 'required|integer|distinct',
        ];
    }
}
