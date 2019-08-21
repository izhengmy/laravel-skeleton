<?php

namespace App\Http\Resources\Admin\EasySmsLog;

use Override\Laravel\Illuminate\Http\Resources\Json\ResourceCollection;

class EasySmsLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['easySmsLogs' => $this->collection->map(function ($item) {
            return new EasySmsLogResource($item);
        })->all()];
    }
}
