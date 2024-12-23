<?php

namespace App\Http\Responses;

class IssueListDataResponses
{
    public int $id;
    public string $createdAt;
    public string $updatedAt;
    public string $title;
    public string $content;
    public string $author;
    /**
     * 被分派者列表
     * @var string[] $assignee
     */
    public array $assignee;

    /**
     * 議題列表
     * @param int $id
     * @param string $title
     * @param string $content
     * @param string $createdAt
     * @param mixed $updatedAt
     * @param string $author
     * @param string[] $assignee
     */
    public function __construct(int $id, string $title, string $content,  string $createdAt, $updatedAt, string $author, array $assignee)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->author = $author;
        $this->assignee = $assignee;
    }
}
