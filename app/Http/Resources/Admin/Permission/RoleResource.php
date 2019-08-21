<?php

namespace App\Http\Resources\Admin\Permission;

use App\Http\Resources\Admin\Menu\MenuResource;
use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Role $role */
        $role = $this->resource;
        $permissionsLoaded = $role->relationLoaded('permissions');
        $menusLoaded = $role->relationLoaded('menus');

        return [
            'id' => $role->id,
            'name' => $role->name,
            'cnName' => $role->cn_name,
            'guardName' => $role->guard_name,
            'permissions' => $this->when($permissionsLoaded, function () use ($role) {
                return $role->permissions->map(function ($item) {
                    return new PermissionResource($item);
                })->all();
            }),
            'menus' => $this->when($menusLoaded, function () use ($role) {
                return $role->menus->map(function ($item) {
                    return new MenuResource($item);
                })->all();
            }),
            'createdAt' => (string) $role->created_at,
            'updatedAt' => (string) $role->updated_at,
        ];
    }
}
