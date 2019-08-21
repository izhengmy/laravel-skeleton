<?php

namespace App\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProfileUpdateRequest.
 *
 * @package App\Http\Requests\Admin\Account
 * @property-read string $username
 * @property-read string $mobileNumber
 * @property-read string $realName
 */
class ProfileUpdateRequest extends FormRequest
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
            'username' => 'required|string|max:20',
            'mobileNumber' => 'required|china_mobile_number',
            'realName' => 'required|string|max:20',
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
            'realName' => '真实姓名',
        ];
    }
}
