<?php

namespace App\Http\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\TokenAuthException;

function TokenAuth(string $token): User
{
    // 取得快取資料
    $userNo = Cache::get("user_token_$token");
    if (empty($userNo)) {
        throw new TokenAuthException();
    }

    // 查詢用戶資訊
    $user = User::where('no', $userNo)->first();

    if (empty($user)) {
        throw new \Exception("Can not find user info");
    }

    // 刷新快取
    Cache::put("user_token_$token", $userNo, 120);

    return $user;
}
