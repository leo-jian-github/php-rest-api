<?php

namespace App\Http\Responses;


class IssueListResponses extends BaseResponses
{
    /**
     * 議題列表
     * @var IssueListDataResponses[] $datas
     */
    public array $datas;

    public int $total;

    /**
     * 數據
     * @param IssueListDataResponses[] $datas
     * @param int $total
     */
    public function __construct(array  $datas, int $total)
    {
        $this->datas = $datas;
        $this->total = $total;
        $this->message = "SUCCESS";
    }
}
