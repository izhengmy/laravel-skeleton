<?php

namespace App\Http\Resources\Admin\EasySmsLog;

use Override\Laravel\Illuminate\Http\Resources\Json\JsonResource;

class EasySmsLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\EasySmsLog $easySmsLog */
        $easySmsLog = $this->resource;

        return [
            'id' => $easySmsLog->id,
            'mobileNumber' => $easySmsLog->mobile_number,
            'message' => $easySmsLog->message,
            'results' => $easySmsLog->results,
            'successful' => $easySmsLog->successful,
            'createdAt' => (string) $easySmsLog->created_at,
            'updatedAt' => (string) $easySmsLog->updated_at,
        ];
    }
}
