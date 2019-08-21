<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MenuStoreRequest.
 *
 * @package App\Http\Requests\Admin\Menu
 * @property int|null $parentId
 * @property string $path
 * @property string $name
 * @property string $icon
 * @property int $sort
 * @property bool $newWindow
 * @property bool $enabled
 */
class MenuStoreRequest extends FormRequest
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
            'parentId' => 'nullable|integer',
            'path' => 'required|string|max:2048',
            'name' => 'required|string|max:20',
            'icon' => 'string|max:20',
            'sort' => 'required|integer|unsigned|max:255',
            'newWindow' => 'required|boolean',
            'enabled' => 'required|boolean',
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
            'parentId' => '父级菜单 ID',
            'path' => '菜单路径',
            'name' => '菜单名称',
            'icon' => '菜单图标',
            'sort' => '排序值',
            'newWindow' => '是否新窗口打开',
        ];
    }
}
