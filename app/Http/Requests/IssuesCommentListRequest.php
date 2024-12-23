<?php

namespace App\Http\Requests;


class IssuesCommentListRequest extends BaseRequest
{
    protected function childRules(): array
    {
        return [
            'issues_id' => 'required|integer',
            'page' => 'required|integer|min:1',
            'page_size' => 'required|integer|min:1|max:100',
        ];
    }
}
