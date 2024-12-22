<?php

namespace App\Http\Controllers;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\UserToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpNameRequest;
use App\Http\Requests\UserRegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * 用戶註冊
     * @param \App\Http\Requests\UserRegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            // 取得請求參數
            $req = $request->validated();

            // 驗證帳號是否已存在
            $exists = User::where('account', $req['account'])->exists();
            if ($exists) {
                return response()->json(data: ['message' => "Account is exists"], status: Response::HTTP_CONFLICT);
            }

            $token = Uuid::uuid4()->toString();
            $token = str_replace('-', '', $token);

            $userNo = null;
            DB::transaction(callback: function () use ($req, $token): void {
                // 建立用戶
                $user = User::create(attributes: [
                    'account' => $req['account'],
                    'password' => $req['password'],
                    'name' => $req['name'],
                ]);

                $userNo  = $user->no;
                // 建立 Token
                UserToken::create(attributes: [
                    'token' => $token,
                    'user_no' => $userNo,
                ]);
            });

            // 新增快取資料
            Cache::put("user_token_$token", $userNo, 120);

            return response()->json(data: ['message' => "SUCCESS", 'token' => $token], status: Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 用戶登入
     * @param \App\Http\Requests\UserLoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        try {
            // 取得請求參數
            $req = $request->validated();

            // 查詢用戶資訊
            $user = User::where('account', $req['account'])->first();

            log::debug($user);

            if (empty($user) || $user->password != $req['password']) {
                return response()->json(data: ['message' => 'Wrong account or password'], status: Response::HTTP_UNAUTHORIZED);
            }

            $token = Uuid::uuid4()->toString();
            $token = str_replace('-', '', $token);

            DB::transaction(callback: function () use ($user, $token): void {
                // 移除舊的 token
                UserToken::where('user_no', $user->no)->delete();

                // 建立 Token
                UserToken::create(attributes: [
                    'token' => $token,
                    'user_no' => $user->no,
                ]);
            });

            // 新增快取資料
            Cache::put("user_token_$token", $user->no, 120);

            return response()->json(data: ['message' => "SUCCESS", 'token' => $token], status: Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 更新暱稱
     * @param \App\Http\Requests\UserUpNameRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function upname(UserUpNameRequest $request)
    {
        try {
            // 取得請求參數
            $req = $request->validated();
            $token = $req['token'];

            // 取得快取資料
            $userNo = Cache::get("user_token_$token");

            log::debug("userno: $userNo");

            if (empty($userNo)) {
                return response()->json(data: ['message' => 'Token expired'], status: Response::HTTP_FORBIDDEN);
            }

            // 刷新快取
            Cache::put("user_token_$token", $userNo, 120);

            // 查詢用戶資訊
            $user = User::find($userNo);

            log::debug("user: $user");

            if (empty($user)) {
                return response()->json(data: ['message' => 'Can not find user info'], status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $user->name = $req['name'];
            $user->save();

            return response()->json(data: ['message' => "SUCCESS"], status: Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 取得測試用 token (X-CSRF-TOKEN)
     * @return string
     */
    public function token()
    {
        return csrf_token();
    }
}
