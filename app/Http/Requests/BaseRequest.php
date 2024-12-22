<?php

namespace App\Http\Requests;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class  BaseRequest extends FormRequest
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
        // $rules = $this->childRules();
        // $rules[] = ['token' => 'required|string|min:32|max:32',];
        // // array_push($rules, ['token' => 'required|string|min:32|max:32',]);
        // return $rules;

        return array_merge($this->childRules(), [
            'token' => 'required|string|min:32|max:32',
        ]);
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

    /**
     * 子類別參數規則
     * @return array
     */
    abstract  protected function childRules(): array;
}
