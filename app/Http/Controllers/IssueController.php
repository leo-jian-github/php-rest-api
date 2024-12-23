<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Issue;
use Ramsey\Uuid\Uuid;
use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Models\IssuesAssignee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\IssueListRequest;
use function App\Http\Helpers\TokenAuth;
use App\Http\Requests\IssueCreateRequest;
use App\Http\Responses\IssueListResponses;
use Symfony\Component\HttpFoundation\Response;
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
        try {
            // 取得請求參數
            $req = $request->validated();
            $token = $req['token'];

            // 驗證用戶
            TokenAuth(token: $token);

            $result = Issue::with(relations: 'user')->orderBy('id', 'desc')->paginate(perPage: $req['page_size'], page: $req['page']);
            $value = new IssueListResponses(value: $result);
            return response()->json(data: $value, status: Response::HTTP_OK);
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 建立議題
     * @param \App\Http\Requests\IssueCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(IssueCreateRequest $request)
    {
        try {
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
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
