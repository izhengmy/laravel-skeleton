<?php

namespace App\Http\Resources\Admin\Account;

use App\Http\Resources\Admin\Permission\RoleResource;
use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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

        return [
            'id' => $admin->id,
            'username' => $admin->username,
            'mobileNumber' => $admin->mobile_number,
            'realName' => $admin->real_name,
            'roles' => $admin->roles->map(function ($item) {
                return new RoleResource($item);
            })->all(),
        ];
    }
}
