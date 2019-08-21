<?php

namespace App\Http\Resources\Admin\Menu;

use App\Models\Menu;
use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Menu $menu */
        $menu = $this->resource;

        return $this->item($menu);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Menu  $menu
     * @return array
     */
    protected function item(Menu $menu)
    {
        $childrenLoaded = $menu->relationLoaded('children');

        return [
            'id' => $menu->id,
            'parentId' => $menu->parent_id,
            'path' => $menu->path,
            'name' => $menu->name,
            'icon' => $menu->icon,
            'sort' => $menu->sort,
            'newWindow' => $menu->new_window,
            'enabled' => $menu->enabled,
            'children' => $this->when(! $menu->isLeaf() && $childrenLoaded, function () use ($menu) {
                return $menu->children->map(function ($item) {
                    return $this->item($item);
                })->all();
            }),
        ];
    }
}
