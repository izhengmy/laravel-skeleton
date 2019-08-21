<?php

namespace App\Http\Resources\Admin\Permission;

use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Permission $permission */
        $permission = $this->resource;

        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'cnName' => $permission->cn_name,
            'guardName' => $permission->guard_name,
            'createdAt' => (string) $permission->created_at,
            'updatedAt' => (string) $permission->updated_at,
        ];
    }
}
