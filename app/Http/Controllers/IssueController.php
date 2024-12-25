<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Issue;
use App\Models\IssuesComment;
use App\Models\IssuesAssignee;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IssueListRequest;
use function App\Http\Helpers\TokenAuth;
use App\Http\Requests\IssueCreateRequest;
use App\Http\Responses\IssueListResponses;
use App\Http\Responses\IssueListDataResponses;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\IssuesCommentListRequest;
use App\Http\Requests\IssuesCommentCreateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IssueController extends Controller
{
    /**
     * 查詢議題列表
     * @param \App\Http\Requests\IssueListRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function list(IssueListRequest $request)
    {
        // 取得請求參數
        $req = $request->validated();
        $token = $req['token'];

        // 驗證用戶
        TokenAuth(token: $token);

        $result = Issue::with(relations: 'user')->with('assignees')->orderBy('id', 'desc')->paginate(perPage: $req['page_size'], page: $req['page']);
        if ($result->total() <= 0) {
            return response()->json(data: ['message' => "No data found"], status: Response::HTTP_NOT_FOUND);
        }

        // 回傳數據
        $datas = [];
        foreach ($result->items() as $issue) {
            $assignee = [];
            foreach ($issue->assignees as $assignees) {
                $user = User::where('no', $assignees['user_no'])->first();
                if (!empty($user)) {
                    array_push($assignee, $user->account);
                }
            }
            $data = new IssueListDataResponses(
                id: $issue['id'],
                title: $issue['title'],
                content: $issue['content'],
                createdAt: $issue['created_at'],
                updatedAt: $issue['updated_at'],
                author: $issue['user']['account'],
                assignee: $assignee
            );
            array_push($datas, $data);
        }

        return response()->json(data: new IssueListResponses(datas: $datas, total: $result->total()), status: Response::HTTP_OK);
    }

    /**
     * 建立議題
     * @param \App\Http\Requests\IssueCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(IssueCreateRequest $request)
    {
        // 取得請求參數
        $req = $request->validated();
        $token = $req['token'];

        $user = TokenAuth(token: $token);

        $assignee = $req['assignee'];
        $assignee = array_unique($assignee);


        foreach ($assignee as $assigneeNo) {
            // 不可指派給自己
            if ($assigneeNo == $user->no) {
                return response()->json(data: ['message' => "Cannot be assigned to self"], status: Response::HTTP_BAD_REQUEST);
            }
            // 驗證用戶是否存在
            if (!User::where('no', $assigneeNo)->exists()) {
                return response()->json(data: ['message' => "Cannot find user info by $assigneeNo"], status: Response::HTTP_BAD_REQUEST);
            }
        }

        DB::transaction(callback: function () use ($req, $user, $assignee): void {
            // 建立議題
            $issue = Issue::create(attributes: [
                'title' => $req['title'],
                'content' => $req['content'],
                'user_no' => $user->no,
            ]);

            foreach ($assignee as $assigneeNo) {
                // 建立指派
                IssuesAssignee::create(attributes: [
                    'issues_id' => $issue->id,
                    'user_no' =>  $assigneeNo,
                ]);
            }
        });

        return response()->json(data: ['message' => "SUCCESS"], status: Response::HTTP_OK);
    }

    /**
     * 查詢議題評論列表
     * @param \App\Http\Requests\IssuesCommentListRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function commentList(IssuesCommentListRequest $request)
    {
        // 取得請求參數
        $req = $request->validated();
        $token = $req['token'];

        // 驗證用戶
        TokenAuth(token: $token);

        $result =  IssuesComment::with('user')->where("issues_id", $req['issues_id'])->paginate(perPage: $req['page_size'], page: $req['page']);
        if ($result->total() <= 0) {
            return response()->json(data: ['message' => "No data found"], status: Response::HTTP_NOT_FOUND);
        }

        return response()->json(data: ['datas' => $result->items(), 'total' => $result->total()], status: Response::HTTP_OK);
    }

    /**
     * 建立議題評論
     * @param \App\Http\Requests\IssuesCommentCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function commentCreate(IssuesCommentCreateRequest $request)
    {
        // 取得請求參數
        $req = $request->validated();
        $token = $req['token'];

        // 驗證用戶
        $user = TokenAuth(token: $token);

        // 驗證資料
        $exists = Issue::where('id', $req['issues_id'])->exists();
        if (!$exists) {
            return response()->json(data: ['message' => "No data found"], status: Response::HTTP_NOT_FOUND);
        }

        // 新增議題評論
        IssuesComment::create(attributes: [
            'issues_id' => $req['issues_id'],
            'user_no' => $user->no,
            'content' => $req['content'],
        ]);

        return response()->json(data: ['message' => "SUCCESS"], status: Response::HTTP_OK);
    }
}
