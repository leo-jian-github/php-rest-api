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
use function App\Http\Helpers\TokenAuth;
use App\Http\Requests\IssueCreateRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        //
    }
}
