<?php

namespace App\Http\Responses;

use Illuminate\Pagination\LengthAwarePaginator;


class IssueListResponses extends BaseResponses
{
    /**
     * 數據
     * @var IssueListDataResponses[] $datas
     */
    public array $datas;

    public int $total;

    public function __construct(LengthAwarePaginator  $value)
    {
        $this->datas = $value->items();
        $this->total = $value->total();
        $this->message = "SUCCESS";
    }
}

class IssueListDataResponses
{
    public int $id;
    public string $createdAt;
    public string $updatedAt;
    public string $title;
    public string $content;
    public int $userNo;
    public IssueListUserResponses $user;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->userNo = $data['user_no'];
        $this->user = $data['user'];
    }
}

class IssueListUserResponses
{
    public int $no;
    public string $account;
    public string $name;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->no = $data['no'];
        $this->account = $data['account'];
        $this->name = $data['name'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }
}
