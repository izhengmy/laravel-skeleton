<?php

namespace App\Http\Resources\Admin\Admin;

use Override\Laravel\Illuminate\Http\Resources\Json\ResourceCollection;

class AdminCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['admins' => $this->collection->map(function ($item) {
            return new AdminResource($item);
        })->all()];
    }
}
