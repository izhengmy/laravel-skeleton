<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\Admin\Permission\RoleResource;
use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = $this->resource;
        $rolesLoaded = $admin->relationLoaded('roles');

        return [
            'id' => $admin->id,
            'username' => $admin->username,
            'mobileNumber' => $admin->mobile_number,
            'realName' => $admin->real_name,
            'enabled' => $admin->enabled,
            'roles' => $this->when($rolesLoaded, function () use ($admin) {
                return $admin->roles->map(function ($item) {
                    return new RoleResource($item);
                })->all();
            }),
            'createdAt' => (string) $admin->created_at,
            'updatedAt' => (string) $admin->updated_at,
            'deletedAt' => (string) $admin->deleted_at,
        ];
    }
}
