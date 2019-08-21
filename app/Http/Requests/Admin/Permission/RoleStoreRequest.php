<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleStoreRequest.
 *
 * @package App\Http\Requests\Admin\Permission
 * @property-read string $name
 * @property-read string $cnName
 * @property-read int[] $permissionIds
 * @property-read int[] $menuIds
 */
class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cnName' => 'required|string|max:255',
            'permissionIds' => 'array',
            'permissionIds.*' => 'required|integer',
            'menuIds' => 'array',
            'menuIds.*' => 'required|integer',
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
            'name' => '角色名称',
            'cnName' => '角色中文名称',
            'permissionIds' => '权限 ID',
            'permissionIds.*' => '权限 ID',
            'menuIds' => '菜单 ID',
            'menuIds.*' => '菜单 ID',
        ];
    }
}
