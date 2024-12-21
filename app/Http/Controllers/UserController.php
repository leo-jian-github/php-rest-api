<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * 用戶註冊
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $request->validate(rules: [
                'account' => 'required|string|unique:users',
                'password' => 'required|string|min:32|max:32',
                'name' => 'required|string|min:1|max:20',
            ]);

            User::create(attributes: [
                'account' => $request->account,
                'password' => $request->password,
                'name' => $request->name,
            ]);
            // $user = User::create($request->all());

            return response(status: Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(data: ['errors' => $e->errors()], status: 422);
        } catch (\Exception $th) {
            return response(status: Response::HTTP_INTERNAL_SERVER_ERROR, content: $th->getMessage());
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
