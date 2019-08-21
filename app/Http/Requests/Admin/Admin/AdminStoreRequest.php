<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AdminStoreRequest.
 *
 * @package App\Http\Requests\Admin\Admin
 * @property-read string $username
 * @property-read string $mobileNumber
 * @property-read string $password
 * @property-read string $passwordConfirmation
 * @property-read string $realName
 * @property-read bool $enabled
 * @property-read int[] $roleIds
 */
class AdminStoreRequest extends FormRequest
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
            'password' => 'required|string|between:8,16',
            'passwordConfirmation' => 'required|same:password',
            'realName' => 'required|string|max:20',
            'enabled' => 'required|boolean',
            'roleIds' => 'array',
            'roleIds.*' => 'required|integer',
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
            'passwordConfirmation' => '确认密码',
            'realName' => '真实姓名',
            'roleIds' => '角色 ID',
            'roleIds.*' => '角色 ID',
        ];
    }
}
