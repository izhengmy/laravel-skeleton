<?php

namespace App\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PasswordUpdateRequest.
 *
 * @package App\Http\Requests\Admin\Account
 * @property-read string $oldPassword
 * @property-read string $newPassword
 * @property-read string $newPasswordConfirmation
 */
class PasswordUpdateRequest extends FormRequest
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
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|between:8,16',
            'newPasswordConfirmation' => 'required|same:newPassword',
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
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'newPasswordConfirmation' => '确认密码',
        ];
    }
}
