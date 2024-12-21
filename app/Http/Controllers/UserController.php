<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function register(UserRequest $request)
    {
        try {
            // 取得請求參數
            $req = $request->validated();

            // 驗證帳號是否已存在
            $exists = User::where('account', $req['account'])->exists();
            if ($exists) {
                return response()->json(data: ['message' => "Account is exists"], status: Response::HTTP_CONFLICT);
            }

            // 建立用戶
            User::create(attributes: [
                'account' => $req['account'],
                'password' => $req['password'],
                'name' => $req['name'],
            ]);

            // TODO 用戶登入

            return response()->json(data: ['message' => "SUCCESS"], status: Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(data: ['message' => $e->getMessage()], status: Response::HTTP_BAD_REQUEST);
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
