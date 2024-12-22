<?php

namespace App\Http\Requests;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => 'required|string|regex:/^[a-z0-9_-]{6,20}$/',
            'password' => 'required|string|regex:/^[a-zA-Z0-9]{32}$/',
        ];
    }

    /**
     * 參數驗證返回錯誤
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public function failedValidation(Validator $validator)
    {
        // 參數驗證失敗回傳錯誤資訊
        $response = response()->json(data: ['message' => $validator->getMessageBag()->first()], status: Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($response);
    }
}
