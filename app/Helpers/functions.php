<?php

namespace App\Http\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

function TokenAuth(string $token): User
{
    // 取得快取資料
    $userNo = Cache::get("user_token_$token");
    if (empty($userNo)) {
        throw new HttpResponseException(response()->json(data: ['message' => 'Token expired'], status: Response::HTTP_FORBIDDEN));
    }

    // 查詢用戶資訊
    $user = User::where('no', $userNo)->first();

    if (empty($user)) {
        throw new HttpResponseException(response()->json(data: ['message' => 'Can not find user info'], status: Response::HTTP_INTERNAL_SERVER_ERROR));
    }

    // 刷新快取
    Cache::put("user_token_$token", $userNo, 120);

    return $user;
}
