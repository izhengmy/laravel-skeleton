<?php

namespace App\Http\Resources\Admin\Permission;

use Override\Laravel\Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['permissions' => $this->collection->map(function ($item) {
            return new PermissionResource($item);
        })->all()];
    }
}
