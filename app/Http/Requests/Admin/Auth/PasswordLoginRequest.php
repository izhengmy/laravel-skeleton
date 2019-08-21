<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PasswordLoginRequest.
 *
 * @package App\Http\Requests\Admin\Auth
 * @property-read string $username
 * @property-read string $password
 * @property-read string $captchaKey
 * @property-read string $captcha
 */
class PasswordLoginRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required',
            'captchaKey' => 'required',
            'captcha' => 'required|captcha_api:'.$this->input('captchaKey'),
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
            'captchaKey' => '图形验证码',
            'captcha' => '图形验证码',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'captcha.captcha_api' => '图形验证码错误',
        ];
    }
}
