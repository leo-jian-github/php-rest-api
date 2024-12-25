<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\FormRequestValidationException;

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
        throw new FormRequestValidationException($validator->getMessageBag()->first());
    }

    /**
     * 子類別參數規則
     * @return array
     */
    abstract  protected function childRules(): array;
}
