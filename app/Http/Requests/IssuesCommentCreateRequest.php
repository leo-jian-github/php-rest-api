<?php

namespace App\Http\Requests;


class IssuesCommentCreateRequest extends BaseRequest
{
    protected function childRules(): array
    {
        return [
            'issues_id' => 'required|integer',
            'content' => 'required|string',
        ];
    }
}
