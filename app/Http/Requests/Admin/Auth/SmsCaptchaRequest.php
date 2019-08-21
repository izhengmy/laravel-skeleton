<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SmsCaptchaRequest.
 *
 * @package App\Http\Requests\Admin\Auth
 * @property-read string $mobileNumber
 * @property-read string $captchaKey
 * @property-read string $captcha
 */
class SmsCaptchaRequest extends FormRequest
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
            'mobileNumber' => '手机号码',
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
