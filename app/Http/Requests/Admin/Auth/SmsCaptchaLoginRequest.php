<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SmsCaptchaLoginRequest.
 *
 * @package App\Http\Requests\Admin\Auth
 * @property-read string $mobileNumber
 * @property-read string $captcha
 */
class SmsCaptchaLoginRequest extends FormRequest
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
        ];
    }
}
