<?php

namespace App\Models\Filters\Traits;

trait StartCreatedAt
{
    /**
     * 开始创建时间筛选.
     *
     * @param  string  $value
     * @return $this
     */
    public function startCreatedAt($value)
    {
        return $this->where('created_at', '>=', $value);
    }
}
