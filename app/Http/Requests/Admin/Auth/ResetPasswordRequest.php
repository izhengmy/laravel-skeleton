<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResetPasswordRequest.
 *
 * @package App\Http\Requests\Admin\Auth
 * @property-read string $mobileNumber
 * @property-read string $captcha
 * @property-read string $password
 * @property-read string $passwordConfirmation
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobileNumber' => 'required|china_mobile_number',
            'captcha' => 'required|string',
            'password' => 'required|string|between:8,16',
            'passwordConfirmation' => 'required|same:password',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'mobileNumber' => '手机号码',
            'captcha' => '短信验证码',
            'passwordConfirmation' => '确认密码',
        ];
    }
}
