<?php

namespace App\Http\Resources\Admin\Menu;

use Override\Laravel\Illuminate\Http\Resources\Json\ResourceCollection;

class MenuCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['menus' => $this->collection->map(function ($item) {
            return new MenuResource($item);
        })->all()];
    }
}
