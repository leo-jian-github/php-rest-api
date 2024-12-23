<?php

namespace App\Http\Requests;


class IssueListRequest extends BaseRequest
{
    protected function childRules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'page_size' => 'required|integer|min:1|max:100',
        ];
    }
}
